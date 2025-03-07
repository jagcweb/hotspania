<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use getID3;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class StorageHelper
{
    /**
     * Devuelve el disco adecuado basado en el entorno actual.
     * 
     * @return string
     */
    public static function getDisk($name = NULL)
    {
        // Si estamos en producción, usamos GCS; de lo contrario, 'images' (local)
        return App::environment('production') ? 'gcs' : $name;
    }

    public static function getSize($image, $name = NULL) {
        if (App::environment('production')) {
            try {
                // Obtener el contenido de la imagen desde GCS
                $imageContent = \Storage::disk('gcs')->get($image->route);
                
                // Crear un archivo temporal
                $tempFile = tempnam(sys_get_temp_dir(), 'img_');
                file_put_contents($tempFile, $imageContent);
                
                // Usar Intervention Image para obtener las dimensiones
                $manager = new ImageManager(new Driver());
                $img = $manager->read($tempFile);
                $width = $img->width();
                $height = $img->height();
                
                // Limpiar el archivo temporal
                unlink($tempFile);
                
                return ['width' => $width, 'height' => $height];
            } catch (\Exception $e) {
                \Log::error('Error al obtener dimensiones de imagen: ' . $e->getMessage());
                return ['width' => NULL, 'height' => NULL];
            }
        } else {
            try {
                $imagePath = \Storage::disk($name)->path($image->route);
                list($width, $height) = getimagesize($imagePath);
                return ['width' => $width, 'height' => $height];
            } catch (\Exception $e) {
                \Log::error('Error al obtener dimensiones de imagen local: ' . $e->getMessage());
                return ['width' => NULL, 'height' => NULL];
            }
        }
    }
}
