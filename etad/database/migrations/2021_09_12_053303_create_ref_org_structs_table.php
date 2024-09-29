<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefOrgStructsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_org_structs',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('level');
                $table->tinyInteger('type')->default(0)->comment('1:presdir, 2:direktur, 3:ia division');
                $table->string('email', 255)->nullable();
                $table->string('code', 20)->nullable();
                $table->string('phone', 25)->nullable();
                $table->string('fax', 25)->nullable();
                $table->string('website', 100)->nullable();
                $table->unsignedInteger('pic_id')->nullable();
                $table->string('address', 2048)->nullable();
                $table->unsignedInteger('province_id')->nullable();
                $table->unsignedInteger('city_id')->nullable();
                $table->tinyInteger('status')->default(1)->comment('0:nonactive, 1:active');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('ref_org_structs');
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
        Schema::dropIfExists('ref_org_structs');
    }
}
