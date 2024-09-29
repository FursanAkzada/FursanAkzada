<?php

namespace Modules\Settings\Http\Controllers;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $module = 'settings.user';
    protected $route  = 'settings.user';
    protected $view   = 'settings::user';
    protected $perms  = 'setting';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'title'  => 'Manajemen User',
                'breadcrumb' => [
                    'Konfigurasi' => '/',
                    'Manajemen User' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = User::with('roles')
            ->where('user_type', 'ehc')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->where(
                        function ($q) use ($keyword) {
                            $q->whereLike(['name', 'email', 'username'], $keyword)
                                // ->orWhereHas(
                                //     'ehc.cabang',
                                //     function ($q) use ($keyword) {
                                //         $q->whereLike(['CAPEM'], $keyword);
                                //     }
                                // )
                                ->orWhereHas(
                                    'groups',
                                    function ($q) use ($keyword) {
                                        $q->whereLike(['name'], $keyword);
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                $location_id = request()->post('location_id'),
                function ($q) use ($location_id) {
                    $q->whereHas(
                        'position',
                        function ($qq) use ($location_id) {
                            $qq->where('org_struct_id', $location_id);
                        }
                    );
                }
            )
            ->when(
                $group_id = request()->post('group_id'),
                function ($q) use ($group_id) {
                    $q->whereHas(
                        'roles',
                        function ($qq) use ($group_id) {
                            $qq->where('id', $group_id);
                        }
                    );
                }
            )
            ->orderBy('updated_at', 'DESC');
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            // ->editColumn(
            //     'position',
            //     function ($r) {
            //         return $this->render($this->view . '.partials.grid.position', compact('r'));
            //     }
            // )
            ->editColumn(
                'name',
                function ($r) {
                    return $r->name;
                }
            )
            ->editColumn(
                'username',
                function ($r) {
                    return $r->username;
                }
            )
            ->editColumn(
                'email',
                function ($r) {
                    return $r->email;
                }
            )
            ->editColumn(
                'org.name',
                function ($r) {
                    return $r->org_name;
                }
            )
            ->editColumn(
                'position.name',
                function ($r) {
                    return $r->position_name;
                }
            )
            ->editColumn(
                'role',
                function ($r) {
                    return $r->groups->pluck('name')->first();
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $hide_delete = $r->email == 'admin@email.com' ? 'true' : 'false';
                    $buttons = [
                        'type:show|id:' . $r->id,
                        'type:edit|id:' . $r->id,
                    ];
                    $buttons[] = [
                        'type' => 'upgrade',
                        'icon' => 'fa fa-sync text-warning',
                        'label' => 'Reset Password',
                        'url' => route($this->route . '.getUpgrade', $r->id),
                        'id'   => $r->id,
                        'attrs' => 'data-swal-text="Reset password akan mengubah password menjadi: qwerty123456"',
                    ];
                    if ($r->canDelete()) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"|hidden:' . $hide_delete;
                    }
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(
                [
                    'name',
                    'username',
                    'email',
                    'org.name',
                    'position.name',
                    'role',
                    'created_at',
                    'action',
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:username|label:Username|sortable:false'),
                        // $this->makeColumn('name:email|label:Email|sortable:false'),
                        $this->makeColumn('name:org.name|label:Unit Kerja|sortable:false|className:text-center'),
                        $this->makeColumn('name:position.name|label:Jabatan|sortable:false|className:text-center'),
                        $this->makeColumn('name:role|label:Hak Akses|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        $struct = OrgStruct::groupByLevel([], true);
        // dd(json_decode($struct));
        return $this->render(
            $this->view . '.create',
            [
                'roles' => Role::orderBy('name', 'asc')->get(),
                'struct' => $struct
            ]
        );
    }

    public function store(Request $request)
    {
        $password_rules = [
            function ($attribute, $value, $fail) {
                $str = 'password harus memiliki: ';
                $msg = [];
                if (strlen($value) < 8) {
                    $msg[] = 'minimal 8 karakter';
                    return $fail('password harus memiliki: minimal 8 karakter');
                }
                if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                    $msg[] = 'huruf besar dan huruf kecil';
                    return $fail('password harus memiliki: huruf besar dan huruf kecil');
                }
                if (!preg_match('/\pL/u', $value)) {
                    // $fail('harus berisi setidaknya satu huruf.');
                }
                if (!preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
                    $msg[] = 'simbol';
                    return $fail('password harus memiliki: simbol');
                }
                if (!preg_match('/\pN/u', $value)) {
                    $msg[] = 'angka';
                    return $fail('password harus memiliki: angka');
                }
                if (count($msg)) {
                    $fail($str . '' . implode(', ', $msg));
                }
            },
        ];
        $request->validate(
            [
                'name'                  => ['required'],
                'nik'                   => [
                    'required',
                    'max:16',
                    'unique:sys_users,nik',
                ],
                'username'              => [
                    'required',
                    'unique:sys_users,username',
                ],
                'email'                 => [
                    'required',
                    'email:filter',
                    'unique:sys_users,email',
                ],
                'org_struct_id'         => 'required',
                'position_id'           => 'required',
                'groups'                => ['required'],
                // 'roles'     => ['required'],
                'password'              => [
                    'required',
                    'confirmed',
                    ...$password_rules,
                ],
                'password_confirmation' => [
                    'required',
                    ...$password_rules,
                ]
            ]
        );
        return User::createByRequest($request, 'ehc');
    }

    public function getUpgrade($id)
    {
        $record = User::find($id);
        return $this->render(
            $this->view . '.upgrade',
            compact('record')
        );
    }

    public function saveUpgrade(Request $request, $id)
    {
        $record = User::find($id);
        return $record->handleResetPassword($request);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->render(
            $this->view . '.show',
            [
                'record' => $user,
                // 'groups' => Group::orderBy('name', 'asc')->get()
            ]
        );
    }

    public function edit(User $user)
    {
        $user = $user->load('groups');
        $struct = OrgStruct::groupByLevel([], true);
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $user,
                'struct' => $struct
            ]
        );
    }

    public function update(Request $request, User $user)
    {
        $password_rules = [
            function ($attribute, $value, $fail) {
                $str = 'password harus memiliki: ';
                $msg = [];
                if (strlen($value) < 8) {
                    $msg[] = 'minimal 8 karakter';
                    return $fail('password harus memiliki: minimal 8 karakter');
                }
                if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
                    $msg[] = 'huruf besar dan huruf kecil';
                    return $fail('password harus memiliki: huruf besar dan huruf kecil');
                }
                if (!preg_match('/\pL/u', $value)) {
                    // $fail('harus berisi setidaknya satu huruf.');
                }
                if (!preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
                    $msg[] = 'simbol';
                    return $fail('password harus memiliki: simbol');
                }
                if (!preg_match('/\pN/u', $value)) {
                    $msg[] = 'angka';
                    return $fail('password harus memiliki: angka');
                }
                if (count($msg)) {
                    $fail($str . '' . implode(', ', $msg));
                }
            },
        ];
        $rules = [
            'name'          => ['required'],
            'nik'                 => [
                'required',
                'max:16',
                'unique:sys_users,nik,' . $user->id,
            ],
            'password'      => [
                'sometimes',
                'confirmed',
                ...$password_rules,
            ],
            'username'                 => [
                'required',
                'unique:sys_users,username,' . $user->id,
            ],
            'email'                 => [
                'required',
                'email:filter',
                'unique:sys_users,email,' . $user->id,
            ],
        ];
        if ($user->id != 1) {
            $rules['org_struct_id'] = 'required';
            $rules['position_id']   = 'required';
            $rules['groups']        = 'required';
        }
        $request->validate($rules);
        return $user->updateByRequest($request);
    }

    public function destroy($id)
    {
        $record = User::find($id);
        return $record->deleteByRequest();
    }

    public function selectAjax(Request $request)
    {
        return User::selectAjax();
    }

    public function selectAjaxUserDivisiHC(Request $request)
    {
        return User::selectAjaxUserDivisiHC($request);
    }
}
