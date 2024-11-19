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
use FFMpeg\Coordinate\TimeCode;

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


    public function upload(Request $request)
    {
        // Validate the request data
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,avchd,webm,flv|max:10240',
            'user_id' => 'required|integer',
        ]);

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
                    $ffmpegPath = '/usr/local/bin/ffmpeg';  // Adjust this based on your server
                    $ffprobePath = '/usr/local/bin/ffprobe'; // Adjust this based on your server
                }

                // Initialize FFProbe to get video details
                $ffprobe = FFProbe::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);
                $duration = (int) $ffprobe->format($videoPath)->get('duration');
                
                // Get video dimensions
                $dimensions = $ffprobe->streams($videoPath)->videos()->first()->getDimensions();
    
                // Set path for GIF output
                $gifName = pathinfo($videoName, PATHINFO_FILENAME) . '.gif';
                $gifPath = 'videogif/' . $gifName; // Save GIF in 'gifs' folder
    
                // Initialize FFmpeg to convert video to GIF
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);
                $ffmpegVideo = $ffmpeg->open($videoPath);
    
                // Create the GIF (the GIF will have the same duration as the video)
                $ffmpegVideo->gif(TimeCode::fromSeconds(0), $dimensions, $duration)->save(storage_path('app/public/' . $gifPath));
            } else {
                // Read the uploaded image
                $image = $manager->read($file);

                // Path to the watermark image (the image you want to use as a pattern)
                $watermarkPath = public_path('images/marca_agua.png');  // Adjust the path as needed

                // Read the watermark image
                $watermark = $manager->read($watermarkPath);

                // Resize the watermark image to a smaller size if needed (optional)
                $watermark->resize(200, 280);  // Example size, adjust as needed

                // Get the dimensions of the original image
                $imageWidth = $image->width();
                $imageHeight = $image->height();

                // Now, we need to "tile" the watermark image across the entire background of the original image
                // Loop through the image to tile the watermark

                for ($y = 0; $y < $imageHeight; $y += $watermark->height()) {
                    for ($x = 0; $x < $imageWidth; $x += $watermark->width()) {
                        // Insert the watermark image at every position (tiled pattern)
                        $image->place($watermark, 'top-left', $x, $y);
                    }
                }

                // Save the image to the storage path (app/public/images)
                // Save the image with watermark to the storage path
                $image->toPng()->save(storage_path('app/public/images/' . $imageName));
            }


            // Assuming you have an Image model to store this image information in the database
            $imageModel = new Image(); // Assuming you have an Image model
            $imageModel->user_id = $request->input('user_id');
            $imageModel->route = $imageName;
            $imageModel->route_gif = $gifName ?? NULL;
            $imageModel->size = round($file->getSize() / 1024, 2);
            $imageModel->type = "images";
            $imageModel->status = 'pending'; // Set initial status to pending
            $imageModel->save();
        }

        return back()->with('success', 'Images uploaded with watermark pattern successfully!');
    }


    public function approve($id)
    {
        $image = Image::findOrFail($id);
        $image->status = 'approved';
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

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

    public function approveAll($id)
    {
        $images = Image::
        where('user_id', $id)->where('status', 'pending')
        ->orWhere('user_id', $id)->where('status', 'unapproved')
        ->get();

        foreach($images as $image) {
            $image->status = 'approved';
            $image->updated_at = \Carbon\Carbon::now();
            $image->update();
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

}