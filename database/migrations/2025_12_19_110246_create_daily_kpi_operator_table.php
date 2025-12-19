<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_kpi_operator', function (Blueprint $table) {
            $table->id();
            $table->date('kpi_date');
            $table->string('operator_code');

            $table->decimal('total_work_hours', 6, 2);
            $table->integer('total_target_qty');
            $table->integer('total_actual_qty');
            $table->decimal('kpi_percent', 6, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_kpi_operator');
    }
};
