<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->tinyInteger('type')->comment('0: Epic, 1: Task');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); // Project relationship
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade'); // Self-referencing for Epic-Task relationship
            $table->foreignId('assignee')->nullable()->constrained('users')->onDelete('cascade'); // Assignee relationship
            $table->string('name'); // Task/Epic name
            $table->text('description')->nullable(); // Description
            $table->date('plan_start_date')->nullable();
            $table->date('plan_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->integer('estimate_effort')->nullable()->comment('MD');
            $table->integer('actual_effort')->nullable()->comment('MD');
            $table->tinyInteger('progress')->default(0)->comment('0-100%'); // Progress in percentage
            $table->tinyInteger('priority')->default(2)->comment('1: High, 2: Medium, 3: Low'); // Priority
            $table->tinyInteger('status')->default(0)->comment('0: Open, 1: In Progress, 2: Completed, etc.'); // Status
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade'); // Creator relationship
            $table->timestamps();
            $table->softDeletes(); // Soft delete for archiving
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
