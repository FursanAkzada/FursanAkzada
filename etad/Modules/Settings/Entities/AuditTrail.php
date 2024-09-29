<?php

namespace Modules\Settings\Entities;

use App\Entities\BaseModel;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditTrail extends BaseModel
{
    use HasFactory;

    protected $table = 'sys_audit_trail';
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'ip_address',
        'browser',
        'old_data',
        'new_data'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array'
    ];

    public const ACTIONS = [
        'login',
        'logout',
        'access',
        'create',
        'read',
        'update',
        'approval',
        'kandidat',
        'verificate',
        'delete',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeMe($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public function actionRaw()
    {
        //primary, info, success, warning, danger, secondary, light, dark
        $maps = [
            'login'         => 'success',
            'logout'        => 'danger',
            'access'        => 'info',
            'create'        => 'success',
            'read'          => 'primary',
            'update'        => 'warning',
            'submit'        => 'warning',
            'approval'      => 'warning',
            'kandidat'      => 'primary',
            'verificate'    => 'primary',
            'print'          => 'primary',
            'delete'        => 'danger',
            'reject'        => 'danger',
        ];
        $action = '<span class="badge badge-' . (array_key_exists($this->action, $maps) ? $maps[$this->action] : 'primary') . ' text-uppercase">
                            ' . $this->action . '
                        </span>';
        return $action;
    }

    public function moduleShow()
    {
        // $modules = \Base::getModules($this->module);
        return \Base::getModules($this->module);
    }

    public function createdByRawAuditTrail(){
        return '<div data-order="' . ($this->updated_at ?: $this->created_at) . '" class="text-left make-td-py-0">
                <small>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . \Str::title($this->user) . '"
                            class="fa fa-user fa-fw fa-lg mr-2"></i>
                        ' . \Str::title($this->user->name) . '
                    </div>
                    <div class="text-nowrap">
                        <i data-toggle="tooltip" title="' . $this->created_at->translatedFormat('d M Y, H:i:s') . '"
                            class="fa fa-clock fa-fw fa-lg mr-2"></i>
                        ' . $this->getCreationDate() . '
                    </div>
                </small>
            </div>';
    }
}
