<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('md_machines', function (Blueprint $table) {
            $table->string('code')->primary();     // kode mesin
            $table->string('name');                // nama mesin
            $table->string('line')->nullable();    // line / area produksi
            $table->boolean('active')->default(true);

            // index ringan
            $table->index('active');
            $table->index('line');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_machines');
    }
};
