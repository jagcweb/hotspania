<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\City;
use App\Models\Image;


class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('logged')->except('get');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->whereNotNull('visible')->get();

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->first();

        return view('account.index', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function edit()
    {
        $cities = City::orderBy('name', 'asc')->get();

        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->get();

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->first();

        return view('account.edit', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function get($nickname)
    {
        $user = User::where('nickname', $nickname)->first();

        $images = Image::where('user_id', $user->id)->where('status', 'approved')->whereNotNull('visible')->get();

        $frontimage = Image::where('user_id', $user->id)->whereNotNull('frontimage')->first();

        return view('account.get', [
            'user' => $user,
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function setFront($id) {
        $id = \Crypt::decryptString($id);
        $image = Image::findOrFail($id);

        $last_front = Image::where('user_id', $image->user_id)->whereNotNull('frontimage')->first();

        if(is_object($last_front)) {
            $last_front->frontimage = NULL;
            $last_front->updated_at = \Carbon\Carbon::now();
            $last_front->update();
        }

       
        $image->frontimage = 1;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();
        
        return redirect()->back()->with('exito', 'Imagen como portada.');
    }

    public function visible($id)
    {
        $id = \Crypt::decryptString($id);
        $image = Image::findOrFail($id);
        $image->visible = 1;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen visible.');
    }

    public function invisible($id)
    {
        $id = \Crypt::decryptString($id);
        $image = Image::findOrFail($id);
        $image->visible = NULL;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen oculta.');
    }
}
