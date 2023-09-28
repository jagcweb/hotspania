<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
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
            'discount' => ['required', 'alpha_num', 'min:1', 'max:100'],
            'uses' => ['required', 'alpha_num', 'min:1'],
            'expiration_date' => ['required', 'date'],
        ]);

        $name = $request->get('name');
        $discount = $request->get('discount');
        $uses = $request->get('uses');
        $expiration_date = $request->get('expiration_date');

        $disc = new Discount();
        $disc->user_id = \Auth::user()->id;
        $disc->name = $name;
        $disc->discount = $discount;
        $disc->uses = $uses;
        $disc->expiration_date = $expiration_date;
        $disc->save();

        return back()->with('exito', 'Descuento '.$disc->name.' creado.');
    }

    public function get(){
        $discounts = Discount::orderBy('name', 'asc')->get();

        return view('admin.discounts.get', [
            'discounts' => $discounts
        ]);
    }

    public function update(Request $request, $id)
    {

        $validate = $this->validate($request, [
            'name' => ['required', 'string'],
            'discount' => ['required', 'alpha_num', 'min:1', 'max:100'],
            'uses' => ['required', 'alpha_num', 'min:1'],
            'expiration_date' => ['required', 'date'],
        ]);

        $name = $request->get('name');
        $discount = $request->get('discount');
        $uses = $request->get('uses');
        $expiration_date = $request->get('expiration_date');

        $disc = Discount::find($id);
        
        if($disc){

            $disc->name = $name;
            $disc->discount = $discount;
            $disc->uses = $uses;
            $disc->expiration_date = $expiration_date;
            $disc->update();

            return back()->with('exito', 'Descuento '.$disc->name.' actualizado.');
        }

        return back()->with('error', 'Error al actualizar el descuento '.$disc->name);
    }

    public function delete($id)
    {
        $disc = Discount::find($id);
        
        if($disc){
            $name = $disc->name;

            $disc->delete();

            return back()->with('exito', 'Descuento '.$name.' borrado.');
        }

        return back()->with('error', 'Error al borrar el descuento.');
    }
}
