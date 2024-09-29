<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanTadPenerimaanAddStartDateContract extends Migration
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
            $table->date('start_date_contract')->nullable()->after('tgl_contractdue');
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
            $table->dropColumn(['start_date_contract']);

        });
    }
}
