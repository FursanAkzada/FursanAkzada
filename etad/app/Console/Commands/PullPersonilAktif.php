<?php

namespace App\Console\Commands;

use App\Entities\EHC\Jabatan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\KepegawaianFailed;
use Modules\Master\Entities\Tad\KepegawaianMigrasi;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Tad\TadFailed;
use Modules\Master\Entities\Tad\TadMigrasi;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class PullPersonilAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:personil-aktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ambil personil aktif dari EHC ke e-TAD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Synchronizing personil aktif dari EHC ke e-TAD');
        if (env('DB_SYNC', false)) {
            $payload = [
                'ReqDate'   => now()->format('Y-m-d'),
            ];
            $payload['Signature'] = base64_encode(hash_hmac(
                'sha256',
                $payload['ReqDate'],
                'jatim',
                true
            ));
            $response = Http::withHeaders(
                [
                    'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                ]
            )
                ->withoutVerifying()
                ->post(
                    'https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/GetAktifTAD',
                    $payload
                );
            $response_json = $response->json();
            // DB::beginTransaction();
            try {
                foreach (($response_json['ListActive'] ?? []) as $key => $TAD) {
                    if (!isset($TAD['NO'])) {
                        continue;
                    }
                    $tad = Tad::with('kepegawaian')->where('NO', $TAD['NO'])->first();
                    $failed = false;
                    if ($tad) {
                        // dd(82, $tad, ($TAD));
                        $tad = new TadFailed;
                        $failed = true;
                    } else {
                        $kepegawaian = Kepegawaian::where('nio', $TAD['NIO'])->first();
                        if ($kepegawaian) {
                            // dd(88, json_decode($kepegawaian), json_decode($TAD));
                            $tad = new TadFailed;
                            $failed = true;
                        } else {
                            $tad = new Tad;
                        }
                    }
                    $posisi_tad = Jabatan::where('NM_UNIT', $TAD['UNIT_KERJA'])->first();
                    // if (!$posisi_tad) {
                    //     $posisi_tad = new Jabatan;
                    //     $posisi_tad->idunit = Jabatan::max('idunit') + 1;
                    //     $posisi_tad->kategori_id    = 1;
                    //     $posisi_tad->NM_UNIT        = $TAD['UNIT_KERJA'];
                    //     $posisi_tad->jenis          = $TAD['JENIS_JABATAN'] ?? 'J-901';
                    //     $posisi_tad->is_migrasi     = 1;
                    //     $posisi_tad->save();
                    // }
                    if (!$posisi_tad) {
                        $tad = new TadFailed;
                        $failed = true;
                    }
                    $vendor = Vendor::where('nama', $TAD['NAMA_PERUSAHAAN'] ?? '')->first();
                    // if (!$vendor) {
                    //     $vendor = new Vendor;
                    //     $vendor->nama = $TAD['NAMA_PERUSAHAAN'] ?? '';
                    //     $vendor->is_migrasi = 1;
                    //     $vendor->save();
                    // }
                    if (!$vendor) {
                        $tad = new TadFailed;
                        $failed = true;
                    }
                    // =====================================
                    $tad->source            = 2;
                    $tad->NO                = $TAD['NO'];
                    $tad->nama              = $TAD['NAMA'];
                    $tad->telepon           = $TAD['NOHP'] ?? '';
                    $tad->jenis_kelamin     = $TAD['JNS_KELAMIN'];
                    if (isset($TAD['STAT_KAWIN'])) {
                        $tad->status_perkawinan = $TAD['STAT_KAWIN'] == 0 ? 'Lajang' : ($TAD['STAT_KAWIN'] == 1 ? 'Menikah' : 'Cerai');
                    } else {
                    }
                    $tad->agama_id          = $TAD['AGAMA'] ?? null;
                    $tad->tempat_lahir      = $TAD['TEMPAT_LAHIR'] ?? '';
                    $tad->tanggal_lahir     = isset($TAD['TGL_LAHIR']) ? Carbon::parse($TAD['TGL_LAHIR'])->addHours(7)->format('d/m/Y') : null;
                    $tad->alamat_lengkap    = $TAD['ALAMAT'] ?? '';
                    $city                   = City::where('name', $TAD['KOTA'] ?? '')->first();
                    $tad->city_id           = $city->id ?? null;
                    $tad->provinsi_id       = null;
                    $_pendidikan            = (int)($TAD['PENDIDIKAN'] ?? null);
                    $tad->pendidikan_id     = $_pendidikan != 0 ?  $_pendidikan : null;
                    $tad->jabatan_id        = $posisi_tad->idunit ?? null;
                    $tad->vendor_id         = $vendor->id ?? null;
                    $tad->rekening_bjtm     = $TAD['REKENING'] ?? null;
                    $tad->save();

                    if(!$failed){
                        // Migrasi
                        // TAD Migrasi
                        $tadMigrasi = new TadMigrasi;
                        $tadMigrasi->source            = 2;
                        $tadMigrasi->NO                = $TAD['NO'];
                        $tadMigrasi->nama              = $TAD['NAMA'];
                        $tadMigrasi->telepon           = $TAD['NOHP'] ?? '';
                        $tadMigrasi->jenis_kelamin     = $TAD['JNS_KELAMIN'];
                        if (isset($TAD['STAT_KAWIN'])) {
                            $tadMigrasi->status_perkawinan = $TAD['STAT_KAWIN'] == 0 ? 'Lajang' : ($TAD['STAT_KAWIN'] == 1 ? 'Menikah' : 'Cerai');
                        } else {
                        }
                        $tadMigrasi->agama_id          = $TAD['AGAMA'] ?? null;
                        $tadMigrasi->tempat_lahir      = $TAD['TEMPAT_LAHIR'] ?? '';
                        $tadMigrasi->tanggal_lahir     = isset($TAD['TGL_LAHIR']) ? Carbon::parse($TAD['TGL_LAHIR'])->addHours(7)->format('d/m/Y') : null;
                        $tadMigrasi->alamat_lengkap    = $TAD['ALAMAT'] ?? '';
                        $city                          = City::where('name', $TAD['KOTA'] ?? '')->first();
                        $tadMigrasi->city_id           = $city->id ?? null;
                        $tadMigrasi->provinsi_id       = null;
                        $_pendidikan                   = (int)($TAD['PENDIDIKAN'] ?? null);
                        $tadMigrasi->pendidikan_id     = $_pendidikan != 0 ?  $_pendidikan : null;
                        $tadMigrasi->jabatan_id        = $posisi_tad->idunit ?? null;
                        $tadMigrasi->vendor_id         = $vendor->id ?? null;
                        $tadMigrasi->rekening_bjtm     = $TAD['REKENING'] ?? null;
                        $tadMigrasi->save();
                    }

                    $kepegawaian = null;
                    if ($failed) {
                        $kepegawaian            = new KepegawaianFailed;
                    } else {
                        $kepegawaian            = new Kepegawaian;
                    }
                    $kepegawaian->is_imported   = 1;
                    $kepegawaian->nio           = $TAD['NIO'];
                    $kepegawaian->status        = isset($TAD['STAT_AKTIF']) && $TAD['STAT_AKTIF'] == 1 ? Kepegawaian::MIGRATE : Kepegawaian::RESIGN;
                    $kepegawaian->year          = now()->format('Y');
                    $kepegawaian->semester      = now()->format('m') <= 6 ? 'Satu' : 'Dua';
                    $kepegawaian->tad_id        = $tad->id;
                    if ($struct = OrgStruct::where('code', $TAD['CABANG'] ?? '')->first()) {
                        $kepegawaian->cabang_id     = $struct->id ?? null;
                        $kepegawaian->vendor_id     = $vendor->id ?? null;
                        $kepegawaian->jabatan_id    = $posisi_tad->idunit ?? null;
                        $kepegawaian->nio           = $TAD['NIO'] ?? '';
                        $kepegawaian->no_sk         = $TAD['NO_SK'] ?? '';
                        if (isset($TAD['TGL_MASUK'])) {
                            $kepegawaian->in_at         = Carbon::parse($TAD['TGL_MASUK'])->format('d/m/Y');
                        }
                        $kepegawaian->save();
                        $tad->kepegawaian_id = $kepegawaian->id;
                        $tad->save();
                    } else {
                    }

                    if(!$failed){
                        $kepegawaianMigrasi = null;
                        $kepegawaianMigrasi     = new KepegawaianMigrasi;
                        // kepegawaian migrasi
                        $kepegawaianMigrasi->is_imported   = 1;
                        $kepegawaianMigrasi->nio           = $TAD['NIO'];
                        $kepegawaianMigrasi->status        = isset($TAD['STAT_AKTIF']) && $TAD['STAT_AKTIF'] == 1 ? KepegawaianMigrasi::MIGRATE : KepegawaianMigrasi::RESIGN;
                        $kepegawaianMigrasi->year          = now()->format('Y');
                        $kepegawaianMigrasi->semester      = now()->format('m') <= 6 ? 'Satu' : 'Dua';
                        $kepegawaianMigrasi->tad_id        = $tadMigrasi->id;
                        if ($struct = OrgStruct::where('code', $TAD['CABANG'] ?? '')->first()) {
                            $kepegawaianMigrasi->cabang_id     = $struct->id ?? null;
                            $kepegawaianMigrasi->vendor_id     = $vendor->id ?? null;
                            $kepegawaianMigrasi->jabatan_id    = $posisi_tad->idunit ?? null;
                            $kepegawaianMigrasi->nio           = $TAD['NIO'] ?? '';
                            $kepegawaianMigrasi->no_sk         = $TAD['NO_SK'] ?? '';
                            if (isset($TAD['TGL_MASUK'])) {
                                $kepegawaianMigrasi->in_at         = Carbon::parse($TAD['TGL_MASUK'])->format('d/m/Y');
                            }
                            $kepegawaianMigrasi->save();
                            $tadMigrasi->kepegawaian_id = $kepegawaianMigrasi->id;
                            $tadMigrasi->save();
                        } else {
                        }
                    }
                }
                // DB::commit();
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        $this->output->title('Synchronize success.');
        return 0;
    }
}
