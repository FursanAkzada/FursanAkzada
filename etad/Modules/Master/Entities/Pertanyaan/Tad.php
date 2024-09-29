<?php

namespace Modules\Master\Entities\Pertanyaan;

use App\Entities\BaseModel;
// use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Penilaian\Entities\Tad\Jawaban;

class Tad extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_penilaian_tad';
    protected $fillable = [
        'parent_id',
        'pertanyaan',
        'judul',
        'urut'
    ];

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Self::class, 'parent_id')->with('jawaban');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'pertanyaan_id');
    }

    public function scopeKategori($q)
    {
        return $q->whereDoesntHave('parent');
    }

    public function scopePertanyaan($q)
    {
        return $q->whereHas('parent');
    }

    public static function createByRequest($request, $module = 'master.pertanyaan.kategori')
    {
        \DB::beginTransaction();
        try {
            // $urut = PertanyaanTad::where('parent_id', $request->parent_id)->orderBy('urut', 'desc')->first();
            // $record->urut = $urut ? $urut->urut + 1 : 0;
            $record = new Self;
            $record->fill($request->all());
            $record->save();

            auth()->user()->storeLog(
                $module,
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
            if ($this->parent_id) {
                $this->module = 'master.pertanyaan.tad';
            } else {
                $this->module = 'master.pertanyaan.kategori';
            }
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

    public function deleteByRequest($module = 'master.pertanyaan.kategori')
    {
        \DB::beginTransaction();
        try {
            if ($this->parent_id) {
                $this->module = 'master.pertanyaan.tad';
            } else {
                $this->module = 'master.pertanyaan.kategori';
            }
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
