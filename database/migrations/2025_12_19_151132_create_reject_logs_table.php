<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reject_logs', function (Blueprint $table) {
            $table->id();

            $table->date('reject_date');
            $table->string('operator_code');
            $table->string('machine_code');
            $table->string('item_code');

            $table->integer('reject_qty');
            $table->string('reject_reason')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reject_logs');
    }
};
