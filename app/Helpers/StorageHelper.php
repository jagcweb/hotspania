<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use getID3;

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

    public static function getSize($image, $name = NULL)
    {
        // Crear una instancia de getID3
        $getID3 = new getID3;
    
        // Si estamos en producción, usamos GCS; de lo contrario, 'images' (local)
        if (App::environment('production')) {
            // Obtener el contenido de la imagen desde GCS
            $imageContent = \Storage::disk('gcs')->get($image->route);
    
            // Analizar el contenido de la imagen
            $fileInfo = $getID3->analyzeFromString($imageContent);
    
            // Obtener las dimensiones
            $width = $fileInfo['image_width'] ?? null;
            $height = $fileInfo['image_height'] ?? null;
        } else {
            // Obtener la ruta local de la imagen
            $imagePath = \Storage::disk($name)->path($image->route);
    
            // Obtener las dimensiones de la imagen localmente
            list($width, $height) = getimagesize($imagePath);
        }
    
        // Verificar si se obtuvieron las dimensiones
        if ($width && $height) {
            return ['width' => $width, 'height' => $height];
        } else {
            return ['width' => NULL, 'height' => NULL];
        }
    }
}
