<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanResignAddPerihalPembukaanIsiSurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trans_pengajuan_resign', function (Blueprint $table) {
            $table->longText('perihal')->nullable()->after('tgl_pengajuan');
            $table->longText('pembukaan')->nullable()->after('cabang_id');
            $table->longText('isi_surat')->nullable()->after('pembukaan');
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
        Schema::table('trans_pengajuan_resign', function (Blueprint $table) {
            $table->dropColumn(['perihal']);
            $table->dropColumn(['pembukaan']);
            $table->dropColumn(['isi_surat']);
        });
    }
}
