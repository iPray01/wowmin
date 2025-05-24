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
        Schema::create('sms_message_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('sms_messages')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('sms_groups')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['message_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_message_groups');
    }
}; 