<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('SEKOLAH', 'usrTime')) {
            Schema::table(
                'SEKOLAH',
                function (Blueprint $table) {
                    $table->datetime('usrTime')->nullable();
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
        if (Schema::hasColumn('SEKOLAH', 'usrTime')) {
            Schema::table(
                'SEKOLAH',
                function (Blueprint $table) {
                    $table->dropColumn('usrTime');
                }
            );
        }
    }
}
