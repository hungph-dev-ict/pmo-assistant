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
        Schema::table('projects', function (Blueprint $table) {
            // Thêm các field ngay sau 'description'
            $table->tinyInteger('status')->default(0)->after('description'); // Field status
            $table->string('client_company', 100)->after('status'); // Field client_company
            $table->foreignId('project_manager')->nullable()->after('client_company')->constrained('users')->nullOnDelete(); // Field project_manager
            
            // Thêm các field ngay sau 'end_date'
            $table->decimal('estimated_budget', 10, 2)->nullable()->after('end_date'); // Field estimated_budget
            $table->unsignedBigInteger('estimated_project_duration')->nullable()->after('estimated_budget'); // Field estimated_project_duration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Xóa các field đã thêm
            $table->dropColumn(['status', 'client_company', 'estimated_budget', 'estimated_project_duration']);
            
            // Xóa quan hệ với bảng users
            $table->dropForeign(['project_manager']);
            $table->dropColumn('project_manager');
        });
    }
};
