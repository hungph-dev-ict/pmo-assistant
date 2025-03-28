<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('client_head_id')->nullable()->after('email')->constrained('users')->nullOnDelete(); // Field project_manager
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa quan hệ với bảng users
            $table->dropForeign(['client_head_id']);
            $table->dropColumn('client_head_id');
        });
    }
};
