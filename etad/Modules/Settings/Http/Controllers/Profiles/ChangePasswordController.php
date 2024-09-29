<?php

namespace Modules\Settings\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Rules\AuthPassword;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    protected $module = 'settings';
    protected $route  = 'settings.change-password';
    protected $view   = 'settings::change-password';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Ganti Password',
                'breadcrumb' => [
                    'Ganti Password' => '/',
                ]
            ]
        );
    }

    public function index()
    {
        return $this->render($this->view . '.index');
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
        auth()->user()->storeLog(
            'change-password',
            'update',
        );
        $request->validate(
            [
                'old_password' => [
                    'required',
                ],
                'new_password' => [
                    'required',
                    'confirmed',
                    ...$password_rules,
                ],
            ]
        );
        return auth()->user()->changePassByRequest($request);
    }
}
