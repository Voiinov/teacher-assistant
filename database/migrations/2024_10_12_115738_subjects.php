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
        
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string("title")->unique();
            $table->string("short_title",20)->nullable()->default(null)->unique();
            $table->integer("subgroup")->nullable()->default(null);
            $table->integer("type")->nullable()->default(null);
            $table->boolean("active")->default(1);
            $table->text("options")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
