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
        // Schema::create('gradebooks', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId("group_id")->constrained("groups");
        //     $table->tinyInteger("study_year");
        //     $table->string("group_index");
        //     $table->date("open");
        //     $table->date("close_semestr")->nullable()->default(null);
        //     $table->date("close_year")->nullable()->default(null);
        //     $table->text("options")->nullable();
        //     $table->timestamp("created_at")->useCurrent();
        //     $table->timestamp('updated_at');
        // });

        Schema::create('gradebook_grades', function (Blueprint $table) {
            $table->integer('gradebook_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('grade_type');
            
            $table->tinyInteger('grade');

            // $table->foreign('gradebook_id')->references('id')->on('gradebooks')->onDelete('cascade');
            // $table->foreign('lesson_id')->references('id')->on('timetables');
            // $table->foreign('student_id')->references('id')->on("students");
            // $table->foreign('subject_id')->references('id')->on("subjects");
            // $table->foreign('user_id')->references('id')->on("users");
            // $table->foreign('grade_type')->references('id')->on("variables");
            
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp('updated_at');

            $table->primary(["gradebook_id","lesson_id","student_id","subject_id","grade_type"]);
        });

        Schema::table('grade_setts', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id');
            $table->string('setts');

            $table->primary(["lesson_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('gradebooks');
        Schema::dropIfExists('gradebook_grades');
    }
};
