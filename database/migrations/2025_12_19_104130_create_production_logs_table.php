<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();

            $table->date('production_date');
            $table->string('shift')->nullable();

            $table->string('operator_code');
            $table->string('machine_code');
            $table->string('item_code');

            $table->time('time_start');
            $table->time('time_end');
            $table->decimal('work_hours', 5, 2);

            $table->integer('cycle_time_used_sec');
            $table->integer('target_qty');
            $table->integer('actual_qty');

            $table->decimal('achievement_percent', 5, 2);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_logs');
    }
};
