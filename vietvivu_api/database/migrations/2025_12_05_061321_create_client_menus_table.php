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
        Schema::create('client_menus', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique(); // slug để route động
            $table->string('url')->nullable(); // nếu menu là URL tùy chỉnh

            // nếu muốn menu đa cấp
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('client_menus')
                ->onDelete('cascade');

            // Loại route: "page", "category", "tour_list", "product", ...
            $table->string('route_type')->nullable();

            // id của đối tượng liên quan (nếu cần)
            $table->unsignedBigInteger('route_id')->nullable();

            // Thứ tự hiển thị
            $table->integer('sort_order')->default(0);

            // Kích hoạt/ẩn
            $table->boolean('is_active')->default(true);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_menus');
    }
};
