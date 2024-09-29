<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcPylGradesalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('PYL_GRADESAL')) {
            Schema::create(
                'PYL_GRADESAL',
                function (Blueprint $table) {
                    $table->string('NIP', 15)->nullable();
                    $table->string('PGRADE', 32)->nullable();
                    $table->string('JGRADE', 32)->nullable();
                    $table->string('GRADE', 10)->nullable();
                    $table->string('JGBARU', 10)->nullable();
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
        Schema::dropIfExists('PYL_GRADESAL');
    }
}
