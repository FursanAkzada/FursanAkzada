<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanResignCcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_resign_cc',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('read_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('ref_positions');
                $table->foreign('pengajuan_id')
                    ->references('id')
                    ->on('trans_pengajuan_resign')
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
        Schema::dropIfExists('trans_pengajuan_resign_cc');
    }
}
