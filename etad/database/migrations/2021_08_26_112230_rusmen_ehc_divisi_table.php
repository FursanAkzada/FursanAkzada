<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenEhcDivisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('DIVISI')) {
            Schema::create(
                'DIVISI',
                function (Blueprint $table) {
                    $table->string('Sandi', 255);
                    $table->string('Lengkap', 255)->nullable();
                    $table->float('kd_cab')->nullable();
                    $table->string('STAT_KANTOR', 255)->nullable();
                    $table->string('kelas', 255)->nullable();
                    $table->string('limit', 255)->nullable();
                    $table->string('kd_ojk', 12)->nullable();
                }
            );
        } elseif (!Schema::hasColumns('DIVISI', ['Sandi'])) {
            Schema::table(
                'DIVISI',
                function (Blueprint $table) {
                    $table->string('Sandi', 255);
                }
            );
        }

        if (!Schema::hasTable('TBL_UNITKERJA')) {
            Schema::create(
                'TBL_UNITKERJA',
                function (Blueprint $table) {
                    $table->bigInteger('idunit');
                    $table->string('NM_UNIT', 50)->nullable();
                    $table->string('jenis', 8);
                }
            );
        } elseif (!Schema::hasColumns('TBL_UNITKERJA', ['idunit'])) {
            Schema::table(
                'TBL_UNITKERJA',
                function (Blueprint $table) {
                    $table->id('idunit');
                }
            );
        }

        if (!Schema::hasTable('AGAMA')) {
            Schema::create(
                'AGAMA',
                function (Blueprint $table) {
                    $table->unsignedBigInteger('Sandi');
                    $table->string('Lengkap', 30)->nullable();
                    $table->string('usrCrea', 10)->nullable();
                    $table->dateTime('dateCrea')->nullable();
                    $table->string('StatusRec', 1)->nullable();
                }
            );
        } elseif (!Schema::hasColumns('AGAMA', ['Sandi'])) {
            Schema::table(
                'AGAMA',
                function (Blueprint $table) {
                    $table->unsignedBigInteger('Sandi');
                }
            );
        }
        if (!Schema::hasTable('SEKOLAH')) {
            Schema::create(
                'SEKOLAH',
                function (Blueprint $table) {
                    $table->bigInteger('sandi');
                    $table->string('lengkap', 30)->nullable();
                    $table->string('usrCrea', 10)->nullable();
                    $table->dateTime('dateCrea')->nullable();
                    $table->string('StatusRec', 1)->nullable();
                }
            );
        } elseif (!Schema::hasColumns('SEKOLAH', ['sandi'])) {
            Schema::table(
                'SEKOLAH',
                function (Blueprint $table) {
                    $table->bigInteger('sandi');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'DIVISI',
            function (Blueprint $table) {
                $table->dropColumn('Sandi');
            }
        );
        Schema::table(
            'TBL_UNITKERJA',
            function (Blueprint $table) {
                $table->dropColumn('idunit');
            }
        );
        Schema::table(
            'AGAMA',
            function (Blueprint $table) {
                $table->dropColumn('Sandi');
            }
        );
        Schema::table(
            'SEKOLAH',
            function (Blueprint $table) {
                $table->dropColumn('sandi');
            }
        );
    }
}
