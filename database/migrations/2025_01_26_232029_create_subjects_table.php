<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_name', 150); //2 en el formato pea
            $table->string('subject_code', 150)->unique()->nullable(); // 3 en el formato PEA
            // $table->string('level');
            $table->string('curricular_org_unit')->nullable();
            $table->unsignedBigInteger('academic_period_id')->nullable();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('cascade');
            $table->integer('credits')->nullable();
            //carga horaria
            $table->unsignedInteger('contact_teacher')->nullable();
            $table->unsignedInteger('practical_teaching')->nullable();
            $table->unsignedInteger('practical_self_employment')->nullable();
            $table->unsignedInteger('self_employment')->nullable();
            //informacion de la asignatura
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->text('gral_learning_outcome')->nullable();
            //Descripción del proyecto integrador de saberes
            $table->text('description_integrative_project')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
