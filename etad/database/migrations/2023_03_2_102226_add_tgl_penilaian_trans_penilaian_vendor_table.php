<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTglPenilaianTransPenilaianVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->date('tgl_penilaian')->nullable()->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_penilaian_vendor', function (Blueprint $table) {
            $table->dropColumn('tgl_penilaian');
        });
    }
}
