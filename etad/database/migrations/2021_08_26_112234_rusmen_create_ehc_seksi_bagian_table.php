<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcSeksiBagianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('SeksiBagian')) {
            Schema::create(
                'SeksiBagian',
                function (Blueprint $table) {
                    $table->string('CodeSB', 10)->nullable();
                    $table->string('New_Code', 10)->nullable();
                    $table->string('kd_seksi', 4)->nullable();
                    $table->string('nama_seksi', 50)->nullable();
                    $table->string('kd_bid', 4)->nullable();
                    $table->string('nama_bidang', 50)->nullable();
                    $table->string('kd_cab', 4)->nullable();
                    $table->string('Link_Up_kd_Cab', 9)->nullable();
                    $table->string('nama_cabang', 50)->nullable();
                    $table->string('kd_gab', 9)->nullable();
                    $table->string('nama_gab', 150)->nullable();
                    $table->string('Link_Up_kd_gab', 9)->nullable();
                    $table->string('new_kd_gab', 9)->nullable();
                    $table->string('checked', 1)->nullable();
                    $table->string('status', 1)->nullable();
                    $table->datetime('tglinput')->nullable();
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
        Schema::dropIfExists('SeksiBagian');
    }
}
