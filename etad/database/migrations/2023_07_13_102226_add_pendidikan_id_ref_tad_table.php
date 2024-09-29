<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendidikanIdRefTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_tad', function (Blueprint $table) {
            $table->unsignedInteger('jurusan_id')->nullable()->after('pendidikan_id');
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->unsignedInteger('jurusan_id')->nullable()->after('pendidikan_id');
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
            $table->dropColumn('jurusan_id');
        });
        Schema::table('ref_tad_failed', function (Blueprint $table) {
            $table->dropColumn('jurusan_id');
        });
    }
}
