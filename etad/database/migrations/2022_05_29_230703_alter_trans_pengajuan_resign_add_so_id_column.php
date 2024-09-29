<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanResignAddSoIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->string('cabang_id', 255)->nullable()->change();
                $table->unsignedBigInteger('so_id')->after('status')
                    ->nullable();

                $table->foreign('so_id')->references('id')->on('ref_org_structs');
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
            'trans_pengajuan_resign',
            function (Blueprint $table) {
                $table->string('cabang_id', 255)->nullable(false)->change();
                $table->dropForeign(['so_id']);
                $table->dropColumn('so_id');
            }
        );
    }
}
