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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // e.g., 'cash', 'check', 'credit_card', 'bank_transfer'
            $table->string('transaction_id')->nullable();
            $table->date('donation_date');
            $table->string('donation_type'); // e.g., 'tithe', 'offering', 'building_fund', 'missions'
            $table->string('campaign_id')->nullable(); // For campaign-specific donations
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_frequency')->nullable(); // e.g., 'weekly', 'monthly', 'quarterly'
            $table->boolean('is_gift_aid_eligible')->default(false); // For tax benefits
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Create campaigns table for donation campaigns
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 12, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
