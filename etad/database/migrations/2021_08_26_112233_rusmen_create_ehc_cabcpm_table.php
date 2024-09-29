<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcCabcpmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('CABCPM')) {
            Schema::create(
                'CABCPM',
                function (Blueprint $table) {
                    $table->string('KDCAB', 3)->nullable();
                    $table->string('CABANG', 30)->nullable();
                    $table->string('KDCAPEM', 5)->nullable();
                    $table->string('CAPEM', 62)->nullable();
                    $table->string('STATCAPEM', 1)->nullable();
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
        Schema::dropIfExists('CABCPM');
    }
}
