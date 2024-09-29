<?php

namespace Modules\Master\Entities\Tad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $table = 'ref_tad_file';
    protected $fillable = [
        'tad_id',
        'foto_filename',
        'foto_filepath',
        'foto_full_filename',
        'foto_full_filepath',
        'cv_filename',
        'cv_filepath',
        'psikotest_filename',
        'psikotest_filepath',
    ];

    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }
}
