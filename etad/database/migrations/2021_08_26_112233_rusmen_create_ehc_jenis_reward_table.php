<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcJenisRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('Jenis_Reward')) {
            Schema::create(
                'Jenis_Reward',
                function (Blueprint $table) {
                    $table->string('sandi')->nullable();
                    $table->string('Lengkap')->nullable();
                    $table->string('description', 2048)->nullable();
                    $table->string('KN')->nullable();
                    $table->string('Lumsum')->nullable();
                    $table->string('usrCrea')->nullable();
                    $table->datetime('usrTime')->nullable();
                    $table->string('statRec', 1)->nullable();
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
        Schema::dropIfExists('Jenis_Reward');
    }
}
