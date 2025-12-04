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
        Schema::create('tour_departures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('available_seats')->default(0);
            $table->integer('booked_seats')->default(0);

            $table->decimal('price_adult', 12, 2)->nullable();
            $table->decimal('price_child', 12, 2)->nullable();

            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->integer('discount_percent')->nullable();

            $table->integer('sort_order')->default(0);
            $table->enum('is_status', ['active', 'inactive'])->default('active');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_departures');
    }
};
