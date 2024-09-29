<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefWawancaraPertanyaanChangePertanyaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'ref_wawancara_pertanyaan',
            function (Blueprint $table) {
                $table->text('pertanyaan')->change();
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
            'ref_wawancara_pertanyaan',
            function (Blueprint $table) {
                $table->string('pertanyaan', 255)->change();
            }
        );
    }
}
