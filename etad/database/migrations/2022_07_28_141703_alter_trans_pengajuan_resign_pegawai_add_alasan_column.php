<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignPegawaiAddAlasanColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->string('alasan', 255)->after('tad_id');
            }
        );
    }
    public function down()
    {
        Schema::table(
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->dropColumn('alasan');
            }
        );
    }
}
