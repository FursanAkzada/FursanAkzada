<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPengajuanTadAddPembukaanPenutupan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trans_pengajuan_tad', function (Blueprint $table) {
            $table->longText('pembukaan')->nullable()->after('semester');
            $table->longText('penutupan')->nullable()->after('pembukaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('trans_pengajuan_tad', function (Blueprint $table) {
            $table->dropColumn(['pembukaan']);
            $table->dropColumn(['penutupan']);
        });
    }
}
