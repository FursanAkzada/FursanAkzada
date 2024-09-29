<?php

namespace Modules\Settings\Entities;

use App\Entities\BaseModel;
use App\Entities\Group;
use Modules\Rkia\Entities\Summary;

class MenuFlow extends BaseModel
{
    protected $table = 'sys_menu_flows';
    protected $fillable = [
        'menu_id',
        'group_id',
        'type',
        'order',
    ];

    public function getShowTypeAttribute()
    {
        if ($this->type == 2) {
            return 'Paralel';
        }
        return 'Sekuensial';
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function scopeHasModule($query, $module)
    {
        return $query->whereHas('name', function ($q) use ($module) {
            $q->where('name', $module);
        });
    }
}
