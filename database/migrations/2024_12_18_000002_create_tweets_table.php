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
        Schema::create('tweets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('handle_id'); // Foreign key to handles table
            $table->string('tweet_id', 255)->unique(); // Twitter's tweet ID
            $table->text('content'); // Full tweet content
            $table->boolean('processed')->default(false); // Whether it's processed
            $table->timestamps(); // For tracking records in our DB

            // Foreign key constraint
            $table->foreign('handle_id')->references('id')->on('handles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
