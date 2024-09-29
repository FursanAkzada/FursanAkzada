<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKepegawaianIdColumnToPenilaianTadRewardPembinaan extends Migration
{
    public function up()
    {
        Schema::table(
            'trans_reward',
            function (Blueprint $table) {
                $table->unsignedBigInteger('kepegawaian_id')
                    ->after('tad_id')
                    ->nullable();
                $table->foreign('kepegawaian_id')
                    ->references('id')
                    ->on('ref_tad_kepegawaian');
            }
        );
        Schema::table(
            'trans_pembinaan',
            function (Blueprint $table) {
                $table->unsignedBigInteger('kepegawaian_id')
                    ->after('tad_id')
                    ->nullable();
                $table->foreign('kepegawaian_id')
                    ->references('id')
                    ->on('ref_tad_kepegawaian');
            }
        );
    }
    public function down()
    {
        Schema::table(
            'trans_pembinaan',
            function (Blueprint $table) {
                $table->dropForeign(['kepegawaian_id']);
                $table->dropColumn('kepegawaian_id');
            }
        );
        Schema::table(
            'trans_reward',
            function (Blueprint $table) {
                $table->dropForeign(['kepegawaian_id']);
                $table->dropColumn('kepegawaian_id');
            }
        );
    }
}
