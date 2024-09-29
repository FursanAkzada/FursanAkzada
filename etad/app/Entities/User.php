<?php

namespace App\Entities;

use App\Entities\EHC\Jabatan;
use App\Entities\EHC\User as UserEHC;
use App\Entities\Sys\SysUserPerusahaan;
use App\Entities\Traits\RaidModel;
use App\Entities\Traits\Utilities;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Pertanyaan\Tad as PertanyaanTad;
use Modules\Master\Entities\Pertanyaan\Vendor as PertanyaanVendor;
use Modules\Master\Entities\Perusahaan;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Master\Entities\Wawancara\Pertanyaan;
use Modules\Pengajuan\Entities\Tad\Pewawancara;
use Modules\Settings\Entities\AuditTrail;
use Modules\Settings\Entities\Menu;
use Modules\Settings\Entities\MenuFlow;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, RaidModel, Utilities;

    protected $table = 'sys_users';
    protected $fillable = [
        'status',
        'position_id',
        'name',
        'nik',
        'username',
        'email',
        'password',
        'phone',
        'kd_log',
        'user_type',
        'vendor_id',
        'photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'user_id');
    }

    public function ehc()
    {
        return $this->belongsTo(UserEHC::class, 'kd_log', 'KD_LOG');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'sys_groups_users', 'user_id', 'group_id');
    }

    public function audits()
    {
        return $this->hasMany(AuditTrail::class, 'user_id')
            ->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }

    public function soCreator()
    {
        return $this->hasMany(OrgStruct::class, 'created_by');
    }
    public function soUpdater()
    {
        return $this->hasMany(OrgStruct::class, 'updated_by');
    }
    public function positionCreator()
    {
        return $this->hasMany(Positions::class, 'created_by');
    }
    public function positionUpdater()
    {
        return $this->hasMany(Positions::class, 'updated_by');
    }
    public function provinceCreator()
    {
        return $this->hasMany(Province::class, 'created_by');
    }
    public function provinceUpdater()
    {
        return $this->hasMany(Province::class, 'updated_by');
    }
    public function cityCreator()
    {
        return $this->hasMany(City::class, 'created_by');
    }
    public function cityUpdater()
    {
        return $this->hasMany(City::class, 'updated_by');
    }
    public function kategoriVendorCreator()
    {
        return $this->hasMany(KategoriVendor::class, 'created_by');
    }
    public function kategoriVendorUpdater()
    {
        return $this->hasMany(KategoriVendor::class, 'updated_by');
    }
    public function vendorCreator()
    {
        return $this->hasMany(Vendor::class, 'created_by');
    }
    public function vendorUpdater()
    {
        return $this->hasMany(Vendor::class, 'updated_by');
    }
    public function posisiTadCreator()
    {
        return $this->hasMany(Jabatan::class, 'created_by');
    }
    public function posisiTadUpdater()
    {
        return $this->hasMany(Jabatan::class, 'updated_by');
    }
    // Master: Kategori Pertanyaan & Pertanyaan di satu tabel yang sama
    public function masterPertanyaanTadCreator()
    {
        return $this->hasMany(PertanyaanTad::class, 'created_by');
    }
    public function masterPertanyaanTadUpdater()
    {
        return $this->hasMany(PertanyaanTad::class, 'updated_by');
    }
    public function masterPertanyaanVendorCreator()
    {
        return $this->hasMany(PertanyaanVendor::class, 'created_by');
    }
    public function masterPertanyaanVendorUpdater()
    {
        return $this->hasMany(PertanyaanVendor::class, 'updated_by');
    }
    public function masterWawancaraKompetensiCreator()
    {
        return $this->hasMany(Kompetensi::class, 'created_by');
    }
    public function masterWawancaraKompetensiUpdater()
    {
        return $this->hasMany(Kompetensi::class, 'updated_by');
    }
    public function masterWawancaraPertanyaanCreator()
    {
        return $this->hasMany(Pertanyaan::class, 'created_by');
    }
    public function masterWawancaraPertanyaanUpdater()
    {
        return $this->hasMany(Pertanyaan::class, 'updated_by');
    }
    public function sysGroupCreator()
    {
        return $this->hasMany(Group::class, 'created_by');
    }
    public function sysGroupUpdater()
    {
        return $this->hasMany(Group::class, 'updated_by');
    }
    public function sysRoleCreator()
    {
        return $this->hasMany(Role::class, 'created_by');
    }
    public function sysRoleUpdater()
    {
        return $this->hasMany(Role::class, 'updated_by');
    }
    public function sysUserCreator()
    {
        return $this->hasMany(Self::class, 'created_by');
    }
    public function sysUserUpdater()
    {
        return $this->hasMany(Self::class, 'updated_by');
    }
    public function sysMenuCreator()
    {
        return $this->hasMany(Menu::class, 'created_by');
    }
    public function sysMenuUpdater()
    {
        return $this->hasMany(Menu::class, 'updated_by');
    }
    public function sysMenuFlowCreator()
    {
        return $this->hasMany(MenuFlow::class, 'created_by');
    }
    public function sysMenuFlowUpdater()
    {
        return $this->hasMany(MenuFlow::class, 'updated_by');
    }
    public function pewawancaras()
    {
        return $this->hasMany(Pewawancara::class, 'user_id');
    }
    // End Relationship
    // Scope
    public function scopeCanDo($query, $perms)
    {
        return $query->whereHas(
            'groups.permissions',
            function ($w) use ($perms) {
                if (is_array($perms)) {
                    $w->whereIn('name', $perms);
                } else {
                    $w->where('name', $perms);
                }
            }
        );
    }

    public function scopeHasEhc($query)
    {
        return $query->where('user_type', 'ehc');
    }

    public function scopeHasVendor($query)
    {
        return $query->where(
            function ($q) {
                $q->where('user_type', 'web');
                $q->whereNotNull('vendor_id');
            }
        );
    }
    // End Scope
    // Attributes
    public function getCabangIdAttribute()
    {
        return $this->ehc ? ($this->ehc->cabang ? $this->ehc->cabang->KDCAB : '-') : null;
    }

    public function getCabangAttribute()
    {
        return $this->ehc ? ($this->ehc->cabang ? $this->ehc->cabang->CAPEM : '-') : '-';
    }

    public function getJabatanIdAttribute()
    {
        return $this->ehc ? ($this->ehc->cabang ? $this->ehc->cabang->KDCAB : '-') : null;
    }

    public function getJabatanAttribute()
    {
        return $this->ehc ? ($this->ehc->cabang ? $this->ehc->cabang->CAPEM : '-') : '-';
    }

    public function getIsEhcAttribute()
    {
        return $this->user_type == 'ehc' ? true : false;
    }

    public function getIsVendorAttribute()
    {
        return $this->user_type == 'web' ? true : false;
    }
    // End Attributes

    public function checkGroup($group)
    {
        return $this->whereHas(
            'roles',
            function ($q) use ($group) {
                $q->whereLike(['name'], $group);
            }
        )->get();
    }

    public function cekDivisiHC()
    {
        if ($this->position && $this->position->imHC() == true) {
            return true;
        }
        return false;
    }

    public function latestNotify()
    {
        $unread = $this->unreadNotifications()->get();
        return $unread->take(10);
    }

    public function checkPerms($permission)
    {
        $permits = Permission::whereHas(
            'groups',
            function ($w) {
                $w->whereIn('id', $this->groups->pluck('id')->toArray());
            }
        )->where('name', 'like', $permission . '%')->exists();
        return $permits;
    }

    public function storeLog($module, $action, $new = null, $old = null)
    {
        $audit = new AuditTrail(
            [
                'module'     => $module,
                'action'     => $action,
                'browser'    => \Browser::browserName() ?: (request()->header('User-OS') ?: request()->header('User-Agent')),
                'ip_address' => \Request::ip(),
                'old_data'   => $old,
                'new_data'   => $new,
            ]
        );

        return $this->audits()->save($audit);
    }

    public static function createByRequest($request, $user_type = 'web')
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->except(['roles']));
            // $record->username = strtok($request->email, '@');
            $record->password = bcrypt($request->password);
            $record->user_type = $user_type;
            $record->save();

            // $record->assignRole($request->roles ?? []);
            $record->groups()->sync([$request->groups]);

            auth()->user()->storeLog(
                ($record->user_type === 'ehc' ? 'setting.user' : 'setting.user-vendor'),
                'create',
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
                'message' => 'Data successfully saved',
                'data'    => $record
            ]
        );
    }


    public function handleResetPassword($request)
    {
        \DB::beginTransaction();
        try {
            $this->password = bcrypt('qwerty123456');
            $this->save();
            auth()->user()->storeLog(
                ($this->user_type === 'ehc' ? 'setting.user' : 'setting.user-vendor'),
                'reset',
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
                'message' => 'Data successfully resetted',
                'data'    => $this,
            ]
        );
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            if (!is_null($request->password)) {
                if ($this->user_type == 'ehc') {
                    $request['password'] = bcrypt($request->password);
                    // $request['password'] = md5($request->password);
                } else {
                    $request['password'] = bcrypt($request->password);
                }
                $this->update($request->all());
            } else {
                $this->update($request->except(['password']));
            }
            // $this->roles()->detach();
            // $this->assignRole($request->roles);
            if ($request->groups) {
                $this->groups()->sync($request->groups);
            }

            /* Dirty */
            $old = $this->replicate();
            $old->preventAttrSet = true;
            $dirty = $this->getDirty();
            foreach ($dirty as $field => $newdata) {
                $olddata = $this->getOriginal($field);
                if ($olddata != $this->getAttributes()[$field]) {
                    $old->$field = $olddata;
                }
            }
            auth()->user()->storeLog(
                ($this->user_type === 'ehc' ? 'setting.user' : 'setting.user-vendor'),
                'update',
                $this->getAttributes(),
                $old->getAttributes()
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

    public function changePassByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // $password = $this->isEhc ?
            //     /* For EHC User */
            //     md5($request->new_password) :
            //     /* For Vendor User */
            //     \Hash::make($request->new_password);

            $password = bcrypt($request->new_password);

            $this->update(
                [
                    'password' => $password
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
                'data'    => $this,
                'redirectTo' => route('settings.change-password.index')
            ]
        );
    }

    public function changePhotoByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $password = $this->isEhc ?
                /* For EHC User */
                md5($request->new_password) :
                /* For Vendor User */
                \Hash::make($request->new_password);

            $userDir = 'profile/';

            $foto = $request->foto;
            $fotoFile = 'foto-' . \Str::uuid() . '.' . $foto->getClientOriginalExtension();
            Storage::putFileAs($userDir, $foto, $fotoFile);

            $this->update(
                [
                    'photo' => $fotoFile
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
                'data'    => $this,
                'redirectTo' => route('settings.profile.index')
            ]
        );
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            auth()->user()->storeLog(($this->user_type === 'ehc' ? 'setting.user' : 'setting.user-vendor'), 'delete');
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

    public function getGroupIds()
    {
        return $this->groups()->pluck('id')->toArray();
    }
    public function getOrgNameAttribute($query)
    {
        return $this->position->struct->name ?? '-';
    }
    public function getPositionNameAttribute($query)
    {
        return $this->position->name ?? '-';
    }

    public static function selectAjax($limit = 100)
    {
        $select      = request()->select;
        $records    = Self::with(
            [
                'position',
                'vendor',
            ]
        )
            // ->when( // unit kerja terpilih + HC + sub divisi HC
            //     $with = request()->with,
            //     function ($q) use ($with) {
            //         $q->whereHas(
            //             'position',
            //             function ($q) use ($with) {
            //                 $q->whereHas(
            //                     'struct',
            //                     function ($q) use ($with) {
            //                         $q
            //                             ->where('code', 'A35')
            //                             ->orWhereHas(
            //                                 'parent',
            //                                 function ($q) {
            //                                     $q->where('code', 'A35');
            //                                 }
            //                             )
            //                             ->orWhere('id', $with);
            //                     }
            //                 );
            //             }
            //         );
            //     }
            // )
            ->when( // vendor terpilih + HC + sub divisi HC
                $with = request()->with,
                function ($q) use ($with) {
                    $q
                        ->where('vendor_id', $with)
                        ->orWhereHas(
                            'position',
                            function ($q) use ($with) {
                                $q->whereHas(
                                    'struct',
                                    function ($q) use ($with) {
                                        $q
                                            ->where('code', 'A35')
                                            ->orWhereHas(
                                                'parent',
                                                function ($q) {
                                                    $q->where('code', 'A35');
                                                }
                                            );
                                    }
                                );
                            }
                        );
                }
            )
            ->when(
                $select === 'hc_with_vendor',
                function ($q) use ($with) {
                    $q
                        ->where('vendor_id', '!=', null)
                        ->orWhereHas(
                            'position',
                            function ($q) use ($with) {
                                $q->whereHas(
                                    'struct',
                                    function ($q) use ($with) {
                                        $q
                                            ->where('code', 'A35')
                                            ->orWhereHas(
                                                'parent',
                                                function ($q) {
                                                    $q->where('code', 'A35');
                                                }
                                            );
                                    }
                                );
                            }
                        );
                }
            )
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['name', 'position.parent.name'], $keyword);
                }
            )
            ->where('id', '!=', '1')
            ->orderBy('name', 'asc')
            ->paginate($limit);

        $response = [];
        foreach ($records as $key => $value) {
            $position = $value->position->name ?? null;
            $vendor = $value->vendor->nama ?? null;
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->name . ($position ? ' (' . $position . ')' : ($vendor ? ' (Vendor ' . $vendor . ')' : '')),
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }

    public static function selectAjaxUserDivisiHC($request, $limit = 32)
    {
        // divisi hc
        $divisi = OrgStruct::where('code', 'A35')->firstOrFail();
        $lists = $divisi->getIdsWithChild();

        $records = Self::where('user_type', 'ehc')
            ->where('position_id', '!=', null)
            ->whereHas('position', function ($q) use ($lists) {
                $q->whereIn('org_struct_id', $lists);
            })
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['name', 'position.parent.name'], $keyword);
                }
            )
            ->when(
                $unit_kerja_id = $request->unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->orWhereHas(
                        'position',
                        function ($q) use ($unit_kerja_id) {
                            $q->where('org_struct_id', $unit_kerja_id);
                        }
                    );
                }
            )
            ->orderBy('name', 'asc')->paginate($limit);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->name . ' (' . $value->position_name . ')',
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }

    public function canDelete()
    {
        return !($this->soCreator()->exists() || $this->soUpdater()->exists() ||
            $this->positionCreator()->exists() || $this->positionUpdater()->exists() ||
            $this->provinceCreator()->exists() || $this->provinceUpdater()->exists() ||
            $this->cityCreator()->exists() || $this->cityUpdater()->exists() ||
            $this->kategoriVendorCreator()->exists() || $this->kategoriVendorUpdater()->exists() ||
            $this->vendorCreator()->exists() || $this->vendorUpdater()->exists() ||
            $this->posisiTadCreator()->exists() || $this->posisiTadUpdater()->exists() ||
            $this->masterPertanyaanTadCreator()->exists() || $this->masterPertanyaanTadUpdater()->exists() ||
            $this->masterPertanyaanVendorCreator()->exists() || $this->masterPertanyaanVendorUpdater()->exists() ||
            $this->masterWawancaraKompetensiCreator()->exists() || $this->masterWawancaraKompetensiUpdater()->exists() ||
            $this->masterWawancaraPertanyaanCreator()->exists() || $this->masterWawancaraPertanyaanUpdater()->exists() ||
            $this->sysGroupCreator()->exists() || $this->sysGroupUpdater()->exists() ||
            $this->sysRoleCreator()->exists() || $this->sysRoleUpdater()->exists() ||
            $this->sysUserCreator()->exists() || $this->sysUserUpdater()->exists() ||
            $this->sysMenuCreator()->exists() || $this->sysMenuUpdater()->exists() ||
            $this->sysMenuFlowCreator()->exists() || $this->sysMenuFlowUpdater()->exists() ||
            $this->sysMenuFlowCreator()->exists() || $this->sysMenuFlowUpdater()->exists() ||
            $this->pewawancaras()->exists() ||
            $this->approvals()->exists() || $this->audits()->exists()
        );
    }

    public function getLastNotificationId()
    {
        $last = $this->notifications()->latest()->first();
        return $last->id ?? 0;
    }
}
