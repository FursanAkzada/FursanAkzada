<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;

class Pewawancara extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_pewawancara';
    protected $fillable = [
        'wawancara_id',
        'user_id',
    ];

    public function wawancara()
    {
        return $this->belongsTo(SummaryWawancara::class, 'wawancara_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
