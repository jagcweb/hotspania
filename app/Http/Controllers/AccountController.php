<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\ImageLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Package;
use App\Models\PackageUser;
use App\Models\PackageUserHistory;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('logged')->except(['get', 'loadMore', 'show', 'like', 'checkLike', 'removeLike']);
    }

    public function makeUnavailable($id) {
        try {
            $decryptedId = \Crypt::decryptString($id);
        } catch (\Exception $e) {
            return back()->with('error', 'ID inválido');
        }

        $user = User::find($decryptedId);
        
        if(!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $user->update([
            'available_time' => NULL,
            'available_until' => NULL
        ]);

        return back()->with('exito', 'Disponibilidad apagada.');
    }

    public function makeAvailable(Request $request, $id) {
        try {
            $decryptedId = \Crypt::decryptString($id);
        } catch (\Exception $e) {
            return back()->with('error', 'ID inválido');
        }

        $validator = Validator::make($request->all(), [
            'tiempo' => 'required|integer|min:1|max:3'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Datos inválidos');
        }

        $user = User::find($decryptedId);
        
        if(!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $user->update([
            'available_time' => $request->tiempo,
            'available_until' => \Carbon\Carbon::now('Europe/Madrid')->addHours($request->tiempo)
        ]);

        return back()->with('exito', 'Marcado como disponible por ' . $request->tiempo . ' horas');
    }

    public function assignPackage(Request $request) {
        $user_id = \Auth::user()->id;
        $package = Package::findOrFail($request->get('package_id'));
        
        // Obtener el último paquete del usuario
        $lastPackage = PackageUser::where('user_id', $user_id)
            ->orderBy('end_date', 'desc')
            ->first();

        $startDate = null;
        if ($lastPackage && $lastPackage->end_date > now()) {
            // Si hay un paquete activo, el nuevo empezará cuando termine el último
            $startDate = $lastPackage->end_date;
        } else {
            // Si no hay paquetes activos, empieza hoy
            $startDate = now()->startOfDay();
        }

        $pack_user = new PackageUser();
        $pack_user->package_id = $package->id;
        $pack_user->user_id = $user_id;
        $pack_user->start_date = $startDate;
        $pack_user->end_date = $startDate->copy()->addDays($package->days);
        $pack_user->save();

        // Guardar en historial
        $pack_history = new PackageUserHistory();
        $pack_history->package_id = $package->id;
        $pack_history->user_id = $user_id;
        $pack_history->save();

        return back()->with('exito', 'Paquete asignado.');
    }


    public function visibleAccount($id) {
        $id = \Crypt::decryptString($id);
        $user = User::find($id);
        $user->visible = $user->visible == 1 ? null : 1;
        $user->updated_at = \Carbon\Carbon::now();
        $user->update();

        $message = $user->visible == 1 ? 'Cuenta visible.' : 'Cuenta oculta.';
        return back()->with('exito', $message);
    }

    public function show($id) {
        $image = Image::find($id);

        $image->visits = $image->visits + 1;
        $image->save();
        
        if (!$image) {
            return response()->json([
                'success' => false, 
                'message' => 'Imagen no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'visits' => $image->visits ?? 0
        ]);
    }

    public function addVisitProfile($id) {
        $id = \Crypt::decryptString($id);
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $user->increment('visits');

        return response()->json([
            'success' => true,
            'visits' => $user->visits ?? 0,
        ]);
    }

    public function like($id) {
        $image = Image::find($id);
        
        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Imagen no encontrada'
            ], 404);
        }

        $hasLiked = false;
        if (Auth::check()) {
            $existingLike = ImageLike::where('image_id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$existingLike) {
                ImageLike::create([
                    'image_id' => $id,
                    'user_id' => Auth::id()
                ]);
                $hasLiked = true;
            }
        } else {
            $guestId = Cookie::get('hotspania_session');
            ImageLike::create([
                'image_id' => $id,
                'guest_id' => $guestId
            ]);
            $hasLiked = true;
        }

        if ($hasLiked) {
            $image->increment('likes');
        }

        return response()->json([
            'success' => true,
            'likes' => $image->likes,
            'hasLiked' => true,
            'isAuthenticated' => Auth::check()
        ]);
    }

    public function checkLike($id)
    {
        if (Auth::check()) {
            $hasLiked = ImageLike::where('image_id', $id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return response()->json(['hasLiked' => $hasLiked]);
    }

    public function removeLike($id)
    {
        $image = Image::find($id);
        
        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Imagen no encontrada'
            ], 404);
        }

        if (Auth::check()) {
            ImageLike::where('image_id', $id)
                ->where('user_id', Auth::id())
                ->delete();
        } else {
            $guestId = Cookie::get('hotspania_session');
            ImageLike::where('image_id', $id)
                ->where('guest_id', $guestId)
                ->delete();
        }

        $image->decrement('likes');

        $totalLikes = ImageLike::where('image_id', $id)->count();

        return response()->json([
            'success' => true,
            'likes' => $totalLikes,
            'hasLiked' => false,
            'isAuthenticated' => Auth::check()
        ]);
    }

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
            'nickname' => 'required|string|max:255|unique:users,nickname,' . \Auth::id() . ',id',
            'whatsapp' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'smoker' => 'required|boolean',
            'city' => 'required|array',
            'city.*' => 'exists:cities,id',
            'working_zone' => 'required|string|max:255',
            'service_location' => 'required|array',
            'service_location.*' => 'required|string|in:piso_propio,domicilio,hotel',
            'gender' => 'required|in:mujer,hombre,lgbti',
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

        return redirect()->route('account.edit')->with('exito', 'Perfil actualizado correctamente.');
    }

    public function get($nickname) {
        $user = User::where('nickname', $nickname)->first();

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        $images = Image::where('user_id', $user->id)
                      ->where('status', 'approved')
                      ->whereNotNull('visible')
                      ->orderBy('created_at', 'desc')
                      ->get();

        $frontimage = Image::where('user_id', $user->id)
                          ->whereNotNull('frontimage')
                          ->whereNotNull('route_frontimage')
                          ->first();

        \Log::info('Total images for user ' . $user->id . ': ' . $images->count());

        // Obtener los likes para todas las imágenes
        $likedImages = [];
        if (Auth::check()) {
            $likedImages = ImageLike::where('user_id', Auth::id())
                ->whereIn('image_id', $images->pluck('id'))
                ->pluck('image_id')
                ->toArray();
        }

        return view('account.get', [
            'user' => $user,
            'images' => $images,
            'frontimage' => $frontimage,
            'likedImages' => $likedImages
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

         \Log::info("Procesando imagen: {$imageName}");

            \Storage::disk('temp_img_ia')->put($imageName, \File::get($file));
            $tempImagePath = storage_path('app/public/temp_img_ia/' . $imageName);

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            if ($isWindows) {
                $python = 'python';
                $predictorPath = 'C:/xampp/htdocs/ProyectosFreelance/Hotspania/body_face_nsfw_models/models/app/predictor.py';
                $command = "$python " . escapeshellarg($predictorPath) . ' ' . escapeshellarg($tempImagePath);
                $command2 = "$python " . escapeshellarg($predictorPath) . ' ' . escapeshellarg($tempImagePath) . ' --blur_faces';
                } else {
                    $activate = 'source /var/www/hotspania/body_face_nsfw_models/models/app/venv/bin/activate';
                    $script = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($tempImagePath) . ' 2>/dev/null';
                    $command = "bash -c '$activate && $script'";
                    $script2 = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($tempImagePath) . ' --blur_faces 2>/dev/null';
                    $command2 = "bash -c '$activate && $script2'";
                }


            \Log::debug("Comando ejecutado: $command");

            $output = shell_exec($command);

            \Log::debug("Salida del script Python: $output");

            $combinedResult = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error("Error al decodificar JSON: " . json_last_error_msg());
                \Log::debug("Salida cruda del script Python: [$output]");
                $combinedResult = ['error' => 'JSON parse failed', 'raw_output' => $output];
            }

            // Si se debe ocultar el rostro
            if (!is_null($request->get('hide_face'))) {
                \Log::info("Hide face detected for image: {$imageName}");
                \Log::debug("Comando2 ejecutado: $command2");
                $output2 = shell_exec($command2);
                \Log::debug("Salida del script2 de Python: $output2");

                // Verificar si existe la imagen difuminada y reemplazar $file
                $ext = '.' . $extension;
                $blurredPath = str_replace($ext, '_blurred' . $ext, $tempImagePath);

                if (file_exists($blurredPath)) {
                    $file = new \Illuminate\Http\UploadedFile(
                        $blurredPath,
                        $imageName,
                        $mimeType,
                        null,
                        true // marcar como archivo de prueba
                    );
                    \Log::info("Imagen con rostros ocultos usada para la marca de agua: {$imageName}");
                } else {
                    \Log::warning("No se encontró la imagen difuminada esperada en: $blurredPath");
                }
            }

            \Storage::disk('temp_img_ia')->delete($imageName);
            \Log::info("Archivo temporal eliminado: {$imageName}");

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

    public function loadMore($page, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        $perPage = 8;
        $offset = ($page - 1) * $perPage;
        
        $images = Image::where('user_id', $userId)
                      ->where('status', 'approved')
                      ->whereNotNull('visible')
                      ->orderBy('created_at', 'desc')
                      ->skip($offset)
                      ->take($perPage)
                      ->get();

        $totalImages = Image::where('user_id', $userId)
                           ->where('status', 'approved')
                           ->whereNotNull('visible')
                           ->count();

        $hasMore = ($offset + $perPage) < $totalImages;

        $html = view('account.partials.gallery-items', ['images' => $images])->render();
        
        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'debug' => [
                'page' => $page,
                'offset' => $offset,
                'count' => $images->count(),
                'total' => $totalImages
            ]
        ]);
    }
}
