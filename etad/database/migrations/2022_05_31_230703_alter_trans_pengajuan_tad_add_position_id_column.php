<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadAddPositionIdColumn extends Migration
{
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->unsignedBigInteger('position_id')->after('id')
                    ->nullable();

                $table->foreign('position_id')->references('id')->on('ref_positions');
            }
        );
    }

    public function down()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->dropForeign(['position_id']);
                $table->dropColumn('position_id');
            }
        );
    }
}
