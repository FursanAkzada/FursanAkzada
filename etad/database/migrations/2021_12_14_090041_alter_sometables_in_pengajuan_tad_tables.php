<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSometablesInPengajuanTadTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_tad_requirement', function (Blueprint $table) {
            $table->string('flag')->nullable()->default('open');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_pengajuan_tad_requirement', function (Blueprint $table) {
            $table->dropColumn(['flag']);
        });
    }
}
