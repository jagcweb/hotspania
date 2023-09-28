<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('getImage');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getImage($filesystem, $filename) {
        $file = \Storage::disk($filesystem)->get($filename);

        $headers = array(
            'Content-Type' => 'image/jpg'
        );

        return new Response($file, 200);
    }
}
