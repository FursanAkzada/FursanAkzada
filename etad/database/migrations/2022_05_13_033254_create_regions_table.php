<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing provinsi
        Schema::create(
            'ref_province',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('kode')->nullable()->unique();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();
            }
        );

        // Create table for storing kabupaten/kota
        Schema::create(
            'ref_city',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('province_id')->unsigned();
                $table->string('name');
                $table->string('kode')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('province_id')->references('id')->on('ref_province');
            }
        );

        // Create table for storing kabupaten/kota
        Schema::create(
            'ref_district',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('city_id')->unsigned();
                $table->string('name');
                $table->string('kode')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('city_id')->references('id')->on('ref_city');
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
        Schema::dropIfExists('ref_distric');
        Schema::dropIfExists('ref_city');
        Schema::dropIfExists('ref_province');
    }
}
