<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string("action");
            $table->string("description")->nullable()->default("null");
            $table->string("route")->nullable()->default("null");
            $table->foreignId("item_id")->nullable();
            $table->foreignId("user_id");
            $table->timestamp("created_at")->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
