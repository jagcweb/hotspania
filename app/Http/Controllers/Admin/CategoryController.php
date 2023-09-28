<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
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
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $image = $request->file('image');

        $image_name = time() ."_". $image->getClientOriginalName();

        //Guardamos en el Storage las imagenes
        \Storage::disk('categories')->put($image_name, \File::get($image));

        $cat = new Category();
        $cat->user_id = \Auth::user()->id;
        $cat->name = $request->get('name');
        $cat->description = $request->get('description');
        $cat->image = $image_name;
        $cat->save();

        return back()->with('exito', 'Categoría '.$cat->name.' creada.');
    }

    public function get(){
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.categories.get', [
            'categories' => $categories
        ]);
    }

    public function update(Request $request, $id)
    {

        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $cat = Category::find($id);
        
        if($cat){

            if(!is_null($request->file('image'))){
                $image = $request->file('image');

                $image_name = time() ."_". $image->getClientOriginalName();
    
                \Storage::disk('categories')->delete($cat->image);
    
                \Storage::disk('categories')->put($image_name, \File::get($image));
                $cat->image = $image_name;
            }

            $cat->name = $request->get('name');
            $cat->description = $request->get('description');
            $cat->updated_at = \Carbon\Carbon::now();
            $cat->update();

            return back()->with('exito', 'Categoría '.$cat->name.' actualizada.');
        }

        return back()->with('error', 'Error al actualizar la categoría '.$cat->name);
    }

    public function delete($id)
    {
        $cat = Category::find($id);
        
        if($cat){
            $name = $cat->name;

            $subcategories = Subcategory::where('category_id', $cat->id)->get();
            $products = Product::where('category_id', $cat->id)->get();

            foreach($subcategories as $subcat){
                $subcat->delete();
            }

            foreach($products as $pro){
                $pro->delete();
            }

            $cat->delete();

            return back()->with('exito', 'Categoría '.$name.' borrada.');
        }

        return back()->with('error', 'Error al borrar la categoría.');
    }
}
