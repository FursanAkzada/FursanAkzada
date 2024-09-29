<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransPerpanjanganTadAddPembukaDanPenutup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table(
            'trans_perpanjangan_tad',
            function (Blueprint $table) {
                $table->longText('pembukaan')->nullable()->after('tgl_akhir_kontrak_baru');
                $table->longText('penutup')->nullable()->after('pembukaan');
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
        //
        Schema::table('trans_perpanjangan_tad', function (Blueprint $table) {
            $table->dropColumn(['pembukaan']);
            $table->dropColumn(['penutup']);
        });
    }
}
