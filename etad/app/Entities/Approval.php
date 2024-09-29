<?php

namespace App\Entities;

use App\Entities\BaseModel;
use App\Entities\Group;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\SO\Positions;

class Approval extends BaseModel
{
    use HasFactory;
    protected $table = 'sys_approval';
    protected $fillable = [
        'targetable_type',
        'targetable_id',
        'group_id',
        'user_id',
        'position_id',
        'order',
        'type',
        'keterangan',
        'is_upgrade',
        'status',
        'approved_at',
    ];

    protected $dates = ['approved_at'];

    public function targetable()
    {
        return $this->morphTo();
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }

    /** MUTATOR **/
    /** ACCESSOR **/
    public function getShowTypeAttribute()
    {
        if ($this->type == 2) {
            return 'Paralel';
        }
        return 'Sekuensial';
    }

    public function getShowColorAttribute()
    {
        if ($this->type == 2) {
            return 'info';
        }
        return 'primary';
    }

    public function labelStatus($status = null)
    {
        if($this->status == 'draft'){
            $status = 'new';
        }else{
            $status = $this->status;
        }
        return BaseModel::getStatus($status);
    }

}
