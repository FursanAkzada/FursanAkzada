<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_positions',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_struct_id')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                // $table->unsignedBigInteger('kategori_id')->nullable();
                $table->string('type')->default('jabatan')->comment('presdir, direktur, div-head, depart-head, rc-head ao-head')->nullable();
                $table->string('name');
                $table->string('name_up')->nullable();
                $table->string('code', 20)->nullable();
                $table->tinyInteger('status')->default(1)->comment('0:nonactive, 1:active');
                $table->timestamps();
                $table->integer('created_by')->unsigned()->nullable();
                $table->integer('updated_by')->unsigned()->nullable();

                $table->foreign('org_struct_id')->references('id')->on('ref_org_structs');
                $table->foreign('parent_id')->references('id')->on('ref_positions');
                // $table->foreign('kategori_id')->references('id')->on('ref_category_positions');
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
        Schema::dropIfExists('ref_positions');
    }
}
