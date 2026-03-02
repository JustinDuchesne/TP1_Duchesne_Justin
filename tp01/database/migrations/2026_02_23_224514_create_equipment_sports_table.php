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
        Schema::create('equipment_sport', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('sport_id');
            $table->unsignedBigInteger('equipment_id');
            $table->foreign("sport_id")->constrained()->references('id')->on('sports');
            $table->foreign("equipment_id")->constrained()->references('id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_sports');
    }
};
