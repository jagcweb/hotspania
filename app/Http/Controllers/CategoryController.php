<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
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

    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    public function get($name)
    {
        $category = Category::where('name', $name)->first();

        return view('categories.get', [
            'category' => $category
        ]);
    }
}
