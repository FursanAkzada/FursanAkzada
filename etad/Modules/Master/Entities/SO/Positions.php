<?php

namespace Modules\Master\Entities\SO;

use App\Entities\BaseModel;
use App\Entities\User;

class Positions extends BaseModel
{

    protected $table = 'ref_positions';

    protected $fillable = [
        'org_struct_id',
        'parent_id',
        // 'kategori_id',
        'type',
        'name',
        'name_up',
        'code',
        'status',
    ];

    public function parentPosition()
    {
        return $this->belongsTo(Positions::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Positions::class, 'parent_id');
    }

    public function childPosition()
    {
        return $this->hasMany(Positions::class, 'parent_id');
    }

    public function struct()
    {
        return $this->belongsTo(OrgStruct::class, 'org_struct_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'position_id');
    }

    public function scopeDefaultOrderBy($query, $column = 'created_at', $direction = 'desc')
    {
        return $query->when(
            !isset(request()->order[0]['column']),
            function ($a) use ($column, $direction) {
                $a->orderBy($column, $direction);
            }
        );
    }

    public function scopeFilterBy($query, $column, $operator = 'LIKE')
    {
        if (is_array($column)) {
            foreach ($column as $col) {
                $query->filterBy($col, $operator);
            }
        } else {
            return $query->when(
                $value = request()->post($column),
                function ($a) use ($operator, $column, $value) {
                    $a->where($column, $operator, ($operator === 'LIKE') ? '%' . $value . '%' : $value);
                }
            );
        }
    }

    public function scopeFilters($query)
    {
        return $query->filterBy(['name', 'parent_id', 'org_struct_id'])
            ->defaultOrderBy('updated_at', 'desc');
    }

    public function canDeleted()
    {
        if ($this->child()->count()  || in_array($this->type, [1, 2, 3])) {
            return false;
        }
        return true;
    }

    public function dataTree($child = [])
    {
        $data = false;
        if (count($child)) {
            $data = [];
            foreach ($child as $record) {
                if ($record->level == 'bod') {
                    $icon = "fas fa-book-reader icon-md text-primary";
                } elseif ($record->level == 'division') {
                    $icon = "fab fa-accusoft icon-md text-success";
                } elseif ($record->level == 'departemen') {
                    $icon = "fas fa-code-branch icon-md text-info";
                } elseif ($record->level == 'korwil') {
                    $icon = "fas fa-code-branch icon-md text-info";
                } elseif ($record->level == 'cabang') {
                    $icon = "fas fa-code-branch icon-md text-info";
                } elseif ($record->level == 'capem') {
                    $icon = "fas fa-code-branch icon-md text-info";
                } elseif ($record->level == 'kas') {
                    $icon = "fas fa-code-branch icon-md text-info";
                } elseif ($record->level == 'jabatan') {
                    $icon = "fas fa-user-tag icon-md text-warning";
                } else {
                    $icon = "fas fa-bookmark icon-md text-warning";
                }
                $data[] = [
                    "id" => $record->id,
                    "icon" => $icon,
                    "text" => $record->name,
                    "children" => $record->dataTree($record->allChild),
                    "state" => [
                        "opened" => ($record->level == 'bod' && !is_null($record->parent) && $record->parent->level == 'COMPANY') ? true : false
                    ]
                ];
            }
        }
        return $data;
    }

    public function imHC()
    {
        $temp = OrgStruct::where(function ($q) {
            $q->where(function ($qq) {
                $qq->bodHC();
            })->orWhere(function ($qq) {
                $qq->divisionHC();
            });
        })->get();
        $lists = [];
        foreach($temp as $dd){
            $lists = array_merge($lists, $dd->getIdsWithChild());
        }
        return in_array($this->org_struct_id , $lists);
    }
}
