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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('requester_name')->nullable(); // For non-member requests
            $table->string('requester_email')->nullable();
            $table->string('requester_phone')->nullable();
            $table->text('request_content');
            $table->string('status')->default('submitted'); // e.g., 'submitted', 'in_prayer', 'answered', 'archived'
            $table->boolean('is_public')->default(false); // Whether it can be shared with prayer chain
            $table->boolean('is_anonymous')->default(false); // Whether to hide requester identity
            $table->date('answer_date')->nullable();
            $table->text('answer_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Create prayer_chain table for prayer chain members
        Schema::create('prayer_chain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Create prayer_responses table for tracking responses to prayer requests
        Schema::create('prayer_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prayer_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->text('response_content');
            $table->boolean('is_private')->default(true); // Whether visible to requester only or all prayer chain members
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
