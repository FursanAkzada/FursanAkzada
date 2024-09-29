<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenEhcAgamaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'AGAMA',
            function (Blueprint $table) {
                $table->unsignedBigInteger('Sandi')->change();
            }
        );
        Schema::table(
            'SEKOLAH',
            function (Blueprint $table) {
                $table->unsignedBigInteger('sandi')->change();
            }
        );
        Schema::table(
            'TBL_UNITKERJA',
            function (Blueprint $table) {
                $table->unsignedBigInteger('idunit')->change();
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
    }
}
