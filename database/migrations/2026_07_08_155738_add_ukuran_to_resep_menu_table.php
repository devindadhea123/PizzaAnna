<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resep_menu', function (Blueprint $table) {
            $table->enum('ukuran', ['S', 'M', 'L'])->nullable()->after('id_bahan');
        });
    }

    public function down()
    {
        Schema::table('resep_menu', function (Blueprint $table) {
            $table->dropColumn('ukuran');
        });
    }
};