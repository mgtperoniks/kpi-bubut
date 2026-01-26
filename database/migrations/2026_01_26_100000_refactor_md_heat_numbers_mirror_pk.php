<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('md_heat_numbers_mirror', function (Blueprint $table) {
            // Drop existing primary key on heat_number
            // Using raw SQL for safety as dropPrimary behavior varies by driver/version with named constraints
            // But standard Laravel way:
            $table->dropPrimary();
        });

        Schema::table('md_heat_numbers_mirror', function (Blueprint $table) {
            // Add new auto-increment ID
            $table->id()->first();

            // Allow heat_number to be non-unique (globally), but we want unique combination with item_code
            $table->index('heat_number');
            $table->unique(['heat_number', 'item_code'], 'unique_heat_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('md_heat_numbers_mirror', function (Blueprint $table) {
            $table->dropUnique('unique_heat_item');
            $table->dropIndex(['heat_number']);
            $table->dropColumn('id');
            $table->primary('heat_number');
        });
    }
};
