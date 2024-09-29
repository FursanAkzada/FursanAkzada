<?php

namespace App\Entities;

use App\Entities\Traits\RaidModel;
use App\Entities\Traits\Utilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\Master\Entities\SO\Unit;
use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{
    use RaidModel, Utilities;

    protected $fillable = [
        'parent_id',
        'name',
        'guard_name',
        'group',
        'unit_id',
        'code',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(
            'is_active',
            function (Builder $builder) {
                $builder->where('sys_roles.is_active', 1);
            }
        );
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Self::class, 'parent_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'sys_groups_roles', 'role_id', 'group_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->except('parent_id'));
            $record->parent_id = $request->parent_id == 0 ? NULL : $request->parent_id;
            $record->save();
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
                'data'    => $record
            ]
        );
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update(
                [
                    'parent_id' => $request->parent_id == 0 ? NULL : $request->parent_id,
                    $request->except('parent_id')
                ]
            );
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
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
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
                'message' => 'Data successfully deleted',
                'data'    => $this
            ]
        );
    }

    public function grantByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update(request()->all());
            // Clear Perms Cache
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            $this->permissions()->sync(request()->check);

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
                'data'    => $this,
                'redirectTo' => route('settings.roles.index')
            ]
        );
    }
}
