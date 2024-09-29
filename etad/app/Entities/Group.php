<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasPermissions;

class Group extends BaseModel
{
    use HasPermissions;

    protected $table = 'sys_groups';
    protected $fillable = [
        'name',
    ];
    protected $guard_name = 'web';
    protected $module = 'setting.roles';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'sys_groups_roles', 'group_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'sys_groups_users', 'group_id', 'user_id');
    }

    public function role()
    {
        return $this->roles->first();
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            $role = Role::where('name', $request->name)->first();
            if (!$role) {
                $role = new Role;
            }
            $role->fill($request->all());
            $role->save();
            // permission
            // $perm_ids = Permission::whereIn('name', 'pengajuan.tad.personil.view')->pluck('id');
            // $role->syncpermissions($perm_ids);
            // $record->users()->sync($request->users);
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
            $this->update($request->all());
            // $this->users()->sync($request->users);
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
            // $this->update(request()->all());
            $this->touch();
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
