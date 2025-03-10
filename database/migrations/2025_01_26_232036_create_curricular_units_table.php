<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curricular_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->text('unit'); //ejm unidad 1
            $table->text('name');
            $table->text('learning_outcomes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curricular_units');
    }
};
