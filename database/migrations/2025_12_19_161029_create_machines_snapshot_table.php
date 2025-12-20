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
        Schema::create('machines_snapshot', function (Blueprint $table) {
            $table->id();

            $table->string('machine_code', 50)->unique();
            $table->string('machine_name', 150);

            // Line / area produksi (opsional)
            $table->string('line', 100)->nullable();

            // Waktu terakhir snapshot diambil dari master mesin
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            // Index untuk query dashboard & filter
            $table->index('line');
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines_snapshot');
    }
};
