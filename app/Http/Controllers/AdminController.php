<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Subcategory;

class AdminController extends Controller
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
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.home', [
            'categories' => $categories,
        ]);
    }


    /*********  AJAX  **********/
    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->orderBy('name', 'asc')->get();

        if($subcategories){
            $status = 200;
            return response(json_encode($subcategories), $status)->header('Content-type', 'text/plain');
        }else{
            $status = 404;
            return response(json_encode('error'),$status);
        }
    }
}
