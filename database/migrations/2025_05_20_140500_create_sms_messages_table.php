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
        // Create SMS Templates table
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Create SMS Groups table
        Schema::create('sms_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Create SMS Group Members table
        Schema::create('sms_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('sms_groups')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['group_id', 'member_id']);
        });

        // Create SMS Messages table
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users');
            $table->text('content');
            $table->string('status')->default('draft');
            $table->foreignId('template_id')->nullable()->constrained('sms_templates');
            $table->foreignId('group_id')->nullable()->constrained('sms_groups');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create SMS Message Recipients table
        Schema::create('sms_message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('sms_messages')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members');
            $table->string('phone_number');
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_message_recipients');
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_group_members');
        Schema::dropIfExists('sms_groups');
        Schema::dropIfExists('sms_templates');
    }
};
