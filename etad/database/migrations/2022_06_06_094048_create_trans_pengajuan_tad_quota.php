<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadQuota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_tad_quota');
        Schema::create(
            'trans_pengajuan_tad_quota_periode',
            function (Blueprint $table) {
                $table->id();
                $table->smallInteger('version')->default(0);
                $table->string('status', 25)->default('draft')->nullable();
                $table->year('year');
                $table->string('semester', 16);
                $table->string('level', 16);
                $table->longText('upgrade_reason')->nullable();
                $table->unsignedBigInteger('quota')->default(0);
                $table->unsignedBigInteger('fulfillment')->default(0);
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->unique(['year', 'semester', 'level']);
            }
        );
        Schema::create(
            'trans_pengajuan_tad_quota',
            function (Blueprint $table) {
                $table->id();
                $table->string('status', 16)->default(0)->nullable();
                $table->unsignedBigInteger('pengajuan_tad_quota_periode_id');
                $table->unsignedBigInteger('org_struct_id')->nullable();
                $table->unsignedBigInteger('posisi_tad_id')->nullable();
                $table->unsignedInteger('quota');
                $table->unsignedBigInteger('fulfillment')->default(0);
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->foreign('pengajuan_tad_quota_periode_id')
                    ->references('id')
                    ->on('trans_pengajuan_tad_quota_periode');
                $table->foreign('org_struct_id')
                    ->references('id')
                    ->on('ref_org_structs');
                $table->foreign('posisi_tad_id')
                    ->references('idunit')
                    ->on('ref_ehc_unit_kerja');
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
        Schema::create(
            'ref_tad_quota',
            function (Blueprint $table) {
                $table->id();
                $table->year('year');
                $table->string('semester', 16);
                $table->string('level', 16)->comment('bod, division, cabang, capem')->nullable();
                $table->unsignedBigInteger('posisi_tad_id')->nullable();
                $table->unsignedInteger('quota');
                $table->string('description', 1024)->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                $table->foreign('posisi_tad_id')
                    ->references('idunit')
                    ->on('ref_ehc_unit_kerja');
            }
        );
        Schema::dropIfExists('trans_pengajuan_tad_quota');
        Schema::dropIfExists('trans_pengajuan_tad_quota_periode');
    }
}
