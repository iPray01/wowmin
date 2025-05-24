<?php

namespace App\Services;

use App\Models\Donation;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Exception;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for a donation
     *
     * @param Donation $donation
     * @return array
     * @throws ApiErrorException
     */
    public function createPaymentIntent(Donation $donation)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $donation->amount * 100, // Convert to pesewas
                'currency' => config('services.stripe.currency'),
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_name' => $donation->member->full_name,
                    'donation_type' => $donation->donation_type,
                ],
                'description' => "Donation - {$donation->donation_type}",
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            throw new Exception('Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Process a successful payment
     *
     * @param string $paymentIntentId
     * @return bool
     * @throws ApiErrorException
     */
    public function processSuccessfulPayment(string $paymentIntentId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $donationId = $paymentIntent->metadata['donation_id'];
            
            $donation = Donation::findOrFail($donationId);
            $donation->update([
                'transaction_id' => $paymentIntentId,
                'payment_status' => 'completed',
                'payment_date' => now(),
            ]);

            // If this is a recurring donation, schedule the next one
            if ($donation->is_recurring) {
                $this->scheduleNextRecurringDonation($donation);
            }

            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to process payment: ' . $e->getMessage());
        }
    }

    /**
     * Schedule the next recurring donation
     *
     * @param Donation $donation
     * @return void
     */
    private function scheduleNextRecurringDonation(Donation $donation)
    {
        $nextDonationDate = match($donation->recurrence_frequency) {
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'annually' => now()->addYear(),
            default => null,
        };

        if ($nextDonationDate) {
            Donation::create([
                'member_id' => $donation->member_id,
                'amount' => $donation->amount,
                'payment_method' => $donation->payment_method,
                'donation_type' => $donation->donation_type,
                'campaign_id' => $donation->campaign_id,
                'is_recurring' => true,
                'recurrence_frequency' => $donation->recurrence_frequency,
                'is_gift_aid_eligible' => $donation->is_gift_aid_eligible,
                'scheduled_date' => $nextDonationDate,
                'notes' => 'Recurring donation',
            ]);
        }
    }

    /**
     * Process gift aid for a donation
     *
     * @param Donation $donation
     * @return float
     */
    public function processGiftAid(Donation $donation)
    {
        if (!$donation->is_gift_aid_eligible) {
            return 0;
        }

        // Gift Aid calculation (25% in the UK model)
        $giftAidAmount = $donation->amount * 0.25;

        // Record gift aid processing
        $donation->update([
            'gift_aid_amount' => $giftAidAmount,
            'gift_aid_processed_at' => now(),
        ]);

        return $giftAidAmount;
    }
} 