<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RiwayatKerja extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_tad_riwayat_kerja';
    protected $fillable = [
        'tad_id',
        'title',
        'employment_type',
        'company',
        'location_company',
        'system_working',
        'description',
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date', 'end_date'
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }

    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }
}
