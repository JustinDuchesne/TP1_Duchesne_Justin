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
        Schema::create('equipmentsports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreign("sportId")->constrained()->references('id')->on('sports');
            $table->foreign("equipmentId")->constrained()->references('id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipmentsports');
    }
};
