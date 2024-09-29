<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPembinaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_pembinaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tad_id')->nullable();
            $table->string('sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->text('eviden')->nullable();
            $table->bigInteger('jenis_id')->nullable();

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->foreign('tad_id')->references('id')->on('ref_tad');
            // $table->foreign('jenis_id')->references('sandi')->on('Jenis_Reward');
        });
        Schema::create(
            'trans_pembinaan_cc',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembinaan_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('sys_users');
                $table->foreign('pembinaan_id')
                    ->references('id')
                    ->on('trans_pembinaan')
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
        Schema::dropIfExists('trans_pembinaan_cc');
        Schema::dropIfExists('trans_pembinaan');
    }
}
