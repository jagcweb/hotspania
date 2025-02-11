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
        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->whereNotNull('visible')->paginate(9);

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        return view('account.index', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function edit()
    {
        $cities = City::orderBy('name', 'asc')->get();

        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->paginate(9);

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        return view('account.edit', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function get($nickname) {
        $user = User::where('nickname', $nickname)->first();

        $images = Image::where('user_id', $user->id)->where('status', 'approved')->whereNotNull('visible')->paginate(9);

        $frontimage = Image::where('user_id', $user->id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        return view('account.get', [
            'user' => $user,
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function setFront($id) {

        $image = Image::findOrFail($id);

        $last_front = Image::where('user_id', $image->user_id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        if(is_object($last_front)) {
            $last_front->frontimage = NULL;
            $last_front->updated_at = \Carbon\Carbon::now();
            $last_front->update();
        }

        // Initialize the ImageManager with the GD driver (explicitly using GD)
        $manager = new ImageManager(new Driver());

        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($image->route);

        // Read the uploaded image
        $imageReader = $manager->read($file);

        
        // Get the dimensions of the original image
        $imageWidth = $imageReader->width();
        $imageHeight = $imageReader->height();

        if($imageWidth > $imageHeight) {
            $imageReader->resize(250, 166, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        } else {
            $imageReader->resize(166, 250, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        }

        $imageContent = $imageReader->toJpeg(70);

        $newFileName = 'front-'.$image->route;

        \Storage::disk(StorageHelper::getDisk('images'))->put($newFileName, $imageContent);
       
        $image->frontimage = 1;
        $image->route_frontimage = $newFileName;
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
