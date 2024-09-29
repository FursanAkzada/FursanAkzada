<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglPengajuanTransTadPengajuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_pengajuan_tad', function (Blueprint $table) {
            $table->date('tgl_pengajuan')->nullable()->after('no_tiket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_pengajuan_tad', function (Blueprint $table) {
            $table->dropColumn('tgl_pengajuan');
        });
    }
}
