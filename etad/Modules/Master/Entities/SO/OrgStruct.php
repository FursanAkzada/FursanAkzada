<?php

namespace Modules\Master\Entities\SO;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use App\Entities\Role;
use App\Entities\User;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class OrgStruct extends BaseModel
{
    protected $table = 'ref_org_structs';

    protected $fillable = [
        'parent_id',
        'level',
        'type',
        'name',
        'code',
        'phone',
        'address',
        'status',
        'email',
        'fax',
        'website',
        'pic_id',
        'province_id',
        'city_id',
    ];

    // protected $appends = ['root_parent'];

    public function parent()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id');
    }
    public function positions()
    {
        return $this->hasMany(Positions::class, 'org_struct_id');
    }

    public function allParent()
    {
        return $this->belongsTo(OrgStruct::class, 'parent_id')->with('parent');
    }

    public function child()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id')->orderBy('level');
    }

    public function childs()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id')->orderBy('level')->with('child');
    }

    public function allChild()
    {
        return $this->hasMany(OrgStruct::class, 'parent_id')->orderBy('level')->with('child');
    }

    public function employes()
    {
        return $this->hasManyThrough(
            Tad::class,
            Kepegawaian::class,
            'cabang_id',
            'id',
            'id',
            'tad_id'
        );
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }

    function quotas()
    {
        return $this->hasMany(Quota::class, 'org_struct_id');
    }

    public function scopeRoot($query, $name = null)
    {
        return $query->where('level', 'root')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeBod($query, $name = null)
    {
        return $query->where('level', 'bod')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeVice($query, $name = null)
    {
        return $query->where('level', 'vice')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeDivision($query, $name = null)
    {
        return $query->where('level', 'division')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeDepartemen($query, $name = null)
    {
        return $query->where('level', 'departemen')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeCabang($query, $name = null)
    {
        return $query->where('level', 'cabang')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeCapem($query, $name = null)
    {
        return $query->where('level', 'capem')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeKas($query, $name = null)
    {
        return $query->where('level', 'kas')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeJabatan($query, $name = null)
    {
        return $query->where('level', 'jabatan')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopePayment($query, $name = null)
    {
        return $query->where('level', 'payment')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopePenyediaJasa($query, $name = null)
    {
        return $query->where('level', 'penyedia-jasa')
            ->when(
                !is_null($name),
                function ($q) use ($name) {
                    $q->where('name', $name);
                }
            );
    }

    public function scopeDivisionHc($query)
    {
        return $query->where('name', 'Divisi Human Capital');
    }

    public function scopeBodHc($query)
    {
        return $query->where('name', 'Direktur SDM');
    }

    public function scopeInHC($query)
    {
        $temp = OrgStruct::where(function ($q) {
            $q->where(function ($qq) {
                $qq->bodHC();
            })->orWhere(function ($qq) {
                $qq->divisionHC();
            });
        })->get();
        $lists = [];
        foreach ($temp as $dd) {
            $lists = array_merge($lists, $dd->getIdsWithChild());
        }
        return $query->whereIn('id', $lists);
    }


    public function canDeleted()
    {
        if ($this->child()->count()) return false;
        if ($this->positions()->count()) return false;
        if ($this->quotas()->count()) return false;
        if (in_array($this->type, [1, 2, 3])) return false;

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
        return $query->filterBy(['name', 'code', 'parent_id'])
            ->latest("updated_at");
    }

    public function getShowLevelAttribute()
    {
        return Self::getLevelLabel($this->level);
    }
    public static function getLevelLabel($level)
    {
        return [
            'root'          =>  'Perseroan',
            'bod'           =>  'Direksi',
            'vice'          =>  'SEVP',
            'division'      =>  'Divisi',
            'departemen'    =>  'Sub Divisi',
            'cabang'        =>  'Cabang',
            'capem'         =>  'Cabang Pembantu',
            'kas'           =>  'Kantor Kas',
        ][$level] ?? ucfirst($level);
    }

    public static function groupByLevel($levels = [], $with_positions = false)
    {
        $data = [];
        if (empty($levels)) {
            $levels = [
                'bod',
                'vice',
                'division',
                // 'departemen',
                'cabang',
                'capem',
                'kas',
            ];
        }
        foreach ($levels as $level) {
            $data[$level] = static::select('id', 'parent_id', 'name')
                ->where('level', $level)
                ->has('parent')
                ->when(
                    $with_positions,
                    function ($q) {
                        $q
                            // ->whereHas('positions')
                            ->with(
                                [
                                    'positions' => function ($q) {
                                        $q->select('id', 'org_struct_id', 'name');
                                    }
                                ]
                            );
                    }
                )
                ->orderBy('name')
                ->get();
        }
        return collect($data);
    }

    public function selectParent($parent, $level)
    {
        if (isset($this->parent->level)) {
            if ($this->parent->level == 'root') {
                return $this->parent;
            } else {
                return $this->selectParent($this->parent, $level);
            }
        }
    }
    public function getRootParentAttribute()
    {
        if (isset($this->parent->level)) {
            if ($this->parent->level == 'root') {
                return $this->parent;
            }
            return $this->selectParent($this->parent, 'root');
        }
    }

    public static function groupByLevelWhereId($id = null, $levels = [], $with_positions = false)
    {
        $data = [];
        if (empty($levels)) {
            $levels = [
                'bod',
                'vice',
                'division',
                // 'departemen',
                'cabang',
                'capem',
                'kas',
            ];
        }
        foreach ($levels as $level) {
            $data[$level] = static::select('id', 'name')
                ->when(
                    $id,
                    function ($q) use ($id) {
                        $temp = Self::findOrFail($id);
                        $list = $temp->getIdsWithChild();
                        $q->whereIn('id', $list);
                    }
                )
                ->where('level', $level)
                ->has('parent')
                ->when(
                    $with_positions,
                    function ($q) {
                        $q
                            // ->whereHas('positions')
                            ->with(
                                [
                                    'positions' => function ($q) {
                                        $q->select('id', 'org_struct_id', 'name');
                                    }
                                ]
                            );
                    }
                )
                ->orderBy('name')
                ->get();
        }
        return collect($data);
    }

    public function getIdsWithChild()
    {
        $ids = [$this->id];
        foreach ($this->childs as $child) {
            $ids = array_merge($ids, $child->getIdsWithChild());
        }
        if (in_array($this->level, ['department', 'subbranch'])) {
            $ids[] = $this->parent_id;
        }
        return $ids;
    }

    public function generateQuota()
    {
        // $QUOTA_PERIODE = QuotaPeriode::get();
        // $JABATAN_TAD = Jabatan::get();
        // foreach ($QUOTA_PERIODE as $key => $periode) {
        //     if ($periode->status != 'draft') {
        //         $periode->status = 'new-struct';
        //         $periode->save();
        //     }
        //     foreach ($JABATAN_TAD as $key => $posisi_tad) {
        //         $quota = new Quota();
        //         $quota->status = 'new';
        //         $quota->pengajuan_tad_quota_periode_id  = $periode->id;
        //         $quota->org_struct_id                   = $this->id;
        //         $quota->posisi_tad_id                   = $posisi_tad->id;
        //         $quota->quota = 0;
        //         $quota->save();
        //     }
        // }
    }
}
