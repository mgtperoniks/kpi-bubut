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
        Schema::create('items_snapshot', function (Blueprint $table) {
            $table->id();

            $table->string('item_code', 50)->unique();
            $table->string('item_name', 200);

            // Standar cycle time dalam detik (basis perhitungan KPI)
            $table->unsignedInteger('cycle_time_sec');

            // Waktu terakhir snapshot diambil dari master item
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            // Index untuk kebutuhan agregasi & pencarian
            $table->index('item_name');
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_snapshot');
    }
};
