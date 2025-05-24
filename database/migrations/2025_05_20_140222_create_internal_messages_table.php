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
        // First create message groups table for group chats
        Schema::create('message_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Create message_group_user pivot table
        Schema::create('message_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['message_group_id', 'user_id']);
        });
        
        // Now create the main internal messages table
        Schema::create('internal_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->references('id')->on('users')->onDelete('set null'); // For direct messages
            $table->foreignId('message_group_id')->nullable()->references('id')->on('message_groups')->onDelete('cascade'); // For group messages
            $table->text('message_content');
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Create message_attachments table for file sharing
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_message_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_messages');
    }
};
