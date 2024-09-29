<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EhcNoChangeToFloatRefTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->float('ehc_no')->nullable()->change();
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->float('ehc_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->string('ehc_no')->nullable()->change();
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->string('ehc_no')->nullable()->change();
        });
    }
}
