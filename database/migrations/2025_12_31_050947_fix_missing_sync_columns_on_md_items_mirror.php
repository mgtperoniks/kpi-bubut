<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('md_items_mirror', function (Blueprint $table) {
            if (!Schema::hasColumn('md_items_mirror', 'source_updated_at')) {
                $table->timestamp('source_updated_at')
                      ->nullable()
                      ->after('status');
            }

            if (!Schema::hasColumn('md_items_mirror', 'last_sync_at')) {
                $table->timestamp('last_sync_at')
                      ->nullable()
                      ->after('source_updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('md_items_mirror', function (Blueprint $table) {
            if (Schema::hasColumn('md_items_mirror', 'last_sync_at')) {
                $table->dropColumn('last_sync_at');
            }

            if (Schema::hasColumn('md_items_mirror', 'source_updated_at')) {
                $table->dropColumn('source_updated_at');
            }
        });
    }
};
