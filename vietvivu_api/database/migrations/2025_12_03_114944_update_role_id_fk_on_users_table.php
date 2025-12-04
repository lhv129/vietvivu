<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            // 1. Xóa khóa ngoại cũ
            $table->dropForeign(['role_id']);
            // Nếu Laravel báo lỗi, dùng tên constraint chính xác:
            // $table->dropForeign('users_role_id_foreign');

            // 2. Tạo khóa ngoại mới KHÔNG có cascade
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('restrict');   // hoặc không ghi gì -> mặc định RESTRICT
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // quay lại cascade nếu rollback
            $table->dropForeign(['role_id']);

            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade');
        });
    }
};
