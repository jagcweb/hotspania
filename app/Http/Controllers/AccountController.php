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

        return view('account.index', [
            'images' => $images
        ]);
    }

    public function edit()
    {
        $cities = City::orderBy('name', 'asc')->get();

        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->get();

        return view('account.edit', [
            'images' => $images
        ]);
    }

    public function get($nickname)
    {
        $user = User::where('nickname', $nickname)->first();

        $images = Image::where('user_id', $user->id)->where('status', 'approved')->whereNotNull('visible')->get();

        return view('account.get', [
            'user' => $user,
            'images' => $images,
        ]);
    }
}
