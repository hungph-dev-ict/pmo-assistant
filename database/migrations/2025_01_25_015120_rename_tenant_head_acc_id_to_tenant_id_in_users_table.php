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
        Schema::table('users', function (Blueprint $table) {
            // Đổi tên cột từ 'tenant_head_acc_id' thành 'tenant_id'
            $table->renameColumn('tenant_head_acc_id', 'tenant_id');
            
            // Cập nhật khóa ngoại, tham chiếu tới bảng 'tenants'
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['tenant_id']);
            
            // Đổi tên lại cột từ 'tenant_id' thành 'tenant_head_acc_id'
            $table->renameColumn('tenant_id', 'tenant_head_acc_id');
        });
    }
};
