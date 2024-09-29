<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefTadChangePersonil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ref_tad',
            function (Blueprint $table) {
                $table->unsignedInteger('city_id')
                    ->after('alamat_lengkap')
                    ->nullable();
                $table->foreign('city_id')
                    ->references('id')
                    ->on('ref_city');
            }
        );
        Schema::table(
            'ref_tad_failed',
            function (Blueprint $table) {
                $table->unsignedInteger('city_id')
                    ->after('alamat_lengkap')
                    ->nullable();
                $table->foreign('city_id')
                    ->references('id')
                    ->on('ref_city');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'ref_tad',
            function (Blueprint $table) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }
        );
        Schema::table(
            'ref_tad_failed',
            function (Blueprint $table) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }
        );
    }
}
