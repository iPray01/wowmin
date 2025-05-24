<?php

namespace App\Console\Commands;

use App\Models\SmsMessageRecipient;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSmsDeliveryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:check-delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check delivery status for sent SMS messages';

    /**
     * The SMS service instance.
     *
     * @var \App\Services\SmsService
     */
    protected $smsService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\SmsService  $smsService
     * @return void
     */
    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking SMS delivery statuses...');

        try {
            // Get all recipients with sent status and delivery ID
            $recipients = SmsMessageRecipient::where('status', 'sent')
                ->whereNotNull('delivery_id')
                ->get();

            $count = 0;
            foreach ($recipients as $recipient) {
                try {
                    $this->smsService->checkDeliveryStatus($recipient);
                    $count++;
                } catch (\Exception $e) {
                    Log::error('Failed to check SMS delivery status', [
                        'recipient_id' => $recipient->id,
                        'error' => $e->getMessage(),
                    ]);
                    $this->error("Failed to check status for recipient {$recipient->id}: {$e->getMessage()}");
                }
            }

            $this->info("Successfully checked {$count} delivery statuses.");
            return 0;
        } catch (\Exception $e) {
            Log::error('Error checking SMS delivery statuses', [
                'error' => $e->getMessage(),
            ]);
            $this->error("Error checking delivery statuses: {$e->getMessage()}");
            return 1;
        }
    }
} 