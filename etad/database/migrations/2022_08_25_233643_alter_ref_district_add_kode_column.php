<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefDistrictAddKodeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ref_distric')) {
            Schema::rename('ref_distric', 'ref_district');
        }
        if (!Schema::hasColumn('ref_district', 'code')) {
            Schema::table(
                'ref_district',
                function (Blueprint $table) {
                    $table->string('code', 8)->nullable()->unique();
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
        if (Schema::hasColumn('ref_district', 'code')) {
            Schema::table(
                'ref_district',
                function (Blueprint $table) {
                    $table->dropColumn('code');
                }
            );
        }
    }
}
