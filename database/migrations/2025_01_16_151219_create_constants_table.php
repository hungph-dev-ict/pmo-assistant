<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('constants', function (Blueprint $table) {
            $table->id();
            $table->string('group'); // Nhóm, ví dụ: 'job_position'
            $table->string('key');   // Tên giá trị, ví dụ: 'PM'
            $table->string('value1'); // Giá trị hiển thị, ví dụ: 'Project Manager'
            $table->string('value2')->nullable(); // Giá trị hiển thị, ví dụ: 'Project Manager'
            $table->string('value3')->nullable(); // Giá trị hiển thị, ví dụ: 'Project Manager'
            $table->string('value4')->nullable(); // Giá trị hiển thị, ví dụ: 'Project Manager'
            $table->string('value5')->nullable(); // Giá trị hiển thị, ví dụ: 'Project Manager'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('constants');
    }
};
