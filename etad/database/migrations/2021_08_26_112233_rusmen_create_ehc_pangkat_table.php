<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcPangkatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('PANGKAT')) {
            Schema::create(
                'PANGKAT',
                function (Blueprint $table) {
                    $table->string('sandi')->nullable();
                    $table->string('lengkap')->nullable();
                    $table->string('usrCrea')->nullable();
                    $table->datetime('usrTime')->nullable();
                    $table->string('statusRec', 1)->nullable();
                    $table->string('Sandi2', 3)->nullable();
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
        Schema::dropIfExists('PANGKAT');
    }
}
