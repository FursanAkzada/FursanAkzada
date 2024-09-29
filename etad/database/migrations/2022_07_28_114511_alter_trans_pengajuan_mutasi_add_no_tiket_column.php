<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiAddNoTiketColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('trans_pengajuan_mutasi', 'no_tiket')) {
            Schema::table(
                'trans_pengajuan_mutasi',
                function (Blueprint $table) {
                    $table->string('no_tiket', 32)
                        ->after('status')
                        ->default(1)
                        ->nullable();
                }
            );
        }
    }
    public function down()
    {
        if (Schema::hasColumn('trans_pengajuan_mutasi', 'no_tiket')) {
            Schema::table(
                'trans_pengajuan_mutasi',
                function (Blueprint $table) {
                    $table->dropColumn('no_tiket');
                }
            );
        }
    }
}
