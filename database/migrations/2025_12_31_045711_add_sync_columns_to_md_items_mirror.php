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
        Schema::table('md_items_mirror', function (Blueprint $table) {
            $table->timestamp('source_updated_at')
                  ->nullable()
                  ->after('status');

            $table->timestamp('last_sync_at')
                  ->nullable()
                  ->after('source_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('md_items_mirror', function (Blueprint $table) {
            $table->dropColumn([
                'source_updated_at',
                'last_sync_at',
            ]);
        });
    }
};
