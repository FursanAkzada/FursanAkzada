<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcSeksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('SEKSI')) {
            Schema::create(
                'SEKSI',
                function (Blueprint $table) {
                    $table->string('SANDI', 4)->nullable();
                    $table->string('LENGKAP', 40)->nullable();
                    $table->string('usrCrea', 10)->nullable();
                    $table->datetime('usrTime')->nullable();
                    $table->string('statusRec', 1)->nullable();
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
        Schema::dropIfExists('SEKSI');
    }
}
