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
        Schema::table('tours', function (Blueprint $table) {
            // ===== 1) GỠ UNIQUE CŨ TRÊN title, slug, code =====
            // Chỉ gỡ unique index, KHÔNG xóa cột
            $table->dropUnique(['title']);
            $table->dropUnique(['slug']);
            $table->dropUnique(['code']);

            // ===== 2) TẠO UNIQUE THEO SOFT-DELETE =====

            // title
            $table->string('unique_title')
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN title ELSE NULL END')
                ->nullable();
            $table->unique('unique_title', 'unique_title_not_deleted_unique');

            // slug
            $table->string('unique_slug')
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN slug ELSE NULL END')
                ->nullable();
            $table->unique('unique_slug', 'unique_slug_not_deleted_unique');

            // code
            $table->string('unique_code')
                ->virtualAs('CASE WHEN deleted_at IS NULL THEN code ELSE NULL END')
                ->nullable();
            $table->unique('unique_code', 'unique_code_not_deleted_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            //
        });
    }
};
