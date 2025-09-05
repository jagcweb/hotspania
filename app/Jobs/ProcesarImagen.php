<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Image;

class ProcesarImagen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $originalImagePath, $tempPath, $imageName, $originalImageName, $mimeType, $extension, $userId, $hideFace, $status;

    public function __construct($originalImagePath, $tempPath, $imageName, $originalImageName, $mimeType, $extension, $userId, $hideFace, $status)
    {
        $this->originalImagePath = $originalImagePath;
        $this->tempPath = $tempPath;
        $this->imageName = $imageName;
        $this->originalImageName = $originalImageName;
        $this->mimeType = $mimeType;
        $this->extension = $extension;
        $this->userId = $userId;
        $this->hideFace = $hideFace;
        $this->status = $status;
    }

    public function handle()
    {
        Log::info("Procesando imagen en cola: {$this->imageName}");

        // Detectar si estamos en Windows o Linux
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            $python = 'python';
            $predictorPath = 'C:/xampp/htdocs/ProyectosFreelance/Hotspania/body_face_nsfw_models/models/app/predictor.py';
            $command = "$python " . escapeshellarg($predictorPath) . ' ' . escapeshellarg($this->tempPath);
            $command2 = "$python " . escapeshellarg($predictorPath) . ' ' . escapeshellarg($this->tempPath) . ' --blur_faces';
        } else {
            $activate = 'source /var/www/hotspania/body_face_nsfw_models/models/app/venv/bin/activate 2>/dev/null';
            $script = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($this->tempPath);
            $command = "bash -c '$activate && { $script; } 2>/dev/null'";
            $script2 = 'python3 /var/www/hotspania/body_face_nsfw_models/models/app/predictor.py ' . escapeshellarg($this->tempPath) . ' --blur_faces';
            $command2 = "bash -c '$activate && { $script2; } 2>/dev/null'";
        }

        Log::debug("Comando ejecutado: $command");

        $output = shell_exec($command);

        Log::debug("Salida cruda del script Python: $output");

        // Filtrar la salida para extraer solo el JSON válido
        if (preg_match('/\{.*\}/s', $output, $matches)) {
            $json_output = $matches[0];
        } else {
            $json_output = '';
        }

        $combinedResult = json_decode($json_output, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Error al decodificar JSON: " . json_last_error_msg());
            Log::debug("Salida cruda del script Python: [$output]");
            $combinedResult = ['error' => 'JSON parse failed', 'raw_output' => $output];
        }

        // Variable para el archivo final
        $finalFile = null;

        // Si se debe ocultar el rostro
        if ($this->hideFace) {
            Log::info("Hide face detected for image: {$this->imageName}");
            Log::debug("Comando2 ejecutado: $command2");
            $output2 = shell_exec($command2);
            Log::debug("Salida del script2 de Python: $output2");

            // Verificar si existe la imagen difuminada y reemplazar $file
            $ext = '.' . $this->extension;
            $blurredPath = str_replace($ext, '_blurred' . $ext, $this->tempPath);

            if (file_exists($blurredPath)) {
                $finalFile = new \Illuminate\Http\UploadedFile(
                    $blurredPath,
                    $this->imageName,
                    $this->mimeType,
                    null,
                    true // marcar como archivo de prueba
                );
                Log::info("Imagen con rostros ocultos usada para la marca de agua: {$this->imageName}");
            } else {
                Log::warning("No se encontró la imagen difuminada esperada en: $blurredPath");
                $finalFile = new \Illuminate\Http\UploadedFile(
                    $this->tempPath, $this->imageName, $this->mimeType, null, true
                );
            }
        } else {
            $finalFile = new \Illuminate\Http\UploadedFile(
                $this->tempPath, $this->imageName, $this->mimeType, null, true
            );
        }

        // Obtener el tamaño del archivo ANTES de eliminarlo y procesar la marca de agua
        $fileSize = round($finalFile->getSize() / 1024, 2);

        $manager = new ImageManager(new Driver());
        // Marca de agua (usa el mismo método que ya tienes)
        app('App\Http\Controllers\AccountController')->addWaterMark($finalFile, $this->imageName, $this->extension, false);

        // Guardar la imagen original en el bucket final usando StorageHelper
        $originalImageFile = new \Illuminate\Http\UploadedFile(
            storage_path('app/public/original/' . $this->originalImageName),
            $this->originalImageName,
            $this->mimeType,
            null,
            true
        );

        // Procesar y guardar la imagen original
        $manager = new ImageManager(new Driver());
        $originalImage = $manager->read($originalImageFile->getPathname());

        $originalDisk = \App\Helpers\StorageHelper::getDisk('images');
        
        if ($this->extension === 'gif' || strpos($this->extension, 'gif') !== false) {
            $originalImageContent = $originalImage->toGif();
        } else {
            $originalImageContent = $originalImage->toJpeg(70);
        }
        
        \Storage::disk($originalDisk)->put($this->originalImageName, $originalImageContent, 'private');
        Log::info("Imagen original guardada en bucket: {$this->originalImageName}");

        // Eliminar archivos temporales DESPUÉS de procesar
        Storage::disk('temp_img_ia')->delete($this->imageName);
        Storage::disk('original')->delete($this->originalImageName);
        Log::info("Archivos temporales eliminados: {$this->imageName} y {$this->originalImageName}");

        // Guardar en base de datos con nombres únicos
        $imageModel = new Image();
        $imageModel->user_id = $this->userId;
        $imageModel->route = $this->imageName; // Imagen procesada con marca de agua
        $imageModel->original = $this->originalImageName; // Imagen original sin procesar
        $imageModel->size = $fileSize;
        $imageModel->type = "images";
        $imageModel->status = $this->status;
        $imageModel->watermarked = 1;
        $imageModel->visible = 1;
        $imageModel->vision_data = json_encode($combinedResult);
        $imageModel->save();

        Log::info("Imagen guardada - Original: {$this->originalImageName}, Procesada: {$this->imageName}");
    }
}