<?php

namespace App\Entities;

use App\Entities\Auth\Group;
use App\Entities\BaseModel;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Org\Positions;

class Record extends BaseModel
{
    use HasFactory;
    protected $table = 'sys_record';
    protected $fillable = [
        'key',
        'value',
    ];
    public $timestamps = false;

}
