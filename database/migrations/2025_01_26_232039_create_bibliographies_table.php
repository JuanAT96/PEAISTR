<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bibliographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('author');
            $table->integer('year');
            $table->string('title');
            $table->string('editorial');
            $table->string('format');
            $table->string('code')->nullable();
            $table->string('link')->nullable();
            $table->string('uplift');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bibliographies');
    }
};
