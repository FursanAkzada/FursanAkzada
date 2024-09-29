<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanTadPenerimaanAddTglContractdue extends Migration
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
            $table->date('tgl_contractdue')->nullable()->after('tgl_keputusan');
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
            $table->dropColumn(['tgl_contractdue']);

        });
    }
}
