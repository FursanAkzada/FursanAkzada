<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefProvinceAddcodeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ref_province', 'code')) {
            Schema::table(
                'ref_province',
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
        if (Schema::hasColumn('ref_province', 'code')) {
            Schema::table(
                'ref_province',
                function (Blueprint $table) {
                    $table->dropColumn('code');
                }
            );
        }
    }
}
