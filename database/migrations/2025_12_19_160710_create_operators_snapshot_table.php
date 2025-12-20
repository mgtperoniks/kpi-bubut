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
        Schema::create('operators_snapshot', function (Blueprint $table) {
            $table->id();

            $table->string('operator_code', 50)->unique();
            $table->string('operator_name', 150);

            $table->string('status', 20)->default('ACTIVE');

            // Waktu terakhir data ini disinkronkan dari master utama
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            // Optional: index tambahan jika sering dipakai filter
            $table->index('status');
            $table->index('synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators_snapshot');
    }
};
