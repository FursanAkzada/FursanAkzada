<?php

namespace App\Http\Controllers\Auth;

use App\Entities\EHC\User as UserEHC;
use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function attemptLogin(Request $request)
    {
        // return 11;
        // dd($request->all());
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
                'captcha' => 'required|simple_captcha',
            ],
            [
                'captcha' => 'Captcha',
            ],
            [
                'captcha' => 'Captcha',
            ]
        );

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$fieldType => $request->email]);

        // $userEHC =  UserEHC::where(
        //     [
        //         'USER_LOG' => $request->email,
        //         'PASS_LOG' => md5($request->password),
        //     ]
        // )->first();
        // if ($userEHC) {
        //     $user = User::where('username', $request->email)->first();
        //     if ($user && $user->password != md5($request->password)) {
        //         $credentials = $request->only($fieldType, 'password');
        //         if (Auth::attempt($credentials)) {
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     } else {
        //         $user = User::firstOrNew(['username' => $request->email]);
        //     }
        //     $user->fill(
        //         [
        //             'password' => bcrypt($request->password),
        //             'user_type' => 'ehc',
        //             'kd_log' => $userEHC->KD_LOG,
        //             'name' => $userEHC->NM_USER,
        //             'email' => $userEHC->USER_LOG . '@email.com',
        //         ]
        //     );
        //     $user->save();
        //     Auth::login($user);
        //     return true;
        // } else {
        //     $credentials = $request->only($fieldType, 'password');
        //     if (Auth::attempt($credentials)) {
        //         auth()->user()->storeLog('auth.login', 'login');
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
        $credentials = $request->only($fieldType, 'password');
            if (Auth::attempt($credentials)) {
                auth()->user()->storeLog('auth.login', 'login');
                return true;
            } else {
                return false;
            }
    }

    public function logout(Request $request)
    {
        /* Audit Trail */
        // $user = $this->guard()->user();
        // $user->storeLog('auth', 'logout');

        /* Logout */
        // Auth::logout();
        // return redirect('/');
        auth()->user()->storeLog(
            'auth.logout',
            'logout',
        );

        $this->guard()->logout();

        $remember_username = session('remember_username');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session(['remember_username' => $remember_username]);

        if ($response = $this->loggedOut($request)) {
            // $request->merge(['module' => 'auth_login']);
            // auth()->user()->addLog('Logout berhasil');
            auth()->user()->storeLog('auth.logout', 'logout');
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                $this->username() => 'required|string',
                'password' => 'required|string',
                'captcha' => 'required|simple_captcha',
            ],
            [
                'captcha' => 'Captcha',
            ],
            [
                'captcha' => 'Captcha',
            ]
        );
    }
}
