<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcCabangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('CABANG')) {
            Schema::create(
                'CABANG',
                function (Blueprint $table) {
                    $table->string('Sandi')->primary();
                    $table->string('Lengkap')->nullable();
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
        Schema::dropIfExists('CABANG');
    }
}
