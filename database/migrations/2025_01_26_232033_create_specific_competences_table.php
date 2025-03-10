<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('specific_competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->text('graduate_profile'); //alta, media, baja
            $table->text('Learning_outcomes');
            $table->text('contribution_level'); //alta, media, baja
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_competences');
    }
};
