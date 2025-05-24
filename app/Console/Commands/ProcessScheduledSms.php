<?php

namespace App\Console\Commands;

use App\Models\SmsMessage;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled SMS messages that are due to be sent';

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
        $this->info('Processing scheduled SMS messages...');

        try {
            // Get all scheduled messages that are due to be sent
            $messages = SmsMessage::where('status', 'scheduled')
                ->where('scheduled_at', '<=', now())
                ->get();

            $count = 0;
            foreach ($messages as $message) {
                try {
                    $this->smsService->sendMessage($message);
                    $count++;
                } catch (\Exception $e) {
                    Log::error('Failed to send scheduled SMS message', [
                        'message_id' => $message->id,
                        'error' => $e->getMessage(),
                    ]);
                    $this->error("Failed to send message {$message->id}: {$e->getMessage()}");
                }
            }

            $this->info("Successfully processed {$count} scheduled messages.");
            return 0;
        } catch (\Exception $e) {
            Log::error('Error processing scheduled SMS messages', [
                'error' => $e->getMessage(),
            ]);
            $this->error("Error processing scheduled messages: {$e->getMessage()}");
            return 1;
        }
    }
} 