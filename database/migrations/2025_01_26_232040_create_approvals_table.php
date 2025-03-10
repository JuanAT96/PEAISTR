<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            // Referencias a la tabla 'users' para roles diferentes
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('coordinator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vice_rector_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
