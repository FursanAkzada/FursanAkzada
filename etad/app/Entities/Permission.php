<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as PermissionModel;
class Permission extends PermissionModel
{
    use HasFactory;

    public function groups()
    {
        return $this->morphedByMany(Group::class, 'model', 'sys_model_has_permissions', 'permission_id', 'model_id');
    }
}
