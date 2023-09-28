<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrdersProduct;
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
        
        if(!\Auth::user() || \Auth::user()->getRoleNames()[0] != "admin"){
            return back();
        }
    }

    public function get(){
        $orders = Order::orderBy('id', 'desc')->get();

        return view('admin.orders.get', [
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = $this->validate($request, [
            'status' => ['required', 'alpha_num'],
        ]);

        $order = Order::find($id);

        if(is_object($order)){
            $order->status = $request->get('status');
            $order->update();

            return back()->with('exito', 'Estado del pedido '.$order->reference. ' actualizado.');
        }

        return back()->with('error', 'Error al actualizar el pedido.');
    }
}
