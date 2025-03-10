<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teaching_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curricular_units_id')->constrained()->onDelete('cascade');
            $table->string('schedule')->nullable(); //semana/fechas
            $table->text('content');
            //METODOLOGÍAS DE ENSEÑANZA APRENDIZAJE/HORAS CLASE
            $table->text('contact_teacher');
            $table->unsignedInteger('contact_teacher_duration'); //horas
            $table->text('practical_teaching');
            $table->unsignedInteger('practical_teaching_duration'); //horas
            $table->text('practical_self_employment');
            $table->unsignedInteger('practical_self_employment_duration'); //horas
            $table->text('self_employment');
            $table->unsignedInteger('self_employment_duration'); //horas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_contents');
    }
};
