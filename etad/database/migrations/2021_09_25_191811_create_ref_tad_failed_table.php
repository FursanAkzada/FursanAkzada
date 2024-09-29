<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefTadFailedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'ref_tad_failed',
            function (Blueprint $table) {
                $table->id();
                $table->string('NO')->nullable();
                $table->unsignedBigInteger('kepegawaian_id')->nullable();
                $table->nullableMorphs('lock');
                $table->string('source')->comment('1: app, 2: import')->default('1');
                $table->string('nama')->nullable();
                $table->string('email')->nullable();
                $table->string('telepon')->nullable();
                $table->string('nik')->nullable();
                $table->string('npwp')->nullable();
                $table->string('bpjs')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->string('status_perkawinan')->nullable();
                $table->unsignedBigInteger('agama_id')->nullable();
                $table->string('tempat_lahir')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->text('alamat_lengkap')->nullable();
                $table->unsignedBigInteger('provinsi_id')->nullable();

                $table->unsignedBigInteger('pendidikan_id')->nullable();
                $table->string('gelar')->nullable();

                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                // $table->foreign('agama_id')->references('Sandi')->on('AGAMA');
                $table->foreign('provinsi_id')->references('id')->on('sys_provinsi');
                // $table->foreign('pendidikan_id')->references('sandi')->on('SEKOLAH');
            }
        );

        Schema::create(
            'ref_tad_kepegawaian_failed',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('status', 3)
                    ->comment('1: work, 2: resign, 3: end, 4: mutation, 5: penghargaan, 6: pembinaan, 7: penilaian')
                    ->index();
                $table->unsignedBigInteger('previous_employment_id')->nullable();
                $table->string('is_imported', 1)->default(0)->index();
                $table->string('year', 4)->index();
                $table->string('semester', 8)->index();
                $table->unsignedBigInteger('kandidat_id')->nullable();
                $table->unsignedBigInteger('tad_id');
                $table->unsignedBigInteger('cabang_id')->nullable();
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->unsignedBigInteger('jabatan_id')->nullable();
                $table->string('nio')->nullable();
                $table->string('no_sk')->nullable();
                $table->unsignedBigInteger('pengajuan_mutasi_pegawai_id')->nullable();
                $table->unsignedBigInteger('pengajuan_resign_pegawai_id')->nullable();
                $table->unsignedBigInteger('penghargaan_id')->nullable();
                $table->unsignedBigInteger('pembinaan_id')->nullable();
                $table->unsignedBigInteger('penilaian_id')->nullable();
                $table->date('in_at')->nullable();
                $table->date('mutation_at')->nullable();
                $table->date('resign_at')->nullable();
                $table->date('out_at')->nullable();
                $table->date('contract_due')->nullable();
                $table->string('jenis_jabatan')->nullable();
                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('tad_id')->references('id')->on('ref_tad_failed');
                // $table->foreign('cabang_id')->references('id')->on('ref_org_structs');
                $table->foreign('vendor_id')->references('id')->on('ref_vendor');
                // $table->foreign('jabatan_id')->references('idunit')->on('ref_ehc_unit_kerja');
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
        Schema::dropIfExists('ref_tad_kepegawaian_failed');
        Schema::dropIfExists('ref_tad_failed');
    }
}
