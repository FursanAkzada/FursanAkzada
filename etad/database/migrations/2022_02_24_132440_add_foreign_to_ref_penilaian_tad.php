<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToRefPenilaianTad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_penilaian_tad', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->change();
            $table->foreign('parent_id')->references('id')->on('ref_penilaian_tad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_penilaian_tad', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
    }
}
