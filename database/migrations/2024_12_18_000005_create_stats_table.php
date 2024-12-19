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
        Schema::create('stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('instrument_id'); // Foreign key to instruments
            $table->integer('mentions_daily')->default(0); // Mentions in the last 24 hours
            $table->integer('mentions_weekly')->default(0); // Mentions in the last 7 days
            $table->integer('mentions_monthly')->default(0); // Mentions in the last 30 days
            $table->timestamp('last_updated')->nullable(); // Last time stats were updated
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('instrument_id')->references('id')->on('instruments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
