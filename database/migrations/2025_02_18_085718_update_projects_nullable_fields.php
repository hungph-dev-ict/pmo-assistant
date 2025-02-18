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
            $table->string('description')->nullable()->change();
            $table->string('client_company', 100)->nullable()->change();
            $table->date('start_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('field_name')->nullable(false)->change();
            $table->string('client_company', 100)->nullable(false)->change();
            $table->string('start_date')->nullable(false)->change();
        });
    }
};
