<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user)
    {
       /* if (is_null($user->email_verified_at)) {
            \Auth::logout();

            return back()->with('error', 'Cuenta de email aún no verificada.');
        }

        if (!is_null($user->banned)) {
            \Auth::logout();

            return back()->with('error', 'El usuario ha sido baneado. Si crees que es un error ponte en contacto con nosotros.');
        }*/

        if ($user->getRoleNames()[0] == 'admin') {
            return redirect()->route('admin.citychanges');
        } else {
            if (!is_null($user->banned)) {
                \Auth::logout();
                return back()->with('error', 'El usuario ha sido baneado. Si crees que es un error ponte en contacto con nosotros.');
            } else {
                return redirect()->route('home');
            }

        }

    }
}
