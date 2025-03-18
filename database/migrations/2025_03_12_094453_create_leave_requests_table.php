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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_user')->index(); 
            $table->tinyInteger('leave_type'); 
            $table->date('leave_date'); 
            $table->time('leave_start_time')->nullable(); 
            $table->time('leave_end_time')->nullable(); 
            $table->text('leave_reason')->nullable(); 
            $table->tinyInteger('leave_status')->default(0);
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('leave_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
