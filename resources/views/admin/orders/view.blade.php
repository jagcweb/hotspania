<div class="modal fade" id="view-order-{{$order->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Ver pedido {{$order->reference}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @foreach ($order->products as $i=>$p)
                    <div @if($i > 0) class="mt-3" @endif style="width: 100%; border: 1px solid #ccc; padding: 15px; background:#fcfcfc;">
                        <p style="width: 100%; text-align:center;"><b>Nombre:</b> {{$p->name}}</p>
                        <p style="width: 100%; text-align:center;"><b>Precio:</b> {{$p->price}}€</p>
                        <p style="width: 100%; text-align:center;"><b>Unidades:</b> {{$p->quantity}}</p>
                        <p style="width: 100%; text-align:center;"><b>Descuento:</b> {{$p->discount}}%</p>
                        <p style="width: 100%; text-align:center;"><b>Total Precio:</b> {{$p->price * $p->quantity - ($p->price * $p->quantity * $p->discount / 100)}}€</p>
                        <br>
                        <div style="width: 100%; display:flex; flex-direction:column; justify-content:center;">
                            <p style="width: 100%; text-align:center;"><b>Imagen:</b></p>
                            <img style="margin: 0px auto;" width="200" src="{{url('/get-image', ['filesystem' => 'products', 'filename' => json_decode($p->images, true)[0]])}}"/>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4" style="width: 100%; border: 1px solid #ccc; padding: 15px; background:#cfcfcf;">
                    <p style="width: 100%; text-align:center;"><b>Precio total: </b>{{number_format($order->total * 1.21 - ($order->total * 1.21 * 0.25), 2, '.', '')}}€</p>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

