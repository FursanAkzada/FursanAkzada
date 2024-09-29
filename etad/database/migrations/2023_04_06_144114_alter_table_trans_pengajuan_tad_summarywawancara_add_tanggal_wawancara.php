<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanTadSummarywawancaraAddTanggalWawancara extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trans_pengajuan_tad_summarywawancara', function (Blueprint $table) {
            $table->date('tgl_wawancara')->nullable()->after('keterangan');
        });

        Schema::table('trans_pengajuan_tad_pewawancara', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['wawancara_id']);
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
        Schema::table('trans_pengajuan_tad_summarywawancara', function (Blueprint $table) {
            $table->dropColumn(['tgl_wawancara']);
        });

        Schema::table('trans_pengajuan_tad_pewawancara', function (Blueprint $table) {
            $table->foreign('wawancara_id')->references('id')->on('trans_pengajuan_tad_wawancara');
            $table->foreign('user_id')->references('id')->on('sys_users');
        });
    }
}
