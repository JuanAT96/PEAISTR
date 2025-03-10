<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id(); //1 en el formato PEA
            $table->string('name', 150)->unique(); // Nombre único de la carrera
            $table->string('research_lines')->nullable();
            $table->string('coordinator')->nullable();
            $table->string('modality')->nullable();
            $table->integer('duration')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
