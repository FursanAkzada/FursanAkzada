<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoUrutOnRefPenilaianTad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_penilaian_tad', function (Blueprint $table) {
            $table->integer('urut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_penilaian_tad', function (Blueprint $table) {
            $table->dropColumn('urut');
        });
    }
}
