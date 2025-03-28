<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email'); // Thêm cột avatar trong bảng users
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('name'); // Thêm cột logo trong bảng tenants
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar'); // Xóa cột avatar
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('logo'); // Xóa cột logo
        });
    }
};
