<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Đổi tên cột estimate_effort thành plan_effort
            $table->renameColumn('estimate_effort', 'plan_effort');

            // Thay đổi kiểu dữ liệu của plan_effort và actual_effort thành DECIMAL(10,2)
            $table->decimal('plan_effort', 10, 2)->nullable()->change();
            $table->decimal('actual_effort', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Đổi tên lại cột plan_effort thành estimate_effort
            $table->renameColumn('plan_effort', 'estimate_effort');

            // Thay đổi kiểu dữ liệu của estimate_effort và actual_effort lại INT
            $table->integer('estimate_effort')->nullable()->change();
            $table->integer('actual_effort')->nullable()->change();
        });
    }
};
