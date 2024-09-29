<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVDatapegawaiIfNotExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('V_DATAPEGAWAI')) {
            Schema::create(
                'V_DATAPEGAWAI',
                function (Blueprint $table) {
                    $table->id();
                    $table->text('nip', 25)->nullable();
                    $table->text('nama', 60)->nullable();
                    $table->text('sex', 2)->nullable();
                    $table->text('alamat', 255)->nullable();
                    $table->text('kores', 255)->nullable();
                    $table->text('tglair', 255)->nullable();
                    $table->text('kolair', 255)->nullable();
                    $table->text('stkawin', 255)->nullable();
                    $table->text('jmanak', 255)->nullable();
                    $table->text('gelar', 255)->nullable();
                    $table->text('bstudy', 255)->nullable();
                    $table->text('tgmasuk', 255)->nullable();
                    $table->text('tgankat', 255)->nullable();
                    $table->text('stpeg', 255)->nullable();
                    $table->text('gol', 255)->nullable();
                    $table->text('tgpank', 255)->nullable();
                    $table->text('tgjabat', 255)->nullable();
                    $table->text('IDBSMR', 255)->nullable();
                    $table->text('tgmut', 255)->nullable();
                    $table->text('gapok', 255)->nullable();
                    $table->text('tggaji', 255)->nullable();
                    $table->text('reward', 255)->nullable();
                    $table->text('tgrwd', 255)->nullable();
                    $table->text('judicium', 255)->nullable();
                    $table->text('tglpk', 255)->nullable();
                    $table->text('potensi', 255)->nullable();
                    $table->text('noastek', 255)->nullable();
                    $table->text('skors', 255)->nullable();
                    $table->text('tgskor', 255)->nullable();
                    $table->text('tgret', 255)->nullable();
                    $table->text('stret', 255)->nullable();
                    $table->text('nopen', 255)->nullable();
                    $table->text('AGAMA', 255)->nullable();
                    $table->text('BIDANG', 255)->nullable();
                    $table->text('CABANG', 255)->nullable();
                    $table->text('JABATAN', 255)->nullable();
                    $table->text('PANGKAT', 255)->nullable();
                    $table->text('PENDIDIKAN_AKUI', 255)->nullable();
                    $table->text('PENDIDIKAN_SAATINI', 255)->nullable();
                    $table->text('LAMA', 255)->nullable();
                    $table->text('SEKSI', 255)->nullable();
                    $table->text('bir', 255)->nullable();
                    $table->text('cab', 255)->nullable();
                    $table->text('sek', 255)->nullable();
                    $table->text('jab', 255)->nullable();
                    $table->text('lulusan2', 255)->nullable();
                    $table->text('status', 255)->nullable();
                    $table->text('STAT_AKTIF', 255)->nullable();
                    $table->text('nipreg', 255)->nullable();
                    $table->text('pkt', 255)->nullable();
                    $table->text('seksiBagian', 255)->nullable();
                    $table->text('Tampil', 255)->nullable();
                    $table->text('sandi', 255)->nullable();
                    $table->text('No_Pensiun', 255)->nullable();
                    $table->text('noRek', 255)->nullable();
                    $table->text('stkawin2', 255)->nullable();
                    $table->text('jmanak2', 255)->nullable();
                    $table->text('stKantor', 255)->nullable();
                    $table->text('IBU_KANDUNG', 255)->nullable();
                    $table->text('PASSWORD', 255)->nullable();
                    $table->text('capem', 255)->nullable();
                    $table->text('PG', 255)->nullable();
                    $table->text('JG', 255)->nullable();
                    $table->text('GRADE', 255)->nullable();
                    $table->text('NPWP', 255)->nullable();
                    $table->text('CPM', 255)->nullable();
                    $table->text('noktp', 255)->nullable();
                    $table->text('sandiojk', 255)->nullable();
                    $table->text('JGBARU', 255)->nullable();
                    $table->timestamps();
                }
            );
        }
        if (!Schema::hasTable('datapegawai')) {
            Schema::create(
                'datapegawai',
                function (Blueprint $table) {
                    $table->id();
                    $table->text('nip')->nullable();
                    $table->text('nama')->nullable();
                    $table->text('sex')->nullable();
                    $table->text('alamat')->nullable();
                    $table->text('kores')->nullable();
                    $table->text('tglair')->nullable();
                    $table->text('kolair')->nullable();
                    $table->text('agama')->nullable();
                    $table->text('stkawin')->nullable();
                    $table->text('jmanak')->nullable();
                    $table->text('lulusan')->nullable();
                    $table->text('lulusan2')->nullable();
                    $table->text('gelar')->nullable();
                    $table->text('bstudy')->nullable();
                    $table->text('tgmasuk')->nullable();
                    $table->text('tgankat')->nullable();
                    $table->text('stpeg')->nullable();
                    $table->text('gol')->nullable();
                    $table->text('pangkat')->nullable();
                    $table->text('tgpank')->nullable();
                    $table->text('jabatan')->nullable();
                    $table->text('jabatanlama')->nullable();
                    $table->text('tgjabat')->nullable();
                    $table->text('biro')->nullable();
                    $table->text('cabang')->nullable();
                    $table->text('capem')->nullable();
                    $table->text('seksi')->nullable();
                    $table->text('tgmut')->nullable();
                    $table->text('gapok')->nullable();
                    $table->text('tggaji')->nullable();
                    $table->text('reward')->nullable();
                    $table->text('tgrwd')->nullable();
                    $table->text('judicium')->nullable();
                    $table->text('tglpk')->nullable();
                    $table->text('potensi')->nullable();
                    $table->text('noastek')->nullable();
                    $table->text('skors')->nullable();
                    $table->text('tgskor')->nullable();
                    $table->text('tgret')->nullable();
                    $table->text('stret')->nullable();
                    $table->text('nopen')->nullable();
                    $table->text('STAT_AKTIF')->nullable();
                    $table->text('Pensiun')->nullable();
                    $table->text('stRec')->nullable();
                    $table->text('No_Pensiun')->nullable();
                    $table->text('nipreg')->nullable();
                    $table->text('user_name')->nullable();
                    $table->text('overide')->nullable();
                    $table->text('overideDate')->nullable();
                    $table->text('usrTime')->nullable();
                    $table->text('seksiBagian')->nullable();
                    $table->text('noRek')->nullable();
                    $table->text('stkawin2')->nullable();
                    $table->text('jmanak2')->nullable();
                    $table->text('stKantor')->nullable();
                    $table->text('IDBSMR')->nullable();
                    $table->text('IBU_KANDUNG')->nullable();
                    $table->text('PASSWORD')->nullable();
                    $table->text('pegHP')->nullable();
                    $table->text('pegTelp1')->nullable();
                    $table->text('pegTelp2')->nullable();
                    $table->text('ID_SO')->nullable();
                    $table->text('noktp')->nullable();
                    $table->text('email')->nullable();
                    $table->text('nama_panggilan')->nullable();
                    $table->text('user_estim')->nullable();
                    $table->text('display_estim')->nullable();
                    $table->timestamps();
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
        Schema::dropIfExists('V_DATAPEGAWAI');
    }
}
