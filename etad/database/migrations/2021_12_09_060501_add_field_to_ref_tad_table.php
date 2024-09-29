<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToRefTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->string('rekening_bjtm')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->string('ehc_no')->nullable();
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->string('rekening_bjtm')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->string('ehc_no')->nullable();
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
            $table->dropColumn(['rekening_bjtm','is_active','ehc_no']);
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->dropColumn(['rekening_bjtm','is_active','ehc_no']);
        });
    }
}
