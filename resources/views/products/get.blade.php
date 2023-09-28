@extends('layouts.app')

@section('title') {{$product->name}} @endsection

@section('content')
<!-- ***** Product Area Starts ***** -->
<section class="section" id="product">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">



                <div class="left-images">
                    @foreach (json_decode($product->images, true) as $img)
                        <img style="width: 80%;" src="{{url('/get-image', ['filesystem' => 'products', 'filename' => $img])}}" alt="Diavla Hookah - {{$product->name}}">
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="right-content">
                    <h4>{{$product->name}}</h4>
                    <span class="price">
                        @if(\Auth::user())
                            {{$product->price}}€
                        @else
                            <a href="#" data-toggle="modal" data-target="#login">Inicia sesión para ver precios</a>
                        @endif
                    </span>
                    <ul class="stars">
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                    </ul>
                    <div class="quote">
                        <i class="fa fa-quote-left"></i>
                        <p>{{$product->description}}</p>
                    </div>
                    @if($product->units <10)
                        <span class="text-danger">¡Quedan pocas unidades!</span>
                    @endif
                    <form id="form" method="POST" action="{{route('cart.add')}}" autocomplete="off">
                        @csrf
                        <div class="quantity-content">
                            <div class="left-content">
                                <h6>Cantidad</h6>
                            </div>
                            <div class="right-content">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus" id="minus">
                                    <input type="number" step="1" min="1" id="quantity" name="quantity" value="1" title="Qty" class="quantity input-text qty text" size="4" pattern="" inputmode="">
                                        <input type="button" value="+" class="plus" id="plus">
                                    <input type="number" value="{{$product->units}}" id="total" hidden />
                                    <input type="number" step="0.01" value="{{$product->price}}" id="price" hidden />
                                    <input type="text" value="{{\Crypt::encryptString($product->id)}}" name="product_id" hidden />
                                </div>
                            </div>
                            <p id="maximo" style="display: none; color:red;">Has alcanzado la cantidad máxima de productos.</p>
                        </div>
                        <div class="total" style="display: flex;
                        flex-direction: column;">
                            @if(\Auth::user())
                            <h4>Total: <span style="font-size: 22px; display:inline-block; font-weight:bold;" id="total_quantity">{{$product->price}}</span>€</h4>
                            <div class="main-border-button w-100">
                                <a id="submit" href="#" style="width: 100%;
                                text-align: center;
                                margin-top: 20px;
                                ">Añadir al carrito</a>
                            </div>
                            @else
                            <h4>Total: <span style="font-size:14px; display:inline-block; font-weight:bold;" id="total_quantity"> <a href="#" data-toggle="modal" data-target="#login">Inicia sesión para ver precios</a></h4>
                            <div class="main-border-button w-100">
                                <a style="cursor:not-allowed;width: 100%;
                                text-align: center;
                                margin-top: 20px;
                                ">Añadir al carrito</a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Product Area Ends ***** -->
<script>
    const minusButton = document.getElementById('minus');
    const plusButton = document.getElementById('plus');
    const inputField = document.getElementById('quantity');
    const total = document.getElementById('total');
    const maximo = document.getElementById('maximo');
    const total_quantity = document.getElementById('total_quantity');
    const price = document.getElementById('price');

    minusButton.addEventListener('click', event => {
        event.preventDefault();
        const currentValue = Number(inputField.value) || 0;
        inputField.value = currentValue>= 2 ? currentValue - 1 : 1;

        if(inputField.value === total.value){
            maximo.style.display = "block"; 
        } else {
            maximo.style.display = "none"; 
        }

        total_quantity.textContent = parseFloat(price.value * inputField.value);
    });

    plusButton.addEventListener('click', event => {
        event.preventDefault();
        const currentValue = Number(inputField.value) || 0;
        inputField.value = currentValue < total.value ? currentValue + 1 : currentValue;

        if(inputField.value === total.value){
            maximo.style.display = "block"; 
        } else {
            maximo.style.display = "none"; 
        }

        total_quantity.textContent = parseFloat(price.value * inputField.value);
    });

    inputField.addEventListener('change', event => {
        const value = parseInt(inputField.value);
        const tt = parseInt(total.value);

        inputField.value = value >= tt ? tt : value;

        if(value >= tt){
            maximo.style.display = "block"; 
        } else {
            maximo.style.display = "none"; 
        }

        total_quantity.textContent = parseFloat(price.value * value);
    });

    const form = document.getElementById('form')

    document.getElementById("submit").addEventListener("click", function () {
        form.submit();
    });

</script>
@endsection
