<?php

namespace Modules\Settings\Entities;

use App\Entities\BaseModel;
use App\Entities\Group;
use Modules\Rkia\Entities\Summary;

class Menu extends BaseModel
{
    protected $table = 'sys_menu';
    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'order',
    ];
    public function getShowModuleAttribute()
    {
        $modules = \Base::getModules();
        return $modules[$this->name] ?? '[System]';
    }
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function parents()
    {
        return $this->belongsTo(static::class, 'parent_id')->with('parent');
    }

    public function child()
    {
        return $this->hasMany(static::class, 'parent_id')->orderBy('order');
    }

    public function childs()
    {
        return $this->hasMany(static::class, 'parent_id')->orderBy('order')->with('child');
    }

    public function flows()
    {
        return $this->hasMany(MenuFlow::class, 'menu_id')->orderBy('order');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, MenuFlow::class, 'menu_id', 'group_id')
            ->orderBy('sys_menu_flows.order')
            ->withPivot(['type', 'order'])
            ->withTimestamps();
    }
    /** SCOPE **/
    public function scopeGrid($query)
    {
        $modules = \Base::getModules();
        return $query->whereIn('module', array_keys($modules))->orderBy('order');
    }

    public function scopeFilters($query)
    {
        return $query->when($module = request()->post('module_name'), function ($q) use ($module) {
                        $q->where('name', 'LIKE', '%'.$module.'%');
                        });
    }

    public function handleStoreOrUpdate($request)
    {
        \DB::beginTransaction();
        try {
            $flows_ids = [];
            $order = 1;
            if (is_array($request->flows)) {
                // Set agar key berurutan dari 1,2,3...
                $flows = array_combine(range(1, count($request->flows)), array_values($request->flows));
                foreach ($flows as $key => $val) {
                    $flow = $this->flows()->firstOrNew(['group_id' => $val['group_id'] ?? null]);
                    $flow->group_id = $val['group_id'];
                    $flow->type     = $val['type'];
                    $flow->order    = $order;
                    $flow->save();
                    $flows_ids[] = $flow->id;
                    // increment order ketika sekuensial(type==1) atau flow sebelumnya sekuensial(type==1)
                    if ($val['type'] == 1 || (!empty($flows[$key + 1]) && $flows[$key + 1]['type'] == 1)) {
                        $order++;
                    }
                }
            }
            $this->flows()->whereNotIn('id', $flows_ids)->delete();
            $this->addLog('Mengubah Flow Approval '.$this->name);
            auth()->user()->storeLog(
                'setting.flow',
                'update',
            );
            $this->touch();

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
                'message' => 'Data successfully saved',
            ]
        );
    }
}
