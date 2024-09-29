<?php

namespace Modules\Master\Entities;

use App\Entities\BaseModel;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Modules\Master\Entities\Tad\Tad;
use Modules\Penilaian\Entities\Vendor\Penilaian;

class Vendor extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_vendor';
    protected $fillable = [
        'kategori_id',
        'nama',
        'deskripsi',
        'is_migrasi'
    ];
    protected $appends = ['acronym'];

    public function categories()
    {
        return $this->belongsToMany(
            KategoriVendor::class,
            VendorKategoriPivot::class,
            'vendor_id',
            'kategori_id',
        );
    }

    public function getAcronymAttribute()
    {
        $str     = str_replace('PT. ', '', $this->nama);
        $words   = explode(" ", $str);
        $acronym = "";

        foreach ($words as $k => $w) {
            $acronym .= $w[0];
            if ($k == 1) {
                break;
            }
        }

        return $acronym;
    }

    public function getTadBjtmAttribute($query)
    {
        return $this->tad()->whereHas('kepegawaian')->count();
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'vendor_id');
    }

    public function tad()
    {
        return $this->hasMany(Tad::class, 'vendor_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'vendor_id');
    }

    public function canDelete()
    {
        return !($this->user()->exists() ||
            $this->tad()->exists() ||
            $this->penilaian()->exists() ||
            $this->id == 1
        );
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->except('kategori_id'));
            $record->save();
            $record->categories()->sync($request->kategori_id);

            auth()->user()->storeLog(
                'master.vendor',
                'create',
            );

            \DB::commit();
        } catch (\Throwable $e) {
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
            $this->update($request->except('kategori_id'));
            $this->categories()->sync($request->kategori_id);

            auth()->user()->storeLog(
                'master.vendor',
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
            auth()->user()->storeLog(
                'master.vendor',
                'delete',
            );
            $this->tad()->delete();
            $this->user()->delete();
            $this->delete();
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

    public static function selectAjax($request)
    {
        $limit = 32;
        $records = Self::when(
            $keyword = request()->q,
            function ($q) use ($keyword) {
                $q->whereLike(['nama'], $keyword);
            }
        )
            ->when(
                auth()->user()->isVendor,
                function ($q) {
                    $q->where('id', auth()->user()->vendor_id);
                }
            )
            ->orderBy('nama', 'asc')
            ->paginate($limit);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->nama,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }

    public static function selectAjaxAll($request)
    {
        $limit = 32;
        $records = Self::whereHas(
            'tad',
            function ($q) {
                $q->whereHas('riwayatKerja');
            }
        )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->when(
                $vendor_id = auth()->user()->vendor_id,
                function ($q) use ($vendor_id) {
                    $q->where('id', $vendor_id);
                }
            )
            ->orderBy('nama', 'asc')
            ->paginate($limit);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->nama,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }
}
