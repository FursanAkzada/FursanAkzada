<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransPengajuanTadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trans_pengajuan_tad',
            function (Blueprint $table) {
                $table->id();
                $table->string('no_tiket')->nullable();
                $table->string('cabang_id')->nullable();
                $table->string('so_filename')->nullable()->comment('Struktur Organisasi');
                $table->text('so_filepath')->nullable()->comment('Struktur Organisasi');
                $table->string('surat_filename')->nullable();
                $table->text('surat_filepath')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('cabang_id')->references('Sandi')->on('ref_ehc_divisi');
            }
        );

        Schema::create(
            'trans_pengajuan_tad_requirement',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->unsignedBigInteger('jabatan_id')->nullable();
                $table->string('jenis_kelamin', '2')->nullable()->comment('L = Laki Laki ,P = Perempuan');
                $table->unsignedBigInteger('vendor_id')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_tad')->onDelete('cascade');
                $table->foreign('jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
                $table->foreign('vendor_id')->references('id')->on('ref_vendor');
            }
        );

        Schema::create(
            'trans_pengajuan_tad_kandidat',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('requirement_id')->nullable();
                $table->unsignedBigInteger('tad_id')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('tad_id')->references('id')->on('ref_tad');
                $table->foreign('requirement_id')->references('id')->on('trans_pengajuan_tad_requirement')->onDelete('cascade');
            }
        );

        Schema::create(
            'trans_pengajuan_tad_logs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pengajuan_id')->nullable();
                $table->string('keterangan')->nullable()->default('text');
                $table->string('status')->nullable()->default('text');
                $table->tinyInteger('is_active')->default(1);

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('pengajuan_id')->references('id')->on('trans_pengajuan_tad')->onDelete('cascade');
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
        Schema::dropIfExists('trans_pengajuan_tad_requirement');
        Schema::dropIfExists('trans_pengajuan_tad_kandidat');
        Schema::dropIfExists('trans_pengajuan_tad_logs');
        Schema::dropIfExists('trans_pengajuan_tad');
    }
}
