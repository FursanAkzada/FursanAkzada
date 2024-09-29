<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RusmenCreateEhcJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('JABATAN')) {
            Schema::create(
                'JABATAN',
                function (Blueprint $table) {
                    $table->string('sandi')->primary();
                    $table->string('Tampil')->nullable();
                    $table->string('SandiPangkat')->nullable();
                    $table->string('lengkap')->nullable();
                    $table->string('status')->nullable();
                    $table->string('rekrut')->nullable();
                    $table->string('usrCrea')->nullable();
                    $table->string('timeCrea')->nullable();
                    $table->string('statusRec')->nullable();
                    $table->string('SandiOld')->nullable();
                    $table->string('GU')->nullable();
                    $table->string('BSMR')->nullable();
                    $table->string('LEVEL_JAB')->nullable();
                    $table->string('JOBGRADE')->nullable();
                    $table->string('KATEGORI')->nullable();
                    $table->string('jabatanojk')->nullable();
                    $table->string('sandiojk')->nullable();
                    $table->string('ketojk')->nullable();
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
        Schema::dropIfExists('JABATAN');
    }
}
