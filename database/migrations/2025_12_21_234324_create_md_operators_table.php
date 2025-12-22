<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('md_operators', function (Blueprint $table) {
            $table->string('code')->primary();     // kode operator
            $table->string('name');                // nama operator
            $table->boolean('active')->default(true);

            // optional index untuk filtering cepat
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_operators');
    }
};
