<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadWawancaraAddTglPewawancaraColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad_wawancara',
            function (Blueprint $table) {
                $table->date('tgl')->after('kandidat_id')->nullable();
                $table->string('pewawancara', 64)->after('kandidat_id')->nullable();
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
            'trans_pengajuan_tad_wawancara',
            function (Blueprint $table) {
                $table->dropColumn('tgl');
                $table->dropColumn('pewawancara');
            }
        );
    }
}
