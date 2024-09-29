<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEhcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('AGAMA',                 'ref_ehc_agama');
        Schema::rename('CABANG',                'ref_ehc_cabang');
        Schema::rename('CABCPM',                'ref_ehc_capem');
        Schema::rename('DIVISI',                'ref_ehc_divisi');
        Schema::rename('JABATAN',               'ref_ehc_jabatan');
        Schema::rename('Jenis_Reward',          'ref_ehc_jenis_reward');
        Schema::rename('M_LOGIN',               'sys_ehc_m_login');
        Schema::rename('PANGKAT',               'ref_ehc_pangkat');
        Schema::create(
            'ref_ehc_pjumlah_cuti_ijin',
            function (Blueprint $table) {
                $table->string('nip', '20')->nullable();
                $table->string('jumlah_tahunan', '8')->nullable();
                $table->string('jumlah_haji', '8')->nullable();
                $table->string('jumlah_melahirkan', '8')->nullable();
                $table->string('jml_pend', '8')->nullable();
                $table->string('jml_extra', '8')->nullable();
                $table->string('jml_ctbesar', '8')->nullable();
            }
        );
        Schema::rename('PYL_GRADESAL',          'ref_ehc_pyl_gradesal');
        Schema::create(
            'ref_ehc_pyl_no_rek',
            function (Blueprint $table) {
                $table->string('Nip', '20')->nullable();
                $table->string('Astek', '20')->nullable();
                $table->string('NoRek', '20')->nullable();
                $table->string('user_nm', '20')->nullable();
                $table->string('dateCrea', '32')->nullable();
                $table->string('dateSystem', '32')->nullable();
                $table->string('NPWP', '32')->nullable();
            }
        );
        Schema::rename('SEKOLAH',               'ref_ehc_sekolah');
        Schema::rename('SEKSI',                 'ref_ehc_seksi');
        Schema::rename('SeksiBagian',           'ref_ehc_seksi_bagian');
        Schema::rename('TBL_DAPEG_OUTSOURCING', 'ref_ehc_dapeg_outsourcing');
        Schema::rename('TBL_UNITKERJA',         'ref_ehc_unit_kerja');
        Schema::rename('V_DATAPEGAWAI',         'sys_ehc_v_datapegawai');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('ref_ehc_agama',             'AGAMA');
        Schema::rename('ref_ehc_cabang',            'CABANG');
        Schema::rename('ref_ehc_capem',             'CABCPM');
        Schema::rename('ref_ehc_divisi',            'DIVISI');
        Schema::rename('ref_ehc_jabatan',           'JABATAN');
        Schema::rename('ref_ehc_jenis_reward',      'Jenis_Reward');
        Schema::rename('sys_ehc_m_login',           'M_LOGIN');
        Schema::rename('ref_ehc_pangkat',           'PANGKAT');
        Schema::dropIfExists('ref_ehc_pjumlah_cuti_ijin');
        Schema::dropIfExists('ref_ehc_pyl_no_rek');
        Schema::rename('ref_ehc_pyl_gradesal',      'PYL_GRADESAL');
        Schema::rename('ref_ehc_sekolah',           'SEKOLAH');
        Schema::rename('ref_ehc_seksi',             'SEKSI');
        Schema::rename('ref_ehc_seksi_bagian',      'SeksiBagian');
        Schema::rename('ref_ehc_dapeg_outsourcing', 'TBL_DAPEG_OUTSOURCING');
        Schema::rename('ref_ehc_unit_kerja',        'TBL_UNITKERJA');
        Schema::rename('sys_ehc_v_datapegawai',     'V_DATAPEGAWAI');
    }
}
