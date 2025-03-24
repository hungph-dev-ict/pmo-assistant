<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropUnique('components_name_unique');
            $table->unique(['name', 'deleted_at']);
        });
    }

    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropUnique(['name', 'deleted_at']);
            $table->unique('name');
        });
    }
};
