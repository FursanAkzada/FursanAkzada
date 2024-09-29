<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchPengajuanTad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_tad_kandidat', function (Blueprint $table) {
            $table->bigInteger('tl_id')->unsigned()->nullable();
            $table->integer('batch')->unsigned()->nullable();

            $table->foreign('tl_id')->references('id')->on('trans_pengajuan_tad_tl');
        });

        Schema::table('trans_pengajuan_tad_tl', function (Blueprint $table) {
            $table->integer('batch')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_pengajuan_tad_kandidat', function (Blueprint $table) {
            $table->dropForeign(['tl_id']);
            $table->dropColumn(['tl_id','batch']);
        });

        Schema::table('trans_pengajuan_tad_tl', function (Blueprint $table) {
            $table->dropColumn(['batch']);
        });
    }
}
