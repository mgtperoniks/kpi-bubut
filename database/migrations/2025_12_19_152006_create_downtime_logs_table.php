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
        Schema::create('downtime_logs', function (Blueprint $table) {
            $table->id();
            $table->date('downtime_date');
            $table->string('operator_code', 50)->index();
            $table->string('machine_code', 50)->index();
            $table->unsignedInteger('duration_minutes');
            $table->string('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // tidak pakai updated_at → sesuai kebutuhan analisa downtime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtime_logs');
    }
};
