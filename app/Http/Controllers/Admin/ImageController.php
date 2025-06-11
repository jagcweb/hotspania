<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;  
use App\Helpers\StorageHelper;
use App\Models\User;  
use Illuminate\Http\Response;
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
use CV\Opencv;
use Google\Cloud\AIPlatform\V1\PredictionServiceClient;
use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ImageController extends Controller
{
    public function saveBlur(Request $request, $id)
    {
        $imageModel = \App\Models\Image::findOrFail($id);
        $data = $request->input('image');

        // Convertir base64 a binario
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

        // Obtener nombre de archivo y extensión
        $imageName = $imageModel->route;
        $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Cargar imagen desde el base64
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageData);

        // Usar la misma lógica que en addWaterMark()
        if ($extension === 'gif' || Str::contains($extension, 'gif')) {
            $disk = StorageHelper::getDisk('images');
            $imageContent = $image->toGif();
            \Storage::disk($disk)->put($imageName, $imageContent, 'private');
        } else {
            $disk = StorageHelper::getDisk('images');
            $imageContent = $image->toJpeg(70); // compresión al 70%
            \Storage::disk($disk)->put($imageName, $imageContent, 'private');
        }

        return response()->json(['success' => true]);
    }



    public function get($id, $name, $filter) {
        switch($filter) {
            case 'pendientes':
                $images = Image::where('user_id', $id)->where('status', 'pending')->orderBy('id', 'desc')->paginate(8);
                break;
            case 'aprobadas':
                $images = Image::where('user_id', $id)->where('status', 'approved')->orderBy('id', 'desc')->paginate(8);
                break;
            case 'desaprobadas':
                $images = Image::where('user_id', $id)->where('status', 'unapproved')->orderBy('id', 'desc')->paginate(8);
                break;
            case 'visible':
                $images = Image::where('user_id', $id)->whereNotNull('visible')->orderBy('id', 'desc')->paginate(8);
                break;
            case 'ocultas':
                $images = Image::where('user_id', $id)->whereNull('visible')->orderBy('id', 'desc')->paginate(8);
                break;
            case 'todas':
                $images = Image::where('user_id', $id)->orderBy('id', 'desc')->paginate(8);
                break;
            default:
                $images = Image::where('user_id', $id)->orderBy('id', 'desc')->paginate(8);
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

        $newFileName = 'front-'.$image->route;

        if (str_contains($image->route, '.gif')) {
            $imageContent = $imageReader->toGif();
        } else {
            $imageContent = $imageReader->toJpeg(70);
        }

        \Storage::disk(StorageHelper::getDisk('images'))->put($newFileName, $imageContent);
    
       
        $image->frontimage = 1;
        $image->route_frontimage = $newFileName;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();
        
        return redirect()->back()->with('exito', 'Imagen como portada.');
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
                $activate = 'source /var/www/hotspania/body_face_nsfw_models/venv/bin/activate';
                $script = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($tempImagePath);
                $command = "bash -c '$activate && $script'";
                $script2 = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($tempImagePath) . ' --blur_faces';
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

            $manager = new ImageManager(new Driver());
            $this->addWaterMark($file, $imageName, $extension, false);


            $imageModel = new Image(); // Assuming you have an Image model
            $imageModel->user_id = $request->input('user_id');
            $imageModel->route = $imageName;
            $imageModel->route_gif = $gifName ?? NULL;
            $imageModel->size = round($file->getSize() / 1024, 2);
            $imageModel->type = "images";
            $imageModel->status = 'approved'; // Set initial status to pending
            $imageModel->watermarked = 1;
            $imageModel->vision_data = json_encode($combinedResult);
            $imageModel->save();
        }

        //return back()->with('success', 'Images uploaded with watermark pattern successfully!');
    }

    /*private function analyzeImageWithVisionAI($imageData) {
        try {
            // Inicializar el cliente de Google Vision AI
            $imageAnnotator = new ImageAnnotatorClient([
                'credentials' => storage_path('keys/hotspania-41a196f738f2.json'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error al inicializar el cliente ImageAnnotator: ' . $e->getMessage());
        }
    
        // Configuramos las características que queremos obtener de la imagen
        $features = [
            (new Feature())->setType(Type::LABEL_DETECTION)->setMaxResults(10),
            (new Feature())->setType(Type::TEXT_DETECTION),
            (new Feature())->setType(Type::SAFE_SEARCH_DETECTION),
            (new Feature())->setType(Type::IMAGE_PROPERTIES),
            (new Feature())->setType(Type::FACE_DETECTION),
            (new Feature())->setType(Type::OBJECT_LOCALIZATION),
            (new Feature())->setType(Type::LANDMARK_DETECTION),
            (new Feature())->setType(Type::LOGO_DETECTION),
        ];
    
        try {
            // Crear un objeto GoogleImage desde los datos de la imagen
            $image = new GoogleImage();
            $image->setContent($imageData);
    
            // Llamamos a la API de Vision
            $response = $imageAnnotator->annotateImage(
                $imageData,
                $features
            );
    
            // Verifica si la respuesta contiene algún error
            $status = $response->getError();
            if ($status) {
                Log::error("Error en la respuesta de la API: " . json_encode($status));
            }
    
            // Obtener las distintas anotaciones
            $labels = $response->getLabelAnnotations();  // Etiquetas detectadas
            $textAnnotations = $response->getTextAnnotations();  // Texto detectado
            $safeSearch = $response->getSafeSearchAnnotation();  // Detección de contenido seguro
            $imageProperties = $response->getImagePropertiesAnnotation();  // Propiedades de imagen
            $faces = $response->getFaceAnnotations();  // Rostros detectados
            $objects = $response->getLocalizedObjectAnnotations();  // Objetos detectados
            $landmarks = $response->getLandmarkAnnotations();  // Lugares o monumentos detectados
            $logos = $response->getLogoAnnotations();  // Logotipos detectados
    
            // Procesar las diferentes anotaciones
    
            // Etiquetas
            $labelsArray = [];
            foreach ($labels as $label) {
                $labelsArray[] = $label->getDescription();
            }
    
            // Texto detectado
            $textArray = [];
            foreach ($textAnnotations as $textAnnotation) {
                $textArray[] = $textAnnotation->getDescription();
            }
    
            // Análisis de seguridad
            $safeSearchArray = [
                'adult' => $safeSearch->getAdult(),
                'spoof' => $safeSearch->getSpoof(),
                'medical' => $safeSearch->getMedical(),
                'violence' => $safeSearch->getViolence(),
                'racy' => $safeSearch->getRacy()
            ];
    
            // Propiedades de la imagen (colores dominantes)
            $imagePropertiesArray = [];
            foreach ($imageProperties->getDominantColors()->getColors() as $color) {
                $imagePropertiesArray[] = [
                    'rgb' => $color->getColor(),
                    'score' => $color->getScore(),
                    'pixelFraction' => $color->getPixelFraction()
                ];
            }
    
            // Rostros detectados (si quieres procesar las coordenadas de los rostros)
            $facesArray = [];
            foreach ($faces as $face) {
                $facesArray[] = [
                    'boundingBox' => $face->getBoundingPoly()->getNormalizedVertices(),
                    'joyLikelihood' => $face->getJoyLikelihood(),
                    'sorrowLikelihood' => $face->getSorrowLikelihood(),
                    'angerLikelihood' => $face->getAngerLikelihood(),
                    'surpriseLikelihood' => $face->getSurpriseLikelihood()
                ];
            }
    
            // Objetos detectados
            $objectsArray = [];
            foreach ($objects as $object) {
                $objectsArray[] = $object->getName();
            }
    
            // Lugares detectados (monumentos)
            $landmarksArray = [];
            foreach ($landmarks as $landmark) {
                $landmarksArray[] = $landmark->getDescription();
            }
    
            // Logotipos detectados
            $logosArray = [];
            foreach ($logos as $logo) {
                $logosArray[] = $logo->getDescription();
            }
    
            // Regresar los datos procesados
            return [
                'labels' => $labelsArray,
                'text' => $textArray,
                'safeSearch' => $safeSearchArray,
                'imageProperties' => $imagePropertiesArray,
                'faces' => $facesArray,
                'objects' => $objectsArray,
                'landmarks' => $landmarksArray,
                'logos' => $logosArray,
            ];
        } catch (\Exception $e) {
            Log::error("Error al analizar la imagen con Google Vision AI: " . $e->getMessage());
            return null;
        } finally {
            // Cerramos el cliente para liberar recursos
            $imageAnnotator->close();
        }
    }*/



    private function analyzeImageWithVertexAI($imageData) {
        try {
            // Obtener el token de acceso para autenticación
            $accessToken = $this->getGoogleAccessToken(); // Método para obtener el token
        
            // Crear el cliente HTTP (Guzzle)
            $client = new Client();
            
            // URL del endpoint de Vertex AI Vision (ajusta esto según tu proyecto y endpoint)
            $endpointId = 'https://vision.googleapis.com/v1/images:annotate';  // ID del endpoint VisionAI
            $project = "hotspania";
            $location = "global";
            $url = "https://$location-aiplatform.googleapis.com/v1/projects/$project/locations/$location/endpoints/$endpointId:predict";
            
            // Configura las cabeceras necesarias para la solicitud
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ];
    
            // Prepara el cuerpo de la solicitud con la imagen codificada en base64
            $body = [
                "instances" => [
                    [
                        "image_bytes" => [
                            "b64" => base64_encode($imageData),
                        ]
                    ]
                ]
            ];
            
            // Realiza la solicitud de predicción
            $response = $client->post($url, [
                'json' => $body,
                'headers' => $headers
            ]);

            Log::info("Código de estado HTTP de la respuesta: " . $response->getStatusCode());

            Log::info("Respuesta completa de Vertex AI: " . $response->getBody());
            
            // Procesa la respuesta
            $responseBody = json_decode($response->getBody(), true);

            //Log::info("Respuesta completa de Vertex AI Body: " . $response->getBody());
    
            // Verifica si hubo un error en la respuesta
            if (isset($responseBody['error'])) {
                Log::error("Error en la respuesta de Vertex AI: " . json_encode($responseBody['error']));
                return null;
            }
    
            // Extrae las predicciones de la respuesta
            $predictions = [];
            if (isset($responseBody['predictions'])) {
                foreach ($responseBody['predictions'] as $prediction) {
                    $predictions[] = $prediction; // Procesa según sea necesario
                }
            }
    
            // Devuelve las predicciones
            return $predictions;
        
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error("Error en la predicción de Vertex AI: " . $e->getMessage());
            return null;
        }
    }
    
    private function getGoogleAccessToken() {
        // Ruta al archivo de las credenciales de servicio de Google
        $keyFile = storage_path('keys/hotspania-41a196f738f2.json');
        
        // Obtener las credenciales de servicio
        $credentials = new ServiceAccountCredentials(
            null,
            $keyFile
        );
        
        // Recuperar el token de acceso
        $token = $credentials->fetchAuthToken();
        
        return $token['access_token']; // Devuelve el token de acceso
    }

    /*private function processImageWithOpenCV($imageData) {
        // Create a Mat (matrix) from the image data
        $mat = Imgcodecs::imdecode(np_frombuffer($imageData), Imgcodecs::IMREAD_COLOR);
        
        // Convert to grayscale (example of processing)
        $grayMat = new Mat();
        cvtColor($mat, $grayMat, CV::COLOR_BGR2GRAY);
    
        // You can save the processed image as PNG/JPG for further use (optional)
        $processedImagePath = 'processed_image.jpg';
        Imgcodecs::imwrite($processedImagePath, $grayMat);
    
        // You can return specific data you need after processing, like basic image info
        return [
            'processed_image' => $processedImagePath,  // Path to the processed image
            'image_size' => $grayMat->size()  // You can return other relevant image info
        ];
    }*/

    
    
    
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

    public function addWaterMarkApprove($imageModel) {
        $manager = new ImageManager(new Driver());

        $file = storage_path('app/public/images/' . $imageModel->route);

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

        if (preg_match('/\.gif$/i', $imageModel->route)) {
            $disk = StorageHelper::getDisk('images');
            $imageContent = $image->toGif();
            $filePath = 'images/' . $imageName;
            \Storage::disk($disk)->put($imageModel->route, $imageContent);
            //$image->toGif()->save(storage_path('app/public/images/' . $imageModel->route));
        } else {
            $disk = StorageHelper::getDisk('images');
            $imageContent = $image->toJpeg(70);
            $filePath = 'images/' . $imageName;
            \Storage::disk($disk)->put($imageModel->route, $imageContent);
            //$image->toJpeg(70)->save(storage_path('app/public/images/' . $imageModel->route));
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

    public function unapprove($id) {
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

    public function unapproveAll($id) {
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

    public function visible($id) {
        $image = Image::findOrFail($id);
        $image->visible = 1;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen visible.');
    }

    public function invisible($id) {
        $image = Image::findOrFail($id);
        $image->visible = NULL;
        $image->updated_at = \Carbon\Carbon::now();
        $image->update();

        return redirect()->back()->with('exito', 'Imagen oculta.');
    }

    public function visibleAll($id) {
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

    public function invisibleAll($id) {
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

    public function uploadProfile(Request $request) {
        // Validate the request data
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|integer',
        ]);

        $user = User::find($request->get('user_id'));

        if(!is_null($user->profile_image)){
           \Storage::disk(StorageHelper::getDisk('images'))->delete($user->profile_image);
        }

        // Upload the profile image
        $file = $request->file('image');
        $imageName = time() . '_' . bin2hex(random_bytes(10)) . '.' . $file->getClientOriginalExtension();

       \Storage::disk(StorageHelper::getDisk('images'))->put($imageName, \File::get($file));

        // Update the user's profile image
        $user->profile_image = $imageName;
        $user->updated_at = \Carbon\Carbon::now();
        $user->update();

        return redirect()->back()->with('exito', 'Imagen de perfil cambiada');
    }

    public function delete($id) {
        $image = Image::findOrFail($id);
       \Storage::disk(StorageHelper::getDisk('images'))->delete($image->route);
        $image->delete();

        return redirect()->back()->with('exito', 'Imagen borrada!');
    }

    public function deleteAll($id) {
        $images = Image::where('user_id', $id)->get();

        foreach($images as $image) {
           \Storage::disk(StorageHelper::getDisk('images'))->delete($image->route);
            $image->delete();
        }

        return redirect()->back()->with('exito', 'Imagenes borradas!');
    }


    public function getImage($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('images'))->get($filename);

        return new Response($file, 200);
    }
    
    public function getGif($filename) {
        $file = \Storage::disk(StorageHelper::getDisk('videogif'))->get($filename);

        return new Response($file, 200);
    }

}