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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bucket_id')->nullable();
            $table->integer('no_of_balls'); 
            $table->unsignedBigInteger('ball_id'); 
            $table->timestamps();
            $table->foreign('bucket_id')->references('id')->on('buckets');
            $table->foreign('ball_id')->references('id')->on('balls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
