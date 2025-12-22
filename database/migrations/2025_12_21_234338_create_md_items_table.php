<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('md_items', function (Blueprint $table) {
            $table->string('code')->primary();         // kode item
            $table->string('name');                    // nama item
            $table->unsignedInteger('cycle_time_sec'); // cycle time (detik)
            $table->boolean('active')->default(true);

            // index untuk lookup autofill
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_items');
    }
};
