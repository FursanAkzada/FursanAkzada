<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadQuotaPeriodeAddQuotaLama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad_quota_periode',
            function (Blueprint $table) {
                $table->unsignedBigInteger('quota_lama')->default(0);
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
            'trans_pengajuan_tad_quota_periode',
            function (Blueprint $table) {
                $table->dropColumn('quota_lama');
            }
        );

    }
}
