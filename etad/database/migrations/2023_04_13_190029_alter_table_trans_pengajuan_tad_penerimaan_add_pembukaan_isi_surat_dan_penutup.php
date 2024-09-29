<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanTadPenerimaanAddPembukaanIsiSuratDanPenutup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trans_pengajuan_tad_penerimaan', function (Blueprint $table) {
            $table->longText('pembukaan')->nullable()->after('tgl_contractdue');
            $table->longText('isi_surat')->nullable()->after('pembukaan');
            $table->longText('penutup')->nullable()->after('isi_surat');
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
        Schema::table('trans_pengajuan_tad_penerimaan', function (Blueprint $table) {
            $table->dropColumn(['pembukaan']);
            $table->dropColumn(['isi_surat']);
            $table->dropColumn(['penutup']);
        });
    }
}
