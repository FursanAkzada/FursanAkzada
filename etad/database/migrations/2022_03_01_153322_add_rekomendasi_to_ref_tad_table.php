<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRekomendasiToRefTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->string('rekomendasi')->nullable();
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->string('rekomendasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->dropColumn('rekomendasi');
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->dropColumn('rekomendasi');
        });
    }
}
