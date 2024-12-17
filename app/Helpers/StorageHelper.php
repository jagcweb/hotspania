<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

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
}
