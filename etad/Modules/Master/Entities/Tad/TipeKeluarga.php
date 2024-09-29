<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipeKeluarga extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_tipe_keluarga';
    protected $fillable = [
        'tipe'
    ];

}
