<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_member_id')->constrained('members')->onDelete('cascade');
            $table->string('relationship_type'); // e.g., spouse, child, parent, sibling
            $table->timestamps();

            $table->unique(['member_id', 'related_member_id', 'relationship_type'], 'family_rel_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_relationships');
    }
};
