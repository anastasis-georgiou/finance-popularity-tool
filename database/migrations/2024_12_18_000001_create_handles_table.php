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
        Schema::create('handles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('handle', 255)->unique(); // Twitter handle
            $table->integer('crawling_freq')->default(3600); // Frequency in seconds
            $table->timestamp('last_crawled_at')->nullable(); // Last crawl time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handles');
    }
};
