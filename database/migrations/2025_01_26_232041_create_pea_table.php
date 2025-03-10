<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->json('details'); // Columna JSON para el resto de los datos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pea');
    }
};
