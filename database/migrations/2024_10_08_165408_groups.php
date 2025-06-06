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
        Schema::create("groups", function (Blueprint $table) {
            $table->id();
            $table->string("mask");
            $table->date("open");
            $table->date("close");
            $table->foreignId("groupe_type")->default(1);
            $table->foreignId("curriculum_id")->default(0);
            $table->timestamps();
        });
        
        Schema::create("groups_setts", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('group_id');
            $table->foreignId('value');
            $table->integer('type')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('groups_setts');
    }
};
