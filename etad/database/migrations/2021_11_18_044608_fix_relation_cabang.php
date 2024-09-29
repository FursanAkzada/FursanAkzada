<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRelationCabang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_tad', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
        });
        Schema::table('trans_pengajuan_resign', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
        });
        Schema::table('trans_pengajuan_mutasi', function (Blueprint $table) {
            $table->dropForeign(['before_cabang_id']);
            $table->dropForeign(['after_cabang_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
