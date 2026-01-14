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
        Schema::table('production_logs', function (Blueprint $table) {
            $table->string('size', 20)->nullable()->after('heat_number');
            $table->string('customer', 50)->nullable()->after('size');
            $table->string('line', 20)->nullable()->after('customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_logs', function (Blueprint $table) {
            //
        });
    }
};
