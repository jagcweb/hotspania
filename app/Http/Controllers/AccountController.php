<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index(){
        return view('account.index');
    }

    public function update(Request $request)
    {
        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,'. \Auth::user()->id],
        ]);

        $name = $request->get('name');
        $surname = $request->get('surname');
        $email = $request->get('email');

        $user = User::find(\Auth::user()->id);

        $user->name = $name;
        $user->surname = $surname;
        $user->email = $email;
        $user->update();

        return back()->with('exito', 'Datos actualizados!');

    }

    public function updatePassword(Request $request)
    {
        $validate = $this->validate($request, [
            'current' => ['required', 'string', 'min:8'],
            'new' => ['required', 'string', 'min:8'],
        ]);

        $current = $request->get('current');
        $new = $request->get('new');

        if(!\Hash::check($current, \Auth::user()->password)){
            return back()->with("error", "La contraseña actual no coincide con la introducida.");
        }

        $user = User::find(\Auth::user()->id);

        $user->password = \Hash::make($new);
        $user->update();

        return back()->with('exito', 'Contraseña actualizada!');

    }
}
