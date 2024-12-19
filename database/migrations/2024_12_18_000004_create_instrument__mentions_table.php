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
        Schema::create('instrument_mentions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instrument_id'); // Foreign key to instruments
            $table->unsignedBigInteger('tweet_id'); // Foreign key to tweets
            $table->timestamp('mentioned_at'); // Copied from tweet creation time
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('instrument_id')->references('id')->on('instruments')->onDelete('cascade');
            $table->foreign('tweet_id')->references('id')->on('tweets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument__mentions');
    }
};
