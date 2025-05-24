<?php

namespace App\Services;

use App\Models\SmsMessage;
use App\Models\SmsMessageRecipient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Twilio\Rest\Client as TwilioClient;

class SmsService
{
    protected $provider;
    protected $twilioClient;
    protected $fromNumber;
    
    public function __construct()
    {
        $this->provider = config('services.sms.provider');
        $this->fromNumber = config('services.sms.twilio.from_number');
        
        if ($this->provider === 'twilio') {
            $this->twilioClient = new TwilioClient(
                config('services.sms.twilio.sid'),
                config('services.sms.twilio.auth_token')
            );
        }
    }

    /**
     * Send an SMS message.
     *
     * @param SmsMessage $message
     * @return void
     */
    public function sendMessage(SmsMessage $message)
    {
        // Update message status to sending
        $message->update(['status' => 'sending']);

        // Process each recipient
        $message->recipients()->where('status', 'pending')->chunk(100, function ($recipients) use ($message) {
            foreach ($recipients as $recipient) {
                try {
                    $response = $this->sendSingleMessage($message->content, $recipient->phone_number);
                    
                    if ($response['success']) {
                        $recipient->update([
                            'status' => $response['status'] ?? 'sent',
                            'delivery_id' => $response['message_id'] ?? null,
                            'sent_at' => now(),
                        ]);
                    } else {
                        $recipient->update([
                            'status' => 'failed',
                            'error_message' => $response['error'] ?? 'Unknown error',
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('SMS sending failed', [
                        'message_id' => $message->id,
                        'recipient_id' => $recipient->id,
                        'error' => $e->getMessage(),
                    ]);

                    $recipient->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        });

        // Update message status based on recipient statuses
        $this->updateMessageStatus($message);
    }

    /**
     * Send a single SMS message using the configured provider.
     *
     * @param string $content
     * @param string $to
     * @return array
     */
    protected function sendSingleMessage($content, $to)
    {
        if ($this->provider === 'twilio') {
            return $this->sendViaTwilio($content, $to);
        }
        
        // Fallback to existing SMS77 implementation
        return $this->sendViaSms77($content, $to);
    }

    /**
     * Send message using Twilio.
     *
     * @param string $content
     * @param string $to
     * @return array
     */
    protected function sendViaTwilio($content, $to)
    {
        try {
            $message = $this->twilioClient->messages->create(
                $to,
                [
                    'from' => $this->fromNumber,
                    'body' => $content,
                    'statusCallback' => route('twilio.webhook')
                ]
            );
            
            // Map initial status immediately
            $initialStatus = $this->mapDeliveryStatus($message->status);
            
            return [
                'success' => true,
                'message_id' => $message->sid,
                'status' => $initialStatus
            ];
        } catch (Exception $e) {
            Log::error('Twilio SMS sending failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send message using SMS77 (existing implementation).
     *
     * @param string $content
     * @param string $to
     * @return array
     */
    protected function sendViaSms77($content, $to)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-rapidapi-host' => config('services.sms.rapidapi_host'),
                'x-rapidapi-key' => config('services.sms.rapidapi_key'),
            ])->post(config('services.sms.base_url') . '/sms', [
                'to' => $to,
                'text' => $content,
                'from' => config('services.sms.from_number'),
                'json' => true,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    return [
                        'success' => true,
                        'message_id' => $data['messages'][0]['id'] ?? null,
                    ];
                }

                return [
                    'success' => false,
                    'error' => $data['error'] ?? 'Message sending failed',
                ];
            }

            return [
                'success' => false,
                'error' => $response->body() ?? 'API request failed',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check delivery status for sent messages.
     *
     * @param SmsMessageRecipient $recipient
     * @return void
     */
    public function checkDeliveryStatus(SmsMessageRecipient $recipient)
    {
        if (!$recipient->delivery_id || $recipient->status !== 'sent') {
            return;
        }

        try {
            if ($this->provider === 'twilio') {
                $this->checkTwilioDeliveryStatus($recipient);
            } else {
                $this->checkSms77DeliveryStatus($recipient);
            }
        } catch (Exception $e) {
            Log::error('Failed to check SMS delivery status', [
                'recipient_id' => $recipient->id,
                'delivery_id' => $recipient->delivery_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check delivery status using Twilio.
     *
     * @param SmsMessageRecipient $recipient
     * @return void
     */
    protected function checkTwilioDeliveryStatus(SmsMessageRecipient $recipient)
    {
        try {
            $message = $this->twilioClient->messages($recipient->delivery_id)->fetch();
            $status = $this->mapDeliveryStatus($message->status);

            $recipient->update([
                'status' => $status,
                'delivered_at' => $status === 'delivered' ? now() : null,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to check Twilio delivery status', [
                'recipient_id' => $recipient->id,
                'delivery_id' => $recipient->delivery_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check delivery status using SMS77.
     *
     * @param SmsMessageRecipient $recipient
     * @return void
     */
    protected function checkSms77DeliveryStatus(SmsMessageRecipient $recipient)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-rapidapi-host' => config('services.sms.rapidapi_host'),
                'x-rapidapi-key' => config('services.sms.rapidapi_key'),
            ])->post(config('services.sms.base_url') . '/rcs/events', [
                'msg_id' => $recipient->delivery_id,
                'to' => $recipient->phone_number,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $status = $this->mapDeliveryStatus($data['status'] ?? 'unknown');

                $recipient->update([
                    'status' => $status,
                    'delivered_at' => $status === 'delivered' ? now() : null,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to check SMS77 delivery status', [
                'recipient_id' => $recipient->id,
                'delivery_id' => $recipient->delivery_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Map provider-specific status to our status.
     *
     * @param string $providerStatus
     * @return string
     */
    protected function mapDeliveryStatus($providerStatus)
    {
        if ($this->provider === 'twilio') {
            return match (strtolower($providerStatus)) {
                'delivered' => 'delivered',
                'failed', 'undelivered' => 'failed',
                'sent', 'accepted' => 'sent',
                'queued', 'sending', 'processing' => 'sending',
                default => 'pending',
            };
        }
        
        // SMS77 status mapping
        return match (strtolower($providerStatus)) {
            'delivered', 'read' => 'delivered',
            'failed', 'rejected', 'undelivered' => 'failed',
            'sent', 'accepted' => 'sent',
            'queued', 'scheduled' => 'pending',
            default => 'unknown',
        };
    }

    /**
     * Update the overall message status based on recipient statuses.
     *
     * @param SmsMessage $message
     * @return void
     */
    protected function updateMessageStatus(SmsMessage $message)
    {
        $totalRecipients = $message->recipients()->count();
        $sentCount = $message->recipients()->where('status', 'sent')->count();
        $failedCount = $message->recipients()->where('status', 'failed')->count();

        if ($failedCount === $totalRecipients) {
            $message->update(['status' => 'failed']);
        } elseif ($sentCount + $failedCount === $totalRecipients) {
            $message->update(['status' => 'sent']);
        }
        // Otherwise, leave as 'sending' as there are still pending messages
    }
} 