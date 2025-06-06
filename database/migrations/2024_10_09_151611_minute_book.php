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
        Schema::create("minute_books", function (Blueprint $table) {
            $table->id("id");
            $table->date("doc_date");
            $table->foreignId("doc_type");
            $table->string("number")->unique();
            $table->string("title");
            $table->text("description")->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('munute_books');
    }
};
