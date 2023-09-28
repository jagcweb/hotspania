<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Discount;

class CartController extends Controller
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

    
    public function index(){
        
        $cart = Cart::where('user_id', \Auth::user()->id)->get();

        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    public function add(Request $request)
    {

        $validate = $this->validate($request, [
            'product_id' => ['required', 'string'],
            'quantity' => ['required', 'alpha_num', 'min:1'],
        ]);

        $id = \Crypt::decryptString($request->get('product_id'));
        $quantity = $request->get('quantity');
        
        $product = Product::find($id);

        $exist_cart = Cart::where('user_id', \Auth::user()->id)->where('product_id', $product->id)->first();

        if($exist_cart){
            $exist_cart->quantity = $exist_cart->quantity+$quantity;
            $exist_cart->update();

            return back()->with('exito', 'Producto añadido a la cesta!');
        } else {
            $cart = new Cart();

            if($cart){
                $cart->user_id = \Auth::user()->id;
                $cart->product_id = $product->id;
                $cart->quantity = $quantity;
                $cart->discount = 0;
                $cart->save();
    
                return back()->with('exito', 'Producto añadido a la cesta!');
            } else {
                return back()->with('error', 'Error al añadir a la cesta');
            }
        }

    }

    
    public function delete($id)
    {
        $id = \Crypt::decryptString($id);
        $cart = Cart::find($id);
        
        if($cart){
            $name = $cart->product->name;

            $cart->delete();

            return back()->with('exito', 'Producto '.$name.' eliminado de la cesta.');
        }

        return back()->with('error', 'Error al borrar el producto.');
    }

    public function getDiscount($name)
    {
        $date = new \DateTime();
        $today = $date->format('Y-m-d');

        $discount = Discount::where('name', $name)
        ->where('uses', '>', 0)
        ->where('expiration_date', '>=', $today)
        ->first();


        if($discount){
            $status = 200;

            $discounts = array(
                'discount' => $discount,
                'status' => $status,
            );

            return response(json_encode($discounts))->header('Content-type', 'text/plain');
        }else{
            $status = 404;
            return response(json_encode($status));
        }


    }
}
