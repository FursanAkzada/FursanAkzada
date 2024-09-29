<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiPegawaiAddSyncColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_mutasi_pegawai',
            function (Blueprint $table) {
                $table->string('synced', '2')->default(0)->index();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'trans_pengajuan_mutasi_pegawai',
            function (Blueprint $table) {
                $table->dropColumn('synced');
            }
        );
    }
}
