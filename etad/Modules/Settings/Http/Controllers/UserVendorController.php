<?php

namespace Modules\Settings\Http\Controllers;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserVendorController extends Controller
{
    protected $module = 'settings.user-vendor';
    protected $route  = 'settings.user-vendor';
    protected $view   = 'settings::user-vendor';
    protected $perms  = 'setting';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Users Vendor',
                'breadcrumb' => [
                    'Konfigurasi' => '/',
                    'Users Vendor' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = User::with('vendor')->hasVendor()
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['name', 'email'], $keyword);
                }
            )->when(
                $keyword_vendor = request()->vendor_id,
                function ($q) use ($keyword_vendor) {
                    $q->where('vendor_id', $keyword_vendor);
                }
            );
        if ($order_column == env('APP_HC_ID')) {
            $records->with(
                [
                    'vendor' => function ($q) use ($order_dir) {
                        $q->orderBy('nama', $order_dir);
                    }
                ]
            );
        } else {
            $records->orderBy('updated_at', 'DESC');
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
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
                'vendor.nama',
                function ($r) {
                    return (($vendor = $r->vendor) ? $vendor->nama : '-');
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
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"';
                    }
                    return $this->makeDropdown($buttons);
                    // $buttons = '';
                    // $buttons .= $this->makeDropdown(
                    //     [
                    //         'type:show|id:' . $r->id,
                    //         'type:edit|id:' . $r->id,
                    //         'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                    //     ]
                    // );
                    // $buttons .= $this->makeDropdown(
                    //     [
                    //         'type' => 'upgrade',
                    //         'icon' => 'fa fa-sync text-warning',
                    //         'label' => 'Reset Password',
                    //         'url' => route($this->route . '.getUpgrade', $r->id),
                    //         'id'   => $r->id,
                    //         'attrs' => 'data-swal-text="Reset password akan mengubah password menjadi: qwerty123456"',
                    //     ]
                    // );
                    // return $buttons;
                }
            )
            ->rawColumns(
                [
                    'name',
                    'username',
                    'vendor.nama',
                    'action', 'created_at', 'role', 'email'
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
                        $this->makeColumn('name:name|label:Nama|sortable:false|className:text-left'),
                        $this->makeColumn('name:username|label:Username|sortable:false'),
                        $this->makeColumn('name:email|label:Email|sortable:false'),
                        $this->makeColumn('name:vendor.nama|label:Vendor|sortable:false'),
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
        return $this->render(
            $this->view . '.create',
            [
                'roles' => Role::orderBy('name', 'asc')->get()
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
                    $msg[] = 'mininal 8 karakter';
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
                'email'         => ['required', 'email:filter', 'unique:sys_users,email'],
                'name'          => ['required'],
                'username'      => [
                    'required',
                    'unique:sys_users,username',
                ],
                'vendor_id'     => ['required'],
                // 'groups' => ['required'],
                'password'      => [
                    'required',
                    'confirmed',
                    ...$password_rules
                ],
                'password_confirmation' => [
                    'required',
                    ...$password_rules
                ]
            ]
        );
        return User::createByRequest($request);
    }

    public function show($id)
    {
        $user_vendor = User::findOrFail($id);
        return $this->render(
            $this->view . '.show',
            [
                'record' => $user_vendor,
            ]
        );
    }

    public function edit(User $user_vendor)
    {
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $user_vendor,
                'roles' => Role::orderBy('name', 'asc')->get()
            ]
        );
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

    public function update(Request $request, User $user_vendor)
    {
        $password_rules = [
            function ($attribute, $value, $fail) {
                $str = 'password harus memiliki: ';
                $msg = [];
                if (strlen($value) < 8) {
                    $msg[] = 'mininal 8 karakter';
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
                // 'email'    => ['required', 'email:filter', 'unique:sys_users,email,' . $user_vendor->id],
                'name'     => ['required'],
                // 'username'     => ['required'],
                'vendor_id' => ['required'],
                // 'groups'    => ['required'],
                'password' => [
                    'sometimes',
                    'confirmed',
                    ...$password_rules
                ],
            ]
        );
        return $user_vendor->updateByRequest($request);
    }

    public function destroy(User $user_vendor)
    {
        return $user_vendor->deleteByRequest();
    }
}
