@extends('layouts.app')

@section('title') Carrito @endsection

@section('content')
<section class="section" id="product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="px-4 px-lg-0">

                    <div class="pb-5">
                        <form method="POST" autocomplete="off">
                            <div class="container append">
                                <div class="row">
                                    <div class="col-lg-12 p-5 bg-white rounded mb-5"
                                        style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">

                                        @if(count($cart))
                                        <!-- Shopping cart table -->
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="border-0 bg-light">
                                                            <div class="p-2 px-3 text-uppercase">Producto</div>
                                                        </th>
                                                        <th scope="col" class="text-center border-0 bg-light">
                                                            <div class="py-2 text-uppercase">Precio</div>
                                                        </th>
                                                        <th scope="col" class="text-center border-0 bg-light">
                                                            <div class="py-2 text-uppercase">Cantidad</div>
                                                        </th>
                                                        <th scope="col" class="text-center border-0 bg-light">
                                                            <div class="py-2 text-uppercase">Acciones</div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $subtotal = 0;
                                                    @endphp
                                                    @foreach ($cart as $c)
                                                    
                                                    @php
                                                        $subtotal += $c->product->price * $c->quantity;
                                                    @endphp
                                                    <tr>
                                                        <th scope="row" class="border-0">
                                                            <div class="p-2">
                                                                <img src="{{url('/get-image', ['filesystem' => 'products', 'filename' => json_decode($c->product->images, true)[0]])}}"
                                                                    alt="Diavla Hookah - {{$c->product->name}}" width="70"
                                                                    class="img-fluid rounded shadow-sm">
                                                                <div class="ml-3 d-inline-block align-middle">
                                                                    <h5 class="mb-0"> <a href="#"
                                                                            class="text-dark d-inline-block align-middle">{{$c->product->name}}</a>
                                                                    </h5><span
                                                                        class="text-muted font-weight-normal font-italic d-block">Categoría:
                                                                        {{$c->product->category->name}}</span>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <td class="text-center border-0 align-middle">
                                                            {{$c->product->price}}€</td>
                                                        <td class="text-center border-0 align-middle">{{$c->quantity}}</td>
                                                        <td class="text-center border-0 align-middle"><a href="{{route('cart.delete', ['id' => \Crypt::encryptString($c->id)])}}"
                                                                class="text-dark"><i class="fa fa-trash"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- End -->
                                    </div>
                                </div>

                                <div class="row py-5 p-4 bg-white rounded"
                                    style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
                                    <div class="col-lg-6">
                                        <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Código de descuento
                                        </div>
                                        <div class="p-4">
                                            <p class="font-italic mb-4">Si tienes un código de descuento, aplícalo debajo.
                                            </p>
                                            <div class="input-group mb-4 border p-2">
                                                <input style="height:100%;" type="text" placeholder="Aplicar código"
                                                    aria-describedby="button-addon3" class="code form-control border-0">
                                                <div class="input-group-append border-0">
                                                    <button id="button-addon3" type="button" class="bt-code"><i
                                                            class="fa fa-gift mr-2"></i>Aplicar código</button>
                                                </div>
                                            </div>
                                            <p class="codigo_existe w-100 text-success text-center d-none">¡Código de
                                                descuento aplicado!</p>
                                            <p class="codigo_no_existe w-100 text-danger text-center d-none">El código
                                                introducido no existe o ha expirado.</p>
                                        </div>
                                        <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Instrucciones al
                                            vendedor</div>
                                        <div class="p-4">
                                            <p class="font-italic mb-4">Si tienes alguna información para el vendedor puedes
                                                dejarla a continuación.</p>
                                            <textarea name="" cols="30" rows="2" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Resumen del pedido
                                        </div>
                                        <div class="p-4">
                                            <p class="font-italic mb-4">Los costos de envío y adicionales se calculan en
                                                función de los valores que haya ingresado.</p>
                                            <ul class="list-unstyled mb-4">
                                                <li class="d-flex justify-content-between py-3 border-bottom">
                                                    Subtotal<strong class="subtotal">{{number_format($subtotal,
                                                        2)}}€</strong></li>
                                                <li class="d-none justify-content-between py-3 border-bottom descuento">
                                                    Descuento<strong class="descuento_text"></strong></li>
                                                <li class="d-flex justify-content-between py-3 border-bottom">Gastos de
                                                    envío<strong class="envio">0.00€</strong></li>
                                                <li class="d-flex justify-content-between py-3 border-bottom">IVA
                                                    (21%)<strong class="iva">{{number_format(($subtotal*1.21)-$subtotal,
                                                        2)}}€</strong></li>
                                                <li class="d-flex justify-content-between py-3 border-bottom">
                                                    <strong>Total</strong>
                                                    <h5 class="font-weight-bold total_importe">
                                                        {{number_format($subtotal*1.21, 2)}}€</h5>
                                                </li>
                                            </ul>
                                            <div class="total">
                                                <div class="main-border-button" style="text-align: center;">
                                                    <a class="w-100 d-block pay" style="cursor: pointer;">Proceder con el pago</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <p style="width: 100%; text-align: center; font-size: 14px;"></p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/jquery-2.1.0.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $(".bt-code").on("click", function(){
            const name = $(".code").val();

            const url = '{{url('cart/get-discount')}}' +'/'+ name;
            $.ajax({
                url: url,
                method: 'GET'
            }).done(function (res) {
                const discount = JSON.parse(res);
                if(discount.status === 200){
                    const disc = discount.discount;
                    $(".codigo_no_existe").addClass('d-none');
                    $(".codigo_existe").removeClass('d-none');
                    $(".descuento").removeClass("d-none").addClass('d-flex');
                    $(".descuento_text").text(`${disc.discount}%`);
                } else {
                    $(".codigo_no_existe").removeClass('d-none');
                    $(".codigo_existe").addClass('d-none');
                    $(".descuento").addClass("d-none").removeClass('d-flex');
                    $(".descuento_text").text(0);
                }

                calculate();
            });
        });

        $(".pay").on("click", function(){
            const reference = Math.random().toString(36).substring(2,12).toUpperCase();
            const name = $(".code").val();
            const csrf_token = $('meta[name="csrf-token"]').attr('content');
            const urlForm = `{{ route('order.create') }}`;

            $(this).removeClass('d-block');
            $(this).addClass('d-none');
            $(".append").append(`<form class="formPay" method="POST" autocomplete="off" action="${urlForm}"><input type="hidden" name="_token" value="${csrf_token}"><input type="hidden" name="reference" value="${reference}"><input type="hidden" name="discount_name" value="${name}"><div class="row mt-4 p-5 bg-white rounded mb-5 d-flex flex-column" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;"><p class="w-100 text-center">Nombre del destinatario: <b>Diavla Hookah SL</b></p><p class="w-100 text-center">IBAN: <b>ES8200810335090001749783</b></p><p class="w-100 text-center">Referencia (incluir): <b>${reference}</b></p><p class="mt-2 w-100 text-center">Después de enviar la transferencia con el importe del total pulse el botón para finalizar el pedido.</p> <div class="total mt-2"> <div class="main-border-button" style="text-align: center;"> <a href="#" class="w-100 d-block finish">Finalizar pedido</a> </div> </div></div></form>`);
        
            $(".finish").on("click", function(){
                $(".formPay").submit();
            });
        });

        
        function calculate() {
            let total = 0;
            
            const subtotal = $(".subtotal").text().split("€")[0];
            const descuento = $(".descuento_text").text().split("%")[0];
            const envio = $(".envio").text().split("€")[0];
            const iva = $(".envio").text().split("€")[0];

            const descuento_aplicado = subtotal - (subtotal * descuento) / 100;
            total = descuento_aplicado * 1.21 + envio;

            $(".total_importe").text(`${parseFloat(total).toFixed(2)}€`);
        };

    });
</script>
@endsection