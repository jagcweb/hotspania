<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\StorageHelper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['getImage']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name', 'user');
            })
            ->whereNotNull('active')
            ->whereNotNull('completed')
            ->whereNull('banned')
            ->with('images')
            ->inRandomOrder()
            ->get();

        return view('home' , [
            'users' => $users
        ]);
    }

    public function privacyPolicies()
    {
        return view('privacy_policies');
    }

    public function getImage($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($filename);

        return new Response($file, 200);
    }

    public function getFrontImage($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($filename);

        return new Response($file, 200);
    }

    public function getGif($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('videogif'))->get($filename);

        return new Response($file, 200);
    }
}
