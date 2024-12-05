<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;  
use App\Models\User;  
use Illuminate\Http\Response;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessVideoUpload;

class ImageController extends Controller
{

    public function get($id, $name, $filter) {
        switch($filter) {
            case 'pendientes':
                $images = Image::where('user_id', $id)->where('status', 'pending')->orderBy('id', 'desc')->get();
                break;
            case 'aprobadas':
                $images = Image::where('user_id', $id)->where('status', 'approved')->orderBy('id', 'desc')->get();
                break;
            case 'desaprobadas':
                $images = Image::where('user_id', $id)->where('status', 'unapproved')->orderBy('id', 'desc')->get();
                break;
            case 'visible':
                $images = Image::where('user_id', $id)->whereNotNull('visible')->orderBy('id', 'desc')->get();
                break;
            case 'ocultas':
                $images = Image::where('user_id', $id)->whereNull('visible')->orderBy('id', 'desc')->get();
                break;
            case 'todas':
                $images = Image::where('user_id', $id)->orderBy('id', 'desc')->get();
                break;
            default:
                $images = Image::where('user_id', $id)->orderBy('id', 'desc')->get();
                break;
        }

        return view('admin.images.get', [
            'id' => $id,
            'name' => $name,
            'filter' => $filter,
            'images' => $images
        ]);
    }

    public function setFront($id) {

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


    public function upload(Request $request) {
        Log::info('Solicitud de carga recibida:', $request->all());
        // Validate the request data
        /*$request->validate([
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,avchd,webm,flv|max:10240',
            'user_id' => 'required|integer',
        ]);*/

        // Get the uploaded files
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
            $imageName = time() . '_' . $file->getClientOriginalName();

            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            // Initialize the ImageManager with the GD driver (explicitly using GD)
            $manager = new ImageManager(new Driver());

            if (in_array($mimeType, $videoMimeTypes)) {
                $videoName = time() . '_' . $file->getClientOriginalName();
                \Storage::disk('images')->put($videoName, \File::get($file));

                $videoPath = storage_path('app/public/images/' . $videoName);

                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Windows system
                    $ffmpegPath = 'C:/ProgramData/chocolatey/lib/ffmpeg/tools/ffmpeg/bin/ffmpeg.exe';
                    $ffprobePath = 'C:/ProgramData/chocolatey/lib/ffmpeg/tools/ffmpeg/bin/ffprobe.exe';
                } else {
                    // Linux/Ubuntu system
                    $ffmpegPath = '/usr/bin/ffmpeg';  // Adjust this based on your server
                    $ffprobePath = '/usr/bin/ffprobe'; // Adjust this based on your server
                }

                // Initialize FFProbe to get video details
                $ffprobe = FFProbe::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);
                $duration = (int) $ffprobe->format($videoPath)->get('duration');
                
                // Get video dimensions
                $dimensions = $ffprobe->streams($videoPath)->videos()->first()->getDimensions();
                $width = $dimensions->getWidth();
                $height = $dimensions->getHeight();
    
                // Set path for GIF output
                $gifName = pathinfo($videoName, PATHINFO_FILENAME) . '.gif';
                $gifPath = 'videogif/' . $gifName; // Save GIF in 'gifs' folder
    
                // Initialize FFmpeg to convert video to GIF
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);
                $ffmpegVideo = $ffmpeg->open($videoPath);

                $watermarkPath = public_path('images/unique_marca_agua.png');

                $ffmpegVideo->filters()->watermark($watermarkPath, [
                    'position' => 'relative',  // Position watermark relative to video size
                    'x' => 10,  // Horizontal position of the watermark (in pixels)
                    'y' => 10,  // Vertical position of the watermark (in pixels)
                    'opacity' => 0.5,  // Set opacity of the watermark (0 to 1)
                ]);

                $ffmpegFormat = new X264('libmp3lame', 'libx264');

                $imageName = 'enconded_' . $videoName;
                
                $outputVideoPath = storage_path('app/public/images/' . $imageName);
                $outputGifPath = storage_path('app/public/videogif/' . $gifName);

                // Comando para procesar el video
                $command = $ffmpegPath . ' -i "' . $videoPath . '" -i "' . $watermarkPath . '" -filter_complex "[0:v][1:v]overlay=x=(W-w)/2:y=(H-h)/2" -c:v libx264 -preset ultrafast -crf 28 -c:a aac -strict experimental -y "' . $outputVideoPath . '"';
                exec($command);

                // Comando para generar el GIF
                $gifCommand = $ffmpegPath . ' -i "' . $outputVideoPath . '" -i "' . $watermarkPath . '" -filter_complex "[0][1]overlay=10:10,fps=10,scale=320:-1" -t 5 -y "' . $outputGifPath . '"';
                exec($gifCommand);

                /*$job = new ProcessVideoUpload($ffmpegPath, $videoPath, $watermarkPath, $outputVideoPath, $outputGifPath);
                $job->handle();*/
            } else {
                $this->addWaterMark($file, $imageName, $extension, false);
            }


            // Assuming you have an Image model to store this image information in the database
            $imageModel = new Image(); // Assuming you have an Image model
            $imageModel->user_id = $request->input('user_id');
            $imageModel->route = $imageName;
            $imageModel->route_gif = $gifName ?? NULL;
            $imageModel->size = round($file->getSize() / 1024, 2);
            $imageModel->type = "images";
            $imageModel->status = 'approved'; // Set initial status to pending
            $imageModel->watermarked = 1;
            $imageModel->save();
        }

        return back()->with('success', 'Images uploaded with watermark pattern successfully!');
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
            $image->resize(533, 355, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        } else {
            $image->resize(355, 533, function ($constraint) {
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
            $image->toGif()->save(storage_path('app/public/videogif/' . $imageName));
        } else {
            if ($extension === 'gif' || strpos($extension, 'gif') !== false) {
                $image->toGif()->save(storage_path('app/public/images/' . $imageName));
            } else {
                $image->toPng()->save(storage_path('app/public/images/' . $imageName));
            }
        }

    }

    public function addWaterMarkApprove($imageModel) {
        $manager = new ImageManager(new Driver());

        $file = storage_path('app/public/images/' . $imageModel->route);

        $image = $manager->read($file);
        // Get the dimensions of the original image
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        if($imageWidth > $imageHeight) {
            $image->resize(533, 355, function ($constraint) {
                $constraint->aspectRatio();  // Maintain the aspect ratio
                $constraint->upsize();       // Avoid stretching the image if it's smaller than the max size
            });
        } else {
            $image->resize(355, 533, function ($constraint) {
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

        if (preg_match('/\.gif$/i', $imageModel->route)) {
            $image->toGif()->save(storage_path('app/public/images/' . $imageModel->route));
        } else {
            $image->toPng()->save(storage_path('app/public/images/' . $imageModel->route));
        }
    }

    public function addWaterMarkVideoApprove($imageModel) {
        $videoName = $imageModel->route;
        $videoPath = storage_path('app/public/images/' . $videoName);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows system
            $ffmpegPath = 'C:/ProgramData/chocolatey/lib/ffmpeg/tools/ffmpeg/bin/ffmpeg.exe';
            $ffprobePath = 'C:/ProgramData/chocolatey/lib/ffmpeg/tools/ffmpeg/bin/ffprobe.exe';
        } else {
            // Linux/Ubuntu system
            $ffmpegPath = '/usr/bin/ffmpeg';  // Adjust this based on your server
            $ffprobePath = '/usr/bin/ffprobe'; // Adjust this based on your server
        }

        // Initialize FFProbe to get video details
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries' => $ffmpegPath,
            'ffprobe.binaries' => $ffprobePath,
        ]);
        $duration = (int) $ffprobe->format($videoPath)->get('duration');
        
        // Get video dimensions
        $dimensions = $ffprobe->streams($videoPath)->videos()->first()->getDimensions();
        $width = $dimensions->getWidth();
        $height = $dimensions->getHeight();

        // Set path for GIF output
        $gifName = pathinfo($videoName, PATHINFO_FILENAME) . '.gif';
        $gifPath = 'videogif/' . $gifName; // Save GIF in 'gifs' folder

        // Initialize FFmpeg to convert video to GIF
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => $ffmpegPath,
            'ffprobe.binaries' => $ffprobePath,
        ]);
        $ffmpegVideo = $ffmpeg->open($videoPath);

        $watermarkPath = public_path('images/unique_marca_agua.png');

        
        $ffmpegVideo->filters()->watermark($watermarkPath, [
            'position' => 'relative',  // Position watermark relative to video size
            'x' => 10,  // Horizontal position of the watermark (in pixels)
            'y' => 10,  // Vertical position of the watermark (in pixels)
            'opacity' => 0.5,  // Set opacity of the watermark (0 to 1)
        ]);

        $ffmpegFormat = new X264('libmp3lame', 'libx264');

        $imageName = 'enconded_' . $videoName;
        
        $outputVideoPath = storage_path('app/public/images/' . $imageName);
        $outputGifPath = storage_path('app/public/videogif/' . $gifName);

        // Comando FFmpeg
        $command = $ffmpegPath . ' -i "' . $videoPath . '" -i "' . $watermarkPath . '" -filter_complex "[1:v]scale=iw:ih[tiled];[0:v][tiled]overlay=0:0" -c:v libx264 -c:a aac -strict experimental -y "' . $outputVideoPath . '"';

        // Ejecutar el comando
        exec($command);

        // Create the GIF (the GIF will have the same duration as the video)
        $gifCommand = $ffmpegPath . ' -i "' . $outputVideoPath . '" -i "' . $watermarkPath . '" -filter_complex "[0][1]overlay=10:10,fps=15,scale=' . $width . ':' . $height . '" -t ' . $duration . ' -y "' . $outputGifPath . '"';
        
        exec($gifCommand, $output, $status);

        return $gifName;
    }

    public function approve($id) {
        $imageModel = Image::findOrFail($id);

        if(is_null($imageModel->watermarked)) {
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageModel->route)) {
                $this->addWaterMarkApprove($imageModel);
            } else {
                $imageModel->route_gif = $this->addWaterMarkVideoApprove($imageModel);
            }
        }
        
        $imageModel->status = 'approved';
        $imageModel->watermarked = 1;
        $imageModel->updated_at = \Carbon\Carbon::now();
        $imageModel->update();

        return redirect()->back()->with('exito', 'Imagen aprobada.');
    }

    public function unapprove($id)
    {
        $image = Image::findOrFail($id);
        $image->status = 'unapproved';
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen NO aprobada.');
    }

    public function approveAll($id) {
        $images = Image::
        where('user_id', $id)->where('status', 'pending')
        ->orWhere('user_id', $id)->where('status', 'unapproved')
        ->get();

        foreach($images as $imageModel) {
            if(is_null($imageModel->watermarked)) {
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageModel->route)) {
                    $this->addWaterMarkApprove($imageModel);
                } else {
                    $imageModel->route_gif = $this->addWaterMarkVideoApprove($imageModel);
                }
            }

            $imageModel->status = 'approved';
            $imageModel->watermarked = 1;
            $imageModel->updated_at = \Carbon\Carbon::now();
            $imageModel->update();
        }

        return redirect()->back()->with('exito', 'Imagenes aprobadas.');
    }

    public function unapproveAll($id)
    {
        $images = Image::
        where('user_id', $id)->where('status', 'pending')
        ->orWhere('user_id', $id)->where('status', 'approved')
        ->get();

        foreach($images as $image) {
            $image->status = 'unapproved';
            $image->updated_at = \Carbon\Carbon::now();
            $image->update();
        }

        return redirect()->back()->with('exito', 'Todas las imagenes aprobadas.');
    }

    public function visible($id)
    {
        $image = Image::findOrFail($id);
        $image->visible = 1;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen visible.');
    }

    public function invisible($id)
    {
        $image = Image::findOrFail($id);
        $image->visible = NULL;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen oculta.');
    }

    public function visibleAll($id)
    {
        $images = Image::
        where('user_id', $id)->whereNull('visible')
        ->get();

        foreach($images as $image) {
            $image->visible = 1;
            $image->updated_at = \Carbon\Carbon::now();
            $image->update();
        }

        return redirect()->back()->with('exito', 'Todas las imagenes visibles.');
    }

    public function invisibleAll($id)
    {
        $images = Image::
        where('user_id', $id)->whereNotNull('visible')
        ->get();

        foreach($images as $image) {
            $image->visible = NULL;
            $image->updated_at = \Carbon\Carbon::now();
            $image->update();
        }

        return redirect()->back()->with('exito', 'Todas las imagenes visibles.');
    }

    public function uploadProfile(Request $request)
    {
        // Validate the request data
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|integer',
        ]);

        $user = User::find($request->get('user_id'));

        if(!is_null($user->profile_image)){
            \Storage::disk('images')->delete($user->profile_image);
        }

        // Upload the profile image
        $file = $request->file('image');
        $imageName = time() . $file->getClientOriginalName();
        \Storage::disk('images')->put($imageName, \File::get($file));

        // Update the user's profile image
        $user->profile_image = $imageName;
        $user->updated_at = \Carbon\Carbon::now();
        $user->update();

        return redirect()->back()->with('exito', 'Imagen de perfil cambiada');
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        \Storage::disk('images')->delete($image->route);
        $image->delete();

        return redirect()->back()->with('exito', 'Imagen borrada!');
    }

    public function deleteAll($id)
    {
        $images = Image::where('user_id', $id)->get();

        foreach($images as $image) {
            \Storage::disk('images')->delete($image->route);
            $image->delete();
        }

        return redirect()->back()->with('exito', 'Imagenes borradas!');
    }


    public function getImage($filename) {
        $file = \Storage::disk('images')->get($filename);

        return new Response($file, 200);
    }
    
    public function getGif($filename) {
        $file = \Storage::disk('videogif')->get($filename);

        return new Response($file, 200);
    }

}