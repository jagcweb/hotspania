<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.dashboard');
    }

    /**
     * Ejecuta el comando artisan docs:load para cargar la documentación.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cargarDocumentacion(Request $request)
    {
        // Ejecuta el comando 'docs:load'
        $output = Artisan::call('docs:load');

        // Puedes obtener el resultado así:
        $result = Artisan::output();

        // Redirige de nuevo con un mensaje flash
        return redirect()->route('admin')
        ->with('exito', 'Documentación cargada correctamente.')
        ->with('output', $result);
    }
}