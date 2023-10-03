<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        if(!\Auth::user() || \Auth::user()->getRoleNames()[0] != "admin"){
            return back();
        }
    }

    public function get(){
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name', '!=', 'admin');
            }
        )->orderBy('id', 'asc')->get();

        return view('admin.users.get', [
            'users' => $users
        ]);
    }

    public function update(Request $request, $id)
    {

        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'surname' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,'. $id],
        ]);

        $name = $request->get('name');
        $surname = $request->get('surname');
        $email = $request->get('email');

        $user = User::find($id);

        $user->name = $name;
        $user->surname = $surname;
        $user->email = $email;
        $user->update();

        return back()->with('exito', 'Datos usuario '.$user->email. ' actualizados!');
    }

    public function ban($id)
    {
        $user = User::find($id);

        if(is_null($user->banned)){
            $user->banned = 1;
            $text = "baneado";
        } else {
            $user->banned = NULL;
            $text = "desbaneado";
        }

        $user->update();

        return back()->with('exito', 'Usuario '.$user->email. " " .$text);
    }

    public function verify($id)
    {
        $user = User::find($id);

        if(is_null($user->email_verified_at)){
            $user->email_verified_at = \Carbon\Carbon::now();
            $text = "verificado";
        } else {
            $user->email_verified_at = NULL;
            $text = "desverificado";
        }

        $user->update();

        return back()->with('exito', 'Email '. $user->email. ' de usuario '.$text);
    }
}
