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
        // Chỉnh sửa bảng tenants
        Schema::table('tenants', function (Blueprint $table) {
            // Xoá cột head_user_id
            $table->dropForeign(['head_user_id']);  // Xoá khóa ngoại
            $table->dropColumn('head_user_id');    // Xoá cột
        });

        // Chỉnh sửa bảng users
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột head_account_flg vào bảng users
            $table->boolean('head_account_flg')->after('tenant_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại trạng thái cũ cho bảng tenants
        Schema::table('tenants', function (Blueprint $table) {
            // Thêm lại cột head_user_id và khóa ngoại
            $table->foreignId('head_user_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        // Quay lại trạng thái cũ cho bảng users
        Schema::table('users', function (Blueprint $table) {
            // Xoá cột head_account_flg
            $table->dropColumn('head_account_flg');
        });
    }
};
