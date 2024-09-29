<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditMasaKontrakOnTransPenilaianTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_penilaian_tad', function (Blueprint $table) {
            $table->integer('masa_kontrak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_penilaian_tad', function (Blueprint $table) {
            $table->integer('masa_kontrak')->nullable(false)->change();
        });
    }
}
