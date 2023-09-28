<?php

namespace App\Http\Controllers\Admin;

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
        
        if(!\Auth::user() || \Auth::user()->getRoleNames()[0] != "admin"){
            return back();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {

        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'category' => ['required', 'alpha_num'],
        ]);

        $cat = new Subcategory();
        $cat->user_id = \Auth::user()->id;
        $cat->category_id = $request->get('category');
        $cat->name = $request->get('name');
        $cat->save();

        return back()->with('exito', 'Subcategoría '.$cat->name.' creada.');
    }

    public function get(){
        $categories = Category::orderBy('name', 'asc')->get();
        $subcategories = Subcategory::orderBy('name', 'asc')->get();

        return view('admin.subcategories.get', [
            'categories' => $categories,
            'subcategories' => $subcategories
        ]);
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

    public function delete($id)
    {
        $cat = Subcategory::find($id);
        
        if($cat){
            $name = $cat->name;
            $cat->delete();

            return back()->with('exito', 'Subcategoría '.$name.' borrada.');
        }

        return back()->with('error', 'Error al borrar la Subcategoría.');
    }
}
