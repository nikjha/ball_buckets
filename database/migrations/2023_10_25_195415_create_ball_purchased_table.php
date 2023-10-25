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
        Schema::create('ball__purchaseds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ball_id');            
            $table->integer('qty');
            $table->foreign('ball_id')->references('id')->on('balls');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ball__purchaseds');
    }
};
