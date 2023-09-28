<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request, $id)
    {

        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'category' => ['required', 'alpha_num'],
        ]);

        $cat = Subcategory::find($id);
        
        if($cat){
            $cat->name = $request->get('name');
            $cat->category_id = $request->get('category');
            $cat->updated_at = \Carbon\Carbon::now();
            $cat->update();

            return back()->with('exito', 'Subcategoría '.$cat->name.' actualizada.');
        }

        return back()->with('error', 'Error al actualizar la Subcategoría '.$cat->name);
    }
}
