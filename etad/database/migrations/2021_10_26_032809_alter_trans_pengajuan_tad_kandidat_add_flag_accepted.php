<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransPengajuanTadKandidatAddFlagAccepted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_tad_kandidat', function (Blueprint $table) {
            $table->tinyInteger('accepted')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->bigInteger('accepted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_pengajuan_tad_kandidat', function (Blueprint $table) {
            $table->dropColumn('accepted');
            $table->dropColumn('accepted_at');
            $table->dropColumn('accepted_by');
        });
    }
}
