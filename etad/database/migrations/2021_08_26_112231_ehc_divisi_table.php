<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EhcDivisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('DIVISI', function (Blueprint $table) {
            $table->string('Sandi')->primary()->nullable(false)->change();
        });
        Schema::table('TBL_UNITKERJA', function (Blueprint $table) {
            $table->bigInteger('idunit')->primary()->change();
        });
        Schema::table('AGAMA', function (Blueprint $table) {
            $table->bigInteger('Sandi')->primary()->change();
        });
        Schema::table('SEKOLAH', function (Blueprint $table) {
            $table->bigInteger('sandi')->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DIVISI', function (Blueprint $table) {
            $table->dropPrimary(['Sandi']);
            $table->string('Sandi')->nullable()->change();
        });
        Schema::table('TBL_UNITKERJA', function (Blueprint $table) {
            $table->dropPrimary(['idunit']);
            $table->integer('idunit')->change();
        });
        Schema::table('AGAMA', function (Blueprint $table) {
            $table->dropPrimary(['Sandi']);
            $table->float('Sandi')->change();
        });
        Schema::table('SEKOLAH', function (Blueprint $table) {
            $table->dropPrimary(['sandi']);
            $table->string('sandi',50)->change();
        });
    }
}
