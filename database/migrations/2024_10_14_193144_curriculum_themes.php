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
        // Schema::create('curriculum_themes', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title');
        //     $table->text('description')->nullable();
        //     $table->foreignId('user_id');
        //     $table->foreignId('subject');
        //     $table->boolean('module')->default(0);
        //     $table->bigInteger('grouped')->default(0);
        //     $table->tinyInteger('level')->default(0);
        //     $table->boolean('active')->default(true);
        //     $table->timestamps();
        // });

        Schema::create('curriculum_theme', function (Blueprint $table) {
            $table->unsignedBigInteger('mapping_id');
            $table->unsignedBigInteger('theme_id');
            $table->float('lesson');

            $table->foreign('mapping_id')->references('id')->on("curriculum_mappings")->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('curriculum_themes')->onDelete('cascade');

            $table->primary(['mapping_id', 'theme_id', 'lesson']);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('curriculum_themes');
        Schema::dropIfExists('curriculum_theme');
    }
};
