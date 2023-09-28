<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
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
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,5})?$/'],
            'units' => ['required', 'alpha_num'],
            'discontinued' => ['nullable', 'alpha_num'],
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);

        $images = $request->file('images');

        $images_array = array();
        foreach ($images as $key => $image) {
            $image_name = time() ."_". $image->getClientOriginalName();

            array_push($images_array, $image_name);

            //Guardamos en el Storage las imagenes
            \Storage::disk('products')->put($image_name, \File::get($image));
        }

        $pro = new Product();
        $pro->user_id = \Auth::user()->id;
        $pro->category_id = $request->get('category');
        $pro->subcategory_id = $request->get('subcategory');
        $pro->name = $request->get('name');
        $pro->description = $request->get('description');
        $pro->price = $request->get('price');
        $pro->units = $request->get('units');
        $pro->discontinued = $request->get('discontinued');
        $pro->images = json_encode($images_array, JSON_FORCE_OBJECT);
        $pro->save();

        return back()->with('exito', 'Product '.$pro->name.' creado.');
    }

    public function get(){
        $categories = Category::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();

        return view('admin.products.get', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {

        $validate = $this->validate($request, [
            'category' => ['required', 'string'],
            'subcategory' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,5})?$/'],
            'units' => ['required', 'alpha_num'],
            'discontinued' => ['nullable', 'alpha_num'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp'],
        ]);


        $pro = Product::find($id);
        
        if($pro){

            if(!is_null($request->file('images'))){

                $currentImages = json_decode($request->file('images'), true);

                foreach ($currentImages as $key => $im) {
                    \Storage::disk('products')->delete($im);
                }

                $images = $request->file('images');

                $images_array = array();
                foreach ($images as $key => $image) {
                    $image_name = time() ."_". $image->getClientOriginalName();
        
                    array_push($images_array, $image_name);
        
                    //Guardamos en el Storage las imagenes
                    \Storage::disk('products')->put($image_name, \File::get($image));
                }

                $pro->images = json_encode($images_array, JSON_FORCE_OBJECT);
            }

            $pro->category_id = $request->get('category');
            $pro->subcategory_id = $request->get('subcategory');
            $pro->name = $request->get('name');
            $pro->description = $request->get('description');
            $pro->price = $request->get('price');
            $pro->units = $request->get('units');
            $pro->discontinued = $request->get('discontinued');
            $pro->updated_at = \Carbon\Carbon::now();
            $pro->update();

            return back()->with('exito', 'producto '.$pro->name.' actualizadp.');
        }

        return back()->with('error', 'Error al actualizar el producto '.$pro->name);
    }

    public function delete($id)
    {
        $pro = Product::find($id);
        
        if($pro){
            $name = $pro->name;

            $pro->delete();

            return back()->with('exito', 'Producto '.$name.' borrado.');
        }

        return back()->with('error', 'Error al borrar el producto.');
    }
}
