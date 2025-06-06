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

        Schema::create('curriculum_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->string('title');
            $table->foreignId('user_id');
            $table->foreignId('status')->default(499000);
            $table->integer('hours');
            $table->text('description')->nullable();
            $table->longText('options')->nullable();
            $table->foreignId("doc")->nullable();
            $table->timestamps();

            $table->primary(['id', 'subject_id']);
        });

        // Schema::create('curriculum_mapping', function (Blueprint $table) {
        //     $table->unsignedBigInteger('mapping_id');
        //     $table->unsignedBigInteger('curriculum_id');

        //     $table->foreign('mapping_id')->references('id')->on("curriculum_mappings");
        //     $table->foreign('curriculum_id')->references('id')->on('curriculums');

        //     $table->primary(['mapping_id', 'curriculum_id']);
        // });        
        
        // Schema::create('curriculum_theme', function (Blueprint $table) {
        //     $table->unsignedBigInteger('mapping_id');
        //     $table->unsignedBigInteger('theme_id');

        //     $table->foreign('mapping_id')->references('id')->on("curriculum_mappings");
        //     $table->foreign('theme_id')->references('id')->on('curriculum_themes');

        //     $table->primary(['mapping_id', 'theme_id']);
        // });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_mapping');
        Schema::dropIfExists('curriculum_theme');
        Schema::dropIfExists('curriculum_mappings');
    }
};
