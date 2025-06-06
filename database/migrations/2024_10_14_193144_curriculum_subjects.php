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

        $this->down();

        Schema::create('curriculum_subjects', function (Blueprint $table) {
            $table->foreignId('curriculum_id')->references("id")->on("curriculums");
            $table->foreignId('subject_id')->references("id")->on("subjects");
            $table->string('weeks')->nullable()->default(null);
            $table->string('hours');            
            $table->text('description')->nullable();
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->nullable();

            $table->primary(['curriculum_id','subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_subjects');
    }
};
