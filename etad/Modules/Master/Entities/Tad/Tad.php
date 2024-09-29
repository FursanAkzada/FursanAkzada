<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Agama;
use App\Entities\EHC\Jabatan;
use App\Entities\EHC\Tad as TadEhc;
use App\Entities\TempFiles;
use App\Entities\Traits\HasFiles;
use App\Imports\PersonilTadImport;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\Jurusan;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Kota;
use Modules\Master\Entities\Pendidikan as EntitiesPendidikan;
use Modules\Master\Entities\Pendidikan;
use Modules\Master\Entities\Provinsi;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\File;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Mutasi\Pegawai;
use Modules\Pengajuan\Entities\Mutasi\Pengajuan;
use Modules\Pengajuan\Entities\Resign\Pegawai as ResignPegawai;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan as PengajuanTad;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;
use Modules\Penilaian\Entities\Tad\Penilaian;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;
use Modules\Punishment\Entities\Punishment\Punishment;
use Modules\Punishment\Entities\Reward\Reward;


class Tad extends BaseModel
{
    use HasFactory, Notifiable, HasFiles;

    protected $table = 'ref_tad';
    protected $fillable = [
        'NO',
        'kepegawaian_id',
        'lock_id',
        'lock_type',
        'source',
        'nama',
        'email',
        'telepon',
        'nik',
        'npwp',
        'bpjs',
        'rekening_bjtm',
        'jenis_kelamin',
        'status_perkawinan',
        'agama_id',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'provinsi_id',
        'city_id',
        'kota_id',
        'pendidikan_id',
        'jurusan_id',
        'gelar',
        'jabatan_id',
        'vendor_id',
        'ehc_no',
        'rekomendasi',
        'date_old_contract'
    ];

    protected $dates = [
        'tanggal_lahir',
        'date_old_contract'
    ];

    const SOURCE_APP    = 1;
    const SOURCE_EXCEL  = 2;

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    public function setDateOldContractAttribute($value)
    {
        try {
            $this->attributes['date_old_contract'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAttrJenisKelaminAttribute()
    {
        return ($this->attributes['jenis_kelamin'] == 'L') ? 'Laki - Laki' : 'Perempuan';
    }

    public function getAttrStatusPerkawinanAttribute($query)
    {
        switch ($this->attributes['status_perkawinan']) {
            case '1':
                return 'Lajang';
            case '2':
                return 'Menikah';
            case '3':
                return 'Cerai';
            default:
                return '-';
        }
    }

    public function getPendidikanRawAttribute()
    {
        return $this->pendidikan ? $this->pendidikan->lengkap : '-';
    }

    public function getAgamaRawAttribute()
    {
        return $this->agama ? $this->agama->Lengkap : '-';
    }

    public function getSameAsEhcAttribute()
    {
        $nio = $this->kepegawaian ? $this->kepegawaian->nio : null;
        $ehc = TadEhc::where('NO', $this->ehc_no)->orWhere('NIO', $nio)->first();
        if ($ehc && $this->kepegawaian) {
            // Jenis Jabatan
            switch ($this->kepegawaian->jenis_jabatan) {
                case 'admin':
                    $jenis_jabatan = 'J-901';
                    break;
                case 'non-admin':
                    $jenis_jabatan = 'J-902';
                    break;
                default:
                    $jenis_jabatan = null;
                    break;
            }

            $checkData = [
                'NAMA'          => $this->nama,
                'NIO'           => $this->kepegawaian->nio ? $this->kepegawaian->nio : $ehc->NO,
                'ALAMAT'        => $this->alamat_lengkap,
                'KOTA'          => $this->kota ? $this->kota->nama : null,
                'TGL_LAHIR'     => $this->tanggal_lahir,
                'TEMPAT_LAHIR'  => $this->tempat_lahir,
                'JNS_KELAMIN'   => $this->jenis_kelamin,
                'STAT_KAWIN'    => $this->status_perkawinan,
                'PENDIDIKAN'    => $this->pendidikan ? $this->pendidikan->lengkap : null,
                'REKENING'      => $this->rekening_bjtm,
                'STAT_AKTIF'    => $this->is_active,

                'CABANG'        => $this->kepegawaian->cabang_id,
                'UNIT_KERJA'    => $this->jabatan ? $this->jabatan->NM_UNIT : null,
                'JENIS_JABATAN' => $jenis_jabatan,
                'TGL_MASUK'     => $this->kepegawaian->in_at ? date('Y-m-d H:i:s', strtotime($this->kepegawaian->in_at)) : null,
                'BLN_BERAKHIR'  => $this->kepegawaian->out_at ? date('M d Y H:iA', strtotime($this->kepegawaian->out_at)) : null,
                'NAMA_PERUSAHAAN' => $this->vendor ? $this->vendor->nama : null,
            ];

            foreach ($checkData as $key => $value) {
                if ($ehc[$key] != $value) {
                    return false;
                } // Cek Data
            }

            return true;
        }
        return false;
    }

    public function scopeMonitoring($query)
    {
        return $query->whereHas('kepegawaian');
    }

    public function scopeFilterPegawai($query)
    {
        if (auth()->user()->isEhc) {
            // return $query->isPegawai();
        } else {
            return $query->where('vendor_id', auth()->user()->vendor_id);
        }
    }

    public function scopeWorked($query)
    {
        return $query->whereHas(
            'kepegawaian',
            function ($w) {
                $w->whereNull('out_at');
            }
        );
    }

    public function scopeNotWorked($query)
    {
        return $query->whereDoesntHave('kepegawaian');
    }

    public function scopeResign($query)
    {
        return $query->whereHas(
            'kepegawaian',
            function ($w) {
                $w->whereNotNull('out_at');
            }
        );
    }

    public function scopeIsPegawai($query)
    {
        $user = auth()->user();
        return $query
            ->when(
                $vendor_id = $user->vendor_id,
                function ($q) use ($vendor_id) {
                    $q->where('vendor_id', $vendor_id);
                }
            )
            ->whereHas(
                'kepegawaian',
                function ($k) {
                    $k->whereNull('resign_at');
                    $k->whereNull('out_at');
                }
            );
    }

    public function pivotPengajuan()
    {
        return $this->belongsToMany(
            PengajuanTad::class,
            'trans_pengajuan_tad_pivot',
            'tad_id',
            'pengajuan_id'
        );
    }

    public function wawancara()
    {
        return $this->hasMany(Wawancara::class, 'tad_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id', 'Sandi');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'tad_id');
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'kepegawaian_id');
    }
    public function lastEmployment()
    {
        return $this->hasOne(Kepegawaian::class, 'tad_id')->latestOfMany();
    }
    function kepegawaianTerahir()
    {
        return $this->lastEmployment();
    }
    public function riwayatKepegawaian()
    {
        return $this->hasMany(Kepegawaian::class, 'tad_id');
    }

    public function riwayatKerja()
    {
        return $this->hasMany(Kepegawaian::class, 'tad_id');
    }

    public function riwayatKerjaBefore()
    {
        return $this->hasMany(RiwayatKerja::class, 'tad_id');
    }

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'tad_id');
    }

    public function kandidat()
    {
        return $this->hasOne(Kandidat::class, 'tad_id');
    }

    public function detail_kandidat()
    {
        return $this->hasOne(DetailKandidat::class, 'tad_id');
    }

    public function kandidats()
    {
        return $this->hasMany(Kandidat::class, 'tad_id');
    }

    function lock()
    {
        return $this->morphTo('lock');
    }

    public function mutasi()
    {
        return $this->hasMany(Pengajuan::class, 'tad_id');
    }

    public function punishment()
    {
        return $this->hasMany(Punishment::class, 'tad_id');
    }

    public function reward()
    {
        return $this->hasMany(Reward::class, 'tad_id');
    }

    public function pembinaan()
    {
        return $this->hasMany(Pembinaan::class, 'tad_id');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'tad_id')
            ->orderBy('updated_at', 'DESC');
    }

    public function pengajuanMutasiPegawai()
    {
        return $this->hasMany(Pegawai::class, 'tad_id');
    }
    public function pengajuanResignPegawai()
    {
        return $this->hasMany(ResignPegawai::class, 'tad_id');
    }

    public function generateNIO()
    {
        $latest = Kepegawaian::latest('id')->first();
        if ($latest) {
            return $latest->nio + 1;
        } else {
            return 00000001;
        }
    }

    public function similar($nama)
    {
        $data = \DB::select("select x.nip,x.nama_anggota,y.keterangan,z.nama namapegawai,z.jabatan,z.cabang from derajat_kedua_anggota_keluarga x,derajat_kedua_tipe_keluarga y, V_DATAPEGAWAI_ALL z where x.nama_anggota like '%" . $nama . "%' and x.id_tipe=y.id_tipe and z.nip=x.nip union all select x.nip,x.nama,y.lengkap,z.nama namapegawai,z.jabatan,z.cabang as keterangan from Keluarga x,hubunganKeluarga y, V_DATAPEGAWAI_ALL z where x.nama like '%" . $nama . "%' and x.Hub_Kel2=y.sandi and z.nip=x.nip order by nama_anggota");
        return $data;
    }

    public static function repairKepegawaianStatus()
    {
        \DB::beginTransaction();
        try {
            $TAD = Self::select('id')->get();
            foreach ($TAD as $tad) {
                $tad->riwayatKerja()->update(['status' => Kepegawaian::END]);
                $kepegawaian = $tad->riwayatKerja()
                    ->whereNull('resign_at')
                    ->orWhereNull('out_at')
                    ->orderBy('id', 'DESC')
                    ->first();
                if ($kepegawaian) {
                    $kepegawaian->status = Kepegawaian::WORK;
                    $kepegawaian->save();
                }
            }
            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // $request->request->add(
            //     [
            //         'vendor_id' => ($vendor = auth()->user()->vendor) ? $vendor->id : null,
            //     ]
            // );
            $record = new Self;
            $record->fill($request->all());
            $record->save();

            $record->saveFilesByTemp($request->uploads_foto3x4, 'pengajuan.tad.personil', 'foto3x4');
            $record->saveFilesByTemp($request->uploads_foto_fullbody, 'pengajuan.tad.personil', 'foto_fullbody');
            $record->saveFilesByTemp($request->uploads_cv, 'pengajuan.tad.personil', 'cv');
            $record->saveFilesByTemp($request->uploads_psikotest, 'pengajuan.tad.personil', 'psikotest');

            auth()->user()->storeLog(
                $request->module,
                'create',
            );

            // $record->file()->save(
            //     new File(
            //         [
            //             'foto_filepath'         => $userDir . $fotoFile,
            //             'foto_filename'         => $foto->getClientOriginalName(),
            //             'foto_full_filepath'    => $userDir . $fotoFullFile,
            //             'foto_full_filename'    => $fotoFull->getClientOriginalName(),
            //             'cv_filepath'           => $userDir . $cvFile,
            //             'cv_filename'           => $cv->getClientOriginalName(),
            //             'psikotest_filepath'    => $userDir . $psikotestFile,
            //             'psikotest_filename'    => $psikotest->getClientOriginalName()
            //         ]
            //     )
            // );

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully saved',
                'data'    => $record
            ]
        );
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {

            $this->saveFilesByTemp($request->uploads_foto3x4, 'pengajuan.tad.personil', 'foto3x4');
            $this->saveFilesByTemp($request->uploads_foto_fullbody, 'pengajuan.tad.personil', 'foto_fullbody');
            $this->saveFilesByTemp($request->uploads_cv, 'pengajuan.tad.personil', 'cv');
            $this->saveFilesByTemp($request->uploads_psikotest, 'pengajuan.tad.personil', 'psikotest');
            $this->update($request->all());

            auth()->user()->storeLog(
                $request->module,
                'update',
            );

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
            auth()->user()->storeLog(
                request()->module,
                'delete',
            );

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully deleted',
                'data'    => $this
            ]
        );
    }

    public function createKeluargaByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $fill = [];
            foreach ($request->parents as $item) {
                $fill[] = new Keluarga($item);
            }
            $this->keluarga()->saveMany($fill);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this,
            ]
        );
    }

    public function updateKeluargaByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $fill = [];
            foreach ($request->parents as $key => $item) {
                $fill[] = ($p = $this->keluarga->find($key)) ? $p->fill($item) : new Keluarga($item);
            }
            $this->keluarga()->saveMany($fill);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }

    // RIWAYAT KERJA
    public function updateRiwayatKerjaByRequest($request, $detail)
    {
        \DB::beginTransaction();
        try {
            $data = $request->all();
            $detail->fill($data);
            $this->riwayatKerjaBefore()->save($detail);
            $this->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }

    public function handleRiwayatKerjaDestroy($detail)
    {
        \DB::beginTransaction();
        try {
            $detail->delete();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully deleted',
                'data'    => $this
            ]
        );
    }

    public static function syncTo($data)
    {
        // Buat Kepegawaian
        $ehc = TadEhc::where('NO', $data->ehc_no);
        if (!$data->kepegawaian) {
            $data->kepegawaian()
                ->save(
                    new Kepegawaian(
                        [
                            'tad_id' => $data->id,
                            'nio' => $ehc->first() && $ehc->first()->NIO ? $ehc->first()->NIO : ''
                        ]
                    )
                );
        } else {
            $ehc = $ehc->orWhere('NIO', $data->kepegawaian->nio);
        }

        // Jenis Jabatan
        if ($data->kepegawaian && $data->kepegawaian->jenis_jabatan) {
            switch ($data->kepegawaian->jenis_jabatan) {
                case 'admin':
                    $jenis_jabatan = 'J-901';
                    break;
                case 'non-admin':
                    $jenis_jabatan = 'J-902';
                    break;
                default:
                    $jenis_jabatan = null;
                    break;
            }
        } else {
            $jenis_jabatan = null;
        }

        $request = [
            'NAMA'          => $data->nama,
            // 'NIO'           => $data->kepegawaian->nio ? $data->kepegawaian->nio : $ehc->NIO,
            'ALAMAT'        => $data->alamat_lengkap,
            'KOTA'          => $data->kota ? $data->kota->nama : null,
            'TGL_LAHIR'     => $data->tanggal_lahir,
            'TEMPAT_LAHIR'  => $data->tempat_lahir,
            'JNS_KELAMIN'   => $data->jenis_kelamin,
            'STAT_KAWIN'    => $data->status_perkawinan,
            'PENDIDIKAN'    => $data->pendidikan ? $data->pendidikan->name : null,
            'REKENING'      => $data->rekening_bjtm,
            'STAT_AKTIF'    => $data->is_active,

            'CABANG'        => $data->kepegawaian->cabang_id,
            'UNIT_KERJA'    => $data->jabatan ? $data->jabatan->NM_UNIT : null,
            'JENIS_JABATAN' => $jenis_jabatan,
            'TGL_MASUK'     => $data->kepegawaian->in_at ? date('Y-m-d H:i:s', strtotime($data->kepegawaian->in_at)) : null,
            'BLN_BERAKHIR'  => $data->kepegawaian->out_at ? date('M d Y H:iA', strtotime($data->kepegawaian->out_at)) : null,
            'NAMA_PERUSAHAAN' => $data->vendor ? $data->vendor->nama : null,

            // 'NO_SK'      => ''
            // 'STKANTOR'   => ''
            // 'TGL_PERSETUJUAN' => ''
            // 'PENEMPATAN' => ''
            // 'KETERANGAN' => ''
        ];

        // Check NIO is Existing (To Skip Duplicate NIO)
        if (!$ehc->first()->NIO) {
            $request['NIO'] = $data->kepegawaian->nio ? $data->kepegawaian->nio : $ehc->first()->NIO;
        }

        // Update or Create
        if ($ehc) {
            $ehc->update($request);
        } else {
            // Create EHC
            $ehc_no = TadEhc::latest('NO')->first()->NO + 1;
            $request['NO'] = $ehc_no;
            $tadEhc = new TadEhc;
            $tadEhc->fill($request);
            $tadEhc->save();

            // Update No EHC di TAD
            $data->update(['ehc_no' => $ehc_no]);
        }
    }

    public function syncToByRequest()
    {
        \DB::beginTransaction();
        try {
            $this::syncTo($this);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }

    public static function syncToMassByRequest()
    {
        \DB::beginTransaction();
        try {
            $tads = Tad::get()
                ->filter(
                    function ($model) {
                        return $model->same_as_hc == false && !empty($model->kepegawaian);
                    }
                );

            foreach ($tads as $tad) {
                Self::syncTo($tad);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => []
            ]
        );
    }

    public function getHistoriesAttribute()
    {
        return Kepegawaian::where('tad_id', $this->id)->get();
    }

    public static function ajaxPenilaianTAD($request)
    {
        $limit = 32;
        $list = [];
        $temp = Tad::with(
            'jabatan',
            'kepegawaian'
        )
            ->where('vendor_id', request()->vendor_id)
            ->whereHas(
                'kepegawaian',
                function ($q) {
                    $q;
                }
            )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->orderBy('nama', 'asc')->get();
        foreach ($temp as $dd) {
            $list[] = $dd->kepegawaian->unit_kerja->id;
        }
        $records = OrgStruct::whereIn('id', $list)->get();
        return $records;
    }

    public static function ajaxPenilaianTADCekJabatan($request)
    {
        $limit = 32;
        $list = [];
        $temp = Tad::with(
            [
                'jabatan',
                'kepegawaian',
            ]
        )
            ->where('vendor_id', request()->vendor_id)
            ->whereHas(
                'kepegawaian',
                function ($q) {
                    $q;
                }
            )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->orderBy('nama', 'asc')->get();
        foreach ($temp as $dd) {
            if ($dd->kepegawaian->cabang_id == $request->unit_kerja_id) {
                $list[] = $dd->kepegawaian->jabatan_id;
            }
        }
        $records = Jabatan::whereIn('idunit', $list)->get();
        return $records;
    }

    public static function ajaxPenilaianTADCekPosisi($request)
    {
        $limit = 32;
        $list = [];
        $records = Tad::with(
            'jabatan',
            'kepegawaian',
            'kepegawaian.unitKerja'
        )
            ->where('vendor_id', request()->vendor_id)
            ->whereHas(
                'kepegawaian',
                function ($q) {
                    $q
                        ->where('jabatan_id', request()->jabatan_id)
                        ->where('cabang_id', request()->unit_kerja_id)
                        ->where(
                            function ($q) {
                                $q
                                    ->where('status', '!=', Kepegawaian::RESIGN)
                                    ->when(
                                        request()->form === 'reward',
                                        function ($q) {
                                            $q->orWhere(
                                                function ($q) {
                                                    $q
                                                        ->where('status', Kepegawaian::RESIGN)
                                                        ->where('out_at', '>', now()->format('Y-m-d'));
                                                }
                                            );
                                        }
                                    );
                            }
                        );
                }
            )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->orderBy('nama', 'asc')->get();
        return $records;
    }

    public static function ajaxPenilaianTADCekPosisiNoResign($request)
    {
        $limit = 32;
        $list = [];
        $records = Tad::with(
            'jabatan',
            'kepegawaian',
            'kepegawaian.unitKerja'
        )->where('vendor_id', request()->vendor_id)
            ->whereHas(
                'kepegawaian',
                function ($q) {
                    $q->where([['jabatan_id', request()->jabatan_id], ['cabang_id', request()->unit_kerja_id]]);
                    $q->where('status', '!=', 2);
                }
            )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->whereNull('lock_type')
            ->whereNull('lock_id')
            ->orderBy('nama', 'asc')->get();
        return $records;
    }

    public function canEdit()
    {
        if ($this->pivotPengajuan()->exists()) {
            return false;
        }
        return true;
    }

    public function handleImport($request)
    {
        // \DB::beginTransaction();
        try {
            set_time_limit(350);
            ini_set('max_execution_time', 350);
            ini_set('max_input_time', 90);
            ini_set('max_input_vars', 3000);
            if (request()->tipe_import === 'quota-aktif') {
                if (\Modules\Master\Entities\Tad\TadMigrasi::count() < 2) {
                    return response()->json(
                        [
                            'success'   => false,
                            'message'   => 'Migrasi Quota tidak bisa dilakukan jika belum dilakukan migrasi personil!',
                            'trace'     => null,
                            'redirectTo' => route($request->route . '.index')
                        ],
                        422
                    );
                    // return redirect(route($request->route . '.index'));
                }
                if (QuotaPeriode::where('status', 'completed')->orWhere('version', '>', 0)->get()->first()) {
                    return response()->json(
                        [
                            'success'   => false,
                            'message'   => 'Migrasi quota hanya bisa dilakukan satu kali!',
                            'trace'     => null,
                        ],
                        422
                    );
                }
                Artisan::call('pull:quota-aktif');
                auth()->user()->storeLog(
                    'personil.quota',
                    'migrasi quota',
                );
                return redirect(route('personil.quota.index'));
            } else if (request()->tipe_import === 'aktif') {
                Artisan::call('pull:personil-aktif');
                auth()->user()->storeLog(
                    'personil.migrasi',
                    'migrasi personil aktif',
                );
            } else {
                Artisan::call('pull:personil-non-aktif');
                auth()->user()->storeLog(
                    'personil.migrasi',
                    'migrasi personil non aktif',
                );
            }
            // \DB::commit();
            if (request()->wantsJson()) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Berhasil menyimpan data',
                        'data'    => []
                    ]
                );
            }
            return redirect(route($request->route . '.index'));
        } catch (\Exception $e) {
            // return response()->json(
            //     [
            //         'success' => false,
            //         'message' => $e->getMessage(),
            //         'redirect' => route($request->route . '.index'),
            //     ]
            // );
            return redirect(route($request->route . '.index'));
        }
    }

    // public function handleImport($request)
    // {
    //     \DB::beginTransaction();
    //     try {
    //         $file = TempFiles::find($request->uploads['temp_files_ids'][0]);
    //         // dd(850, json_decode($file));
    //         if (!$file || !\Storage::disk('public')->exists($file->path)) {
    //             \DB::rollback();
    //             return response()->json(
    //                 [
    //                     'success' => false,
    //                     'message' => 'File tidak tersedia!',
    //                     'trace' => null,
    //                 ],
    //                 500
    //             );
    //         }

    //         // $retval = \Excel::import(new PersonilTadImport, \Storage::disk('public')->path($file->path));
    //         $path = \Storage::disk('public')->path($file->path);
    //         // Excel::import(new PersonilTadImport, $path);
    //         $collection = (new PersonilTadImport)->toCollection($path);
    //         $retval = (new PersonilTadImport)->insert($collection);
    //         // dd(899, $collection, $retval);

    //         // $this->saveLogNotify();
    //         \DB::commit();
    //         return response()->json(
    //             [
    //                 'success' => true,
    //                 'message' => 'Berhasil menyimpan '.$retval[0] . ' Data dari '.$retval[1] .' Baris. Data gagal disimpan sebanyak ' . $retval[2] . ' Baris.',
    //                 'data'    => []
    //             ]
    //         );
    //     } catch (\Exception $e) {
    //         \DB::rollback();
    //         return response()->json(
    //             [
    //                 'success' => false,
    //                 'message' => $e->getMessage(),
    //                 'trace' => $e->getTrace(),
    //             ],
    //             500
    //         );
    //     }
    // }
}
