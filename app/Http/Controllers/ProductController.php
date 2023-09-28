<?php

namespace App\Http\Controllers;

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
        //$this->middleware('auth');
    }

    public function getAll(){

    }

    public function get($name){
        $name = str_replace("-", " ", $name);
        $product = Product::where('name', $name)->first();

        return view('products.get', [
            'product' => $product
        ]);
    }
}
