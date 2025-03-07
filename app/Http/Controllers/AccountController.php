<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\City;
use App\Models\Image;
use App\Http\Controllers\Controller;
use App\Helpers\StorageHelper;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Vision\V1\Image as GoogleImage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use App\Jobs\ProcessVideoUpload;
use CV\Mat;
use CV\Imgcodecs;
use CV\Size;
use \App\Models\CityUser;
use CV\Opencv;
use Google\Cloud\AIPlatform\V1\PredictionServiceClient;
use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;

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
    public function index(){
        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->whereNotNull('visible')->paginate(9);

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        return view('account.index', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function edit(){
        $cities = City::orderBy('name', 'asc')->get();

        $images = Image::where('user_id', \Auth::user()->id)->where('status', 'approved')->paginate(9);

        $frontimage = Image::where('user_id', \Auth::user()->id)->whereNotNull('frontimage')->whereNotNull('route_frontimage')->first();

        return view('account.edit', [
            'images' => $images,
            'frontimage' => $frontimage
        ]);
    }

    public function editData(){
        $cities = City::orderBy('name', 'asc')->get();

        return view('account.edit-data', [
            'cities' => $cities
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'nickname' => 'required|string|max:255|unique:users,nickname,' . \Auth::id(),
            'date_of_birth' => 'required|date',
            'whatsapp' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'smoker' => 'required|boolean',
            'city' => 'required|array',
            'city.*' => 'exists:cities,id',
            'working_zone' => 'required|string|max:255',
            'service_location' => 'required|in:piso_propio,domicilio,hotel',
            'gender' => 'required|in:mujer,hombre,lgbti+',
            'height' => 'required|integer|min:100|max:250',
            'weight' => 'required|integer|min:30|max:200',
            'bust' => 'nullable|integer|min:30|max:200',
            'waist' => 'nullable|integer|min:30|max:200',
            'hip' => 'nullable|integer|min:30|max:200',
            'start_day' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo,fulltime',
            'end_day' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo,fulltime',
            'fulltime_time' => 'nullable|boolean',
            'start_time' => 'required_without:fulltime_time|nullable|integer|min:0|max:23',
            'end_time' => 'required_without:fulltime_time|nullable|integer|min:0|max:23',
            'link' => 'nullable|url'
        ]);

        $user = \Auth::user();

        $cities_selected = CityUser::where('user_id', $user->id)->get();

        foreach($cities_selected as $city_selected){
            $city_selected->delete();
        }

        foreach($request->get('city') as $city){
            $city_user = new CityUser();
            $city_user->city_id = $city;
            $city_user->user_id = \Auth::user()->id;
            $city_user->save();
        }

        
        $user->update([
            'nickname' => $request->nickname,
            'whatsapp' => $request->whatsapp,
            'phone' => $request->phone,
            'is_smoker' => $request->smoker,
            'working_zone' => $request->working_zone,
            'service_location' => $request->service_location,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'height' => $request->height,
            'weight' => $request->weight,
            'bust' => $request->bust,
            'waist' => $request->waist,
            'hip' => $request->hip,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'start_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->start_time,
            'end_time' => !is_null($request->get('fulltime_time')) ? 'fulltime' : $request->end_time,
            'link' => $request->link,
        ]);

        return redirect()->back()->with('exito', 'Perfil actualizado correctamente.');
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

    public function upload(Request $request) {
        $files = $request->file('images');

        $videoMimeTypes = [
            'video/mp4',
            'video/quicktime',  // .mov
            'video/x-msvideo',  // .avi
            'video/x-ms-wmv',    // .wmv
            'video/webm',        // .webm
            'video/x-flv'        // .flv
        ];    

        // Process each file
        foreach ($files as $file) {
            // Generate a unique name for the image
            $imageName = time() . '_' . bin2hex(random_bytes(10)) . '.' . $file->getClientOriginalExtension();


            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            \Storage::disk('temp_img_ia')->put($imageName, \File::get($file));

            $imageData = \Storage::disk('temp_img_ia')->get($imageName);

            //$visionResult = $this->analyzeImageWithVisionAI($imageData);
            //$vertexAIResult = $this->analyzeImageWithVertexAI($imageData);
            //$opencvResult = $this->processImageWithOpenCV($imageData);
            

            $combinedResult = [
                //'vision' => $visionResult,
                //'vertex' => $vertexAIResult,
                //'opencv' => $opencvResult,
            ];

            \Storage::disk('temp_img_ia')->delete($imageName);

            // Initialize the ImageManager with the GD driver (explicitly using GD)
            $manager = new ImageManager(new Driver());

            $this->addWaterMark($file, $imageName, $extension, false);

            $imageModel = new Image(); // Assuming you have an Image model
            $imageModel->user_id = \Auth::user()->id;
            $imageModel->route = $imageName;
            $imageModel->route_gif = $gifName ?? NULL;
            $imageModel->size = round($file->getSize() / 1024, 2);
            $imageModel->type = "images";
            $imageModel->status = 'pending'; // Set initial status to pending
            $imageModel->watermarked = 1;
            $imageModel->visible = 1;
            $imageModel->vision_data = json_encode($combinedResult);
            $imageModel->save();
        }

        //return back()->with('success', 'Images uploaded with watermark pattern successfully!');
    }

    public function addWaterMark($file, $imageName, $extension, bool $generatedGif){
        // Initialize the ImageManager with the GD driver (explicitly using GD)
        $manager = new ImageManager(new Driver());

        // Read the uploaded image
        $image = $manager->read($file);

        
        // Get the dimensions of the original image
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        if($imageWidth > $imageHeight) {
            $image->resize(1200, 800, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        } else {
            $image->resize(800, 1200, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        }

        // Path to the watermark image (the image you want to use as a pattern)
        $watermarkPath = public_path('images/unique_marca_agua.png');  // Adjust the path as needed

        // Read the watermark image
        $watermark = $manager->read($watermarkPath);

        $watermark->resize(234, 166);
        
        // Colocar el watermark en el centro
        $image->place($watermark, 'center');

        // Save the image to the storage path (app/public/images)
        // Save the image with watermark to the storage path

        if($generatedGif) {
            $disk = StorageHelper::getDisk('videogif');
            $imageContent = $image->toGif();
            $filePath = 'videogif/' . $imageName;
            \Storage::disk($disk)->put($imageName, $imageContent, 'private');
            //$image->toGif()->save(storage_path('app/public/videogif/' . $imageName));
        } else {
            if ($extension === 'gif' || strpos($extension, 'gif') !== false) {
                $disk = StorageHelper::getDisk('images');
                $imageContent = $image->toGif();
                $filePath = 'images/' . $imageName;
                \Storage::disk($disk)->put($imageName, $imageContent, 'private');
                //$image->toGif()->save(storage_path('app/public/images/' . $imageName));
            } else {
                $disk = StorageHelper::getDisk('images');
                $imageContent = $image->toJpeg(70);
                $filePath = 'images/' . $imageName;
                \Storage::disk($disk)->put($imageName, $imageContent, 'private');
                //$image->toJpeg(70)->save(storage_path('app/public/images/' . $imageName));
            }
        }

    }


    public function setFront($id) {
        $id = \Crypt::decryptString($id);
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
        
        return back()->with('exito', 'Imagen como portada.');
    }

    public function visible($id){
        $id = \Crypt::decryptString($id);
        $image = Image::findOrFail($id);
        $image->visible = 1;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen visible.');
    }

    public function invisible($id){
        $id = \Crypt::decryptString($id);
        $image = Image::findOrFail($id);
        $image->visible = NULL;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen oculta.');
    }

    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $images = Image::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->skip(($page - 1) * 8)
                      ->take(8)
                      ->get();

        return view('account.partials.gallery-items', compact('images'));
    }
}
