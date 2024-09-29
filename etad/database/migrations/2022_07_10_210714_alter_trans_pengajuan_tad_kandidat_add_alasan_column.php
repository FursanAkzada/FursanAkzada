<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadKandidatAddAlasanColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad_kandidat',
            function (Blueprint $table) {
                $table->string('alasan', 1024)->nullable();
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
            'trans_pengajuan_tad_kandidat',
            function (Blueprint $table) {
                $table->dropColumn('alasan');
            }
        );
    }
}
