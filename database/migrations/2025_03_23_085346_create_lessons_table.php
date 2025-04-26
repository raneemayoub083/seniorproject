<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_subject_teacher_id')
                ->constrained('section_subject_teacher') // Pivot table
                ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video')->nullable(); // Video URL or path
            $table->string('pdf')->nullable(); // PDF path
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
