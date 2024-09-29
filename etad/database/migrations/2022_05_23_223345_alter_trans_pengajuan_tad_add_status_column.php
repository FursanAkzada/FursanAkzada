<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanTadAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->string('status', 32)->after('id')->default(1)->nullable();
            }
        );
    }
    public function down()
    {
        Schema::table(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->dropColumn('status');
            }
        );
    }
}
