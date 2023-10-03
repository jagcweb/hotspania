<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrdersProduct;
use App\Models\TotalSale;
use App\Http\Controllers\Controller;

class OrderController extends Controller
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
        $orders = Order::where('user_id', \Auth::user()->id)->get();

        return view('order.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $validate = $this->validate($request, [
            'reference' => ['required', 'string'],
            'discount_name' => ['nullable', 'string'],
        ]);

        $reference = $request->get('reference');
        $discount_name = $request->get('discount_name');

        $date = new \DateTime();
        $today = $date->format('Y-m-d');

        $discount_percentaje = NULL;
        if(!is_null($discount_name)){
            $discount = Discount::where('name', $discount_name)->where('expiration_date', '>=', $today)->where('uses', '>', 0)->first();

            if(is_object($discount)){
                $discount_percentaje = $discount->discount;

                $discount->uses = $discount->uses-1;
                $discount->update();
            }
        }
        
        $total = 0;

        $cart = Cart::where('user_id', \Auth::user()->id)->get();

        foreach ($cart as $key => $c) {
            $subtotal = $c->product->price*$c->quantity;
            
            if(!is_null($c->product->discount) && $c->product->discount > 0) {
                $total += $subtotal-($subtotal * $c->product->discount / 100);
            } else {
                $total += $subtotal;
            }
        }

        $order = new Order();
        $order->user_id = \Auth::user()->id;
        $order->discount = $discount_percentaje;
        $order->total = $total;
        $order->reference = $reference;
        $order->status = 0;
        $order->save();

        foreach ($cart as $key => $c) {         
            $order_product = new OrdersProduct(); 
            $order_product->user_id = \Auth::user()->id;
            $order_product->order_id = $order->id;
            $order_product->discount = $c->discount;
            $order_product->quantity = $c->quantity;
            $order_product->price = $c->product->price;
            $order_product->name = $c->product->name;
            $order_product->images = $c->product->images;
            $order_product->save();

            $total_sale = TotalSale::where('name', $c->product->name)->first();
        
            if(!is_object($total_sale)){
                $total_sale = new TotalSale();
                $total_sale->product_id = $c->product->id;
                $total_sale->quantity = $c->quantity;
                $total_sale->save();
            } else {
                $total_sale->quantity = $total_sale->quantity + $c->quantity;
                $total_sale->update();
            }

            $c->delete();
        }

        return redirect()->route('home')->with('exito', 'Pedido creado!');
    }
}
