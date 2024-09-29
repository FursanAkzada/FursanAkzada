<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('M_LOGIN')) {
            Schema::create(
                'M_LOGIN',
                function (Blueprint $table) {
                    $table->string('KD_LOG', 255);
                    $table->string('USER_LOG', 15)->nullable();
                    $table->string('PASS_LOG', 255);
                    $table->float('STA_LOG')->nullable();
                    $table->string('AKTIF_LOG', 255)->nullable();
                    $table->string('ID_SESSION', 255)->nullable();
                    $table->float('STT_LOG')->nullable();
                    $table->string('DATE_LOG', 255)->nullable();
                    $table->string('IP_ADD', 255)->nullable();
                    $table->string('MAC_ADD', 255)->nullable();
                    $table->string('USER_IN', 255)->nullable();
                    $table->string('NM_USER', 255)->nullable();
                    $table->string('DIVISI', 255)->nullable();
                    $table->string('ST_KANTOR', 255)->nullable();
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
    }
}
