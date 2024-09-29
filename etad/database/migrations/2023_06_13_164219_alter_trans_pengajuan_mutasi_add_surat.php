<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransPengajuanMutasiAddSurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'trans_pengajuan_mutasi',
            function (Blueprint $table) {
                $table->string('no', 64)->nullable();
                $table->string('perihal', 2048)->nullable();
                $table->text('menunjuk')->nullable();
                $table->text('menindaklanjuti')->nullable();
            }
        );
        Schema::create(
            'trans_pengajuan_mutasi_to',
            function (Blueprint $table) {
                $table->unsignedBigInteger('trans_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();

                // $table->foreign('user_id')->references('id')->on('sys_users');
                $table->foreign('trans_id')
                    ->references('id')
                    ->on('trans_pengajuan_mutasi')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('trans_pengajuan_mutasi_to');
        Schema::table(
            'trans_pengajuan_mutasi',
            function (Blueprint $table) {
                $table->dropColumn('no');
                $table->dropColumn('perihal');
                $table->dropColumn('menunjuk');
                $table->dropColumn('menindaklanjuti');
            }
        );
    }
}
