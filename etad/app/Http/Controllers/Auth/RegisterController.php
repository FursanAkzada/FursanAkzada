<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Sys\SysUserPerusahaan as SysSysUserPerusahaan;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Entities\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Master\Entities\Perusahaan;
use SysUserPerusahaan;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:sys_users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create(
            [
                'name'      => $data['name'],
                'email'     => $data['email'],
                'is_user'   => 0,
                'password'  => Hash::make($data['password']),
            ]
        );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all())->validate();

        // return [
        //     'register@create',
        //     $request->all(),
        //     $request->post('company'),
        //     $validator,
        // ];
        $event = event(new Registered($user = $this->create($request->all())));

        $perusahaanDto = $request->post('company');
        $perusahaanDto['status'] = 1;
        $newPerusahaan = new Perusahaan($perusahaanDto);
        $newPerusahaan->save();


        $user_perusahaan = new SysSysUserPerusahaan(
            [
                'user_id'   => $user->id,
                'perusahaan_id'   => $newPerusahaan->id,
            ]
        );
        $user_perusahaan->save();

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
}
