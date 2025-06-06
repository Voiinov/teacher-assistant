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
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->tinyInteger("study_year");
            $table->text('hours');
            $table->timestamps();
        });

        Schema::create('curriculum_profession', function (Blueprint $table) {
            $table->unsignedBigInteger('profession_id');
            $table->unsignedBigInteger('curriculum_id');

            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');

            $table->primary(['profession_id', 'curriculum_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculums');
        Schema::dropIfExists('curriculum_profession');
    }
};
