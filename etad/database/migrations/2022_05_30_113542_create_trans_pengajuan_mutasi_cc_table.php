<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransPengajuanMutasiCcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_mutasi_cc',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('sys_users');
                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_pengajuan_tad')
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
        Schema::dropIfExists('trans_pengajuan_mutasi_cc');
    }
}
