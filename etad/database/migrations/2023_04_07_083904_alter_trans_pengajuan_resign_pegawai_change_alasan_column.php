<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignPegawaiChangeAlasanColumn extends Migration
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
                $table->bigInteger('alasan')->change();
            }
        );
    }
    public function down()
    {
        Schema::table(
            'trans_pengajuan_resign_pegawai',
            function (Blueprint $table) {
                $table->string('alasan', 255)->change();
            }
        );
    }
}
