<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessRecurringDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled recurring donations';

    /**
     * The payment service instance.
     *
     * @var \App\Services\PaymentService
     */
    protected $paymentService;

    /**
     * Create a new command instance.
     */
    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing recurring donations...');

        try {
            $donations = Donation::where('is_recurring', true)
                ->where('scheduled_date', '<=', now())
                ->where('payment_status', '!=', 'failed')
                ->get();

            $processed = 0;
            foreach ($donations as $donation) {
                try {
                    // Create payment intent for the recurring donation
                    $paymentIntent = $this->paymentService->createPaymentIntent($donation);
                    
                    // Process the payment
                    $this->paymentService->processSuccessfulPayment($paymentIntent['paymentIntentId']);
                    
                    // Process gift aid if eligible
                    if ($donation->is_gift_aid_eligible) {
                        $this->paymentService->processGiftAid($donation);
                    }

                    $processed++;
                } catch (\Exception $e) {
                    Log::error('Failed to process recurring donation', [
                        'donation_id' => $donation->id,
                        'error' => $e->getMessage()
                    ]);

                    $donation->update([
                        'payment_status' => 'failed',
                        'notes' => 'Failed to process recurring payment: ' . $e->getMessage()
                    ]);
                }
            }

            $this->info("Successfully processed {$processed} recurring donations.");
            return 0;
        } catch (\Exception $e) {
            $this->error('Error processing recurring donations: ' . $e->getMessage());
            Log::error('Error in recurring donations command', [
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
} 