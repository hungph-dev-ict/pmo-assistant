<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Thêm cột description sau cột name trong bảng tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
        });

        // Đổi tên cột client_head_id thành tenant_head_acc_id trong bảng users
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('client_head_id', 'tenant_head_acc_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Lùi lại thay đổi: xóa cột description trong bảng tenants
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        // Lùi lại thay đổi: đổi tên cột tenant_head_acc_id thành client_head_id trong bảng users
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('tenant_head_acc_id', 'client_head_id');
        });
    }
};
