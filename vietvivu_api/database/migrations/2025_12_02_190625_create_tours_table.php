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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            // Điểm khởi hành (FK tới start_locations);
            $table->unsignedBigInteger('start_location_id');

            $table->string('title', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            // Trạng thái + sắp xếp
            $table->enum('is_status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('start_location_id')->references('id')->on('start_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
