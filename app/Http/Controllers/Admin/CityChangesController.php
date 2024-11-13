<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class CityChangesController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::orderBy('id', 'asc')->get();
                
        return view('admin.citychanges.index', [
            'cities' => $cities,
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        if (!is_null(\Cookie::get('city'))) {
            \Cookie::queue(\Cookie::forget('city'));
        }

        \Cookie::queue('city', $request->get('city'), 15*24*60);

        return back()->with('exito', 'La ciudad: '. $request->get('city') .' ha sido aplicada correctamente.');
    }
}
