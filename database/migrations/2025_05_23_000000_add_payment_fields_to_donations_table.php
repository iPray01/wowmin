<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_status')->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->decimal('gift_aid_amount', 10, 2)->nullable();
            $table->timestamp('gift_aid_processed_at')->nullable();
            $table->timestamp('scheduled_date')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->json('payment_metadata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_date',
                'gift_aid_amount',
                'gift_aid_processed_at',
                'scheduled_date',
                'payment_intent_id',
                'payment_metadata'
            ]);
        });
    }
}; 