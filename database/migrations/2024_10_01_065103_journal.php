<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id'); // студент (здобувач освіти)
            $table->foreignId('lesson_id'); // урок відповідно до розкладу
            $table->foreignId('theme_id'); // тема уроку
            $table->foreignId('user_id'); // викладач
            // $table->foreignId('subject_id');
            // $table->foreignId('date');
            $table->foreignId('mark_type'); // робота на уроці/домашня робота/контрольна/підсумкова/річна
            $table->tinyInteger('mark'); // оцінка
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal');
    }
};
