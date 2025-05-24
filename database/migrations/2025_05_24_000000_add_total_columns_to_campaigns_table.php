<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->decimal('total_pledges', 10, 2)->default(0);
            $table->decimal('total_fulfilled', 10, 2)->default(0);
        });

        // Update existing campaigns with current totals
        DB::statement('
            UPDATE campaigns c
            SET total_pledges = (
                SELECT COALESCE(SUM(amount), 0)
                FROM pledges
                WHERE campaign_id = c.id
            ),
            total_fulfilled = (
                SELECT COALESCE(SUM(amount_fulfilled), 0)
                FROM pledges
                WHERE campaign_id = c.id
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['total_pledges', 'total_fulfilled']);
        });
    }
}; 