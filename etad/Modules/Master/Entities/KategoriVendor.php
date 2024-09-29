<?php

namespace Modules\Master\Entities;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Tad\Tad;

class KategoriVendor extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_kategori_vendor';
    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function getTadBjtmAttribute($query)
    {
        return $this->tad()->whereHas('kepegawaian')->count();
    }

    public function jabatanTad()
    {
        return $this->hasMany(Jabatan::class, 'kategori_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'vendor_id');
    }

    public function tad()
    {
        return $this->hasMany(Tad::class, 'vendor_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(
            Vendor::class,
            VendorKategoriPivot::class,
            'kategori_id',
            'vendor_id'
        );
    }

    public function canDelete()
    {
        return !($this->vendors()->exists() ||
            $this->jabatanTad()->exists() ||
            $this->id == 1);
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        $request->validate(
            [
                'nama' => ['required', 'unique:ref_kategori_vendor,nama']
            ]
        );
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
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
            $this->update($request->all());
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
            // return $this;
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
}
