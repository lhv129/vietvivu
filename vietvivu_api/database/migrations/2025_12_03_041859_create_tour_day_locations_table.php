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
        Schema::create('tour_day_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_day_id');
            $table->unsignedBigInteger('location_id');
            $table->integer('order_in_day');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_day_locations');
    }
};
