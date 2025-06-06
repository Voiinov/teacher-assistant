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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthday')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->char('gender')->nullable()->default(NULL); // m/f
            $table->string('code')->nullable(); // ідентифікапційний код (якщо нема, то ПІБ) в md5
            $table->integer('role')->nullable(); //студент/староста
            $table->date('enrolled')->nullable();
            $table->date('expelled')->nullable();
            $table->string('email')->nullable();
            $table->string("access_token")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();
        });

        Schema::create('student_events', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId("student_id");
            $table->integer("event");
            $table->date("date");
            $table->string("title");
            $table->longText("note")->nullable();
            $table->timestamps();
        });

        Schema::create('student_var', function (Blueprint $table) {
            $table->foreignId("student_id")->references('id')->on("students");
            $table->foreignId("variable");
            
            $table->primary(['student_id', 'variable']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('student_events');
        Schema::dropIfExists('student_var');
    }
};
