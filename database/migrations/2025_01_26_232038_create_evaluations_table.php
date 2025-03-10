<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('unit');
            $table->text('Learning_outcomes');
            $table->string('type'); // Formativa o Sumativa
            $table->text('criteria');
            $table->text('activity');
            $table->decimal('first_partial', 5, 2)->nullable();
            $table->decimal('second_partial', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
