<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account')->nullable()->after('id');
            $table->tinyInteger('job_position')->nullable()->after('email');
            $table->tinyInteger('status')->default(1)->after('job_position');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account', 'job_position', 'status']);
        });
    }
};
