@extends('layouts.app')

@section('title') Inicio @endsection

@section('content')
    @php $categories = \App\Models\Category::orderBy('created_at', 'asc')->get(); @endphp
    <div class="main-banner" id="top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-content">
                        <div class="thumb">
                            <div class="inner-content">
                                <h4>Diavla Hookah</h4>
                                <span>Caerás en la tentación</span>
                                <div class="main-border-button">
                                    <a href="{{ route('categories.index') }}">Ver todas la categorías</a>
                                </div>
                            </div>
                            <img src="{{ asset('images/diavla_categories.jpg') }}" alt="Diavla Hookah">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="right-content">
                        <div class="row">
                            @foreach ($categories as $cat)
                            <div class="col-lg-6">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>{{$cat->name}}</h4>
                                            <span>{{$cat->description}}</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>{{$cat->name}}</h4>
                                                <p>{{$cat->description}}</p>
                                                <div class="main-border-button">
                                                    <a href="{{ route('categories.get', ['name' => $cat->name]) }}">Ver más</a>
                                                </div>
                                            </div>
                                        </div>
                                        <img class="image_gray" src="{{url('/get-image', ['filesystem' => 'categories', 'filename' => $cat->image])}}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="novedades">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Novedades</h2>
                        <span>¡Mira nuestros últimos productos!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="men-item-carousel">
                        <div class="owl-men-item owl-carousel">
                            @php $products = \App\Models\Product::latest()->paginate(10); @endphp
                            @foreach ($products as $p)
                                @php $im = json_decode($p->images, true); @endphp
                                <div class="item">
                                    <div class="thumb">
                                        <div class="hover-content">
                                            <ul>
                                                <li><a href="{{route('products.get', ['name' => str_replace(" ", "-", strtolower($p->name))])}}"><i class="fa fa-eye"></i></a></li>
                                                @if(\Auth::user())
                                                <li>
                                                    <form id="form" method="POST" action="{{route('cart.add')}}" style="display: inline-block;">
                                                        @csrf
                                                        <input type="text" value="{{\Crypt::encryptString($p->id)}}" name="product_id" hidden />
                                                        <input type="number" step="1" min="1" name="quantity" value="1" hidden />
                                                        <a id="submit" href="#"><i class="fa fa-shopping-cart"></i></a>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <img src="{{url('/get-image', ['filesystem' => 'products', 'filename' => $im[0]])}}" alt="Diavla Hookah - {{$p->name}}">
                                    </div>
                                    <div class="down-content">
                                        <h4>{{$p->name}}</h4>
                                        @if(\Auth::user())
                                        <span>{{$p->price}}€</span>
                                        @else
                                        <a href="#" data-toggle="modal" data-target="#login">Inicia sesión para ver precios</a>
                                        @endif
                                        <ul class="stars">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php $most_products_sale = \App\Models\TotalSale::orderBy('quantity', 'DESC')->limit(5)->get(); @endphp

    @if(count($most_products_sale)>0)
        <section class="section" id="mas_ventas">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-heading">
                            <h2>Lo más vendido</h2>
                            <span>Lo más top en ventas</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="women-item-carousel">
                            <div class="owl-women-item owl-carousel">
                                @foreach ($most_products_sale as $prod)
                                    <div class="item">
                                        <div class="thumb">
                                            <div class="hover-content">
                                                <ul>
                                                    <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>
                                                    <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>
                                                    <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>
                                            <img src="{{url('/get-image', ['filesystem' => 'products', 'filename' => json_decode($prod->product->images, true)[0]])}}" alt="">
                                        </div>
                                        <div class="down-content">
                                            <h4>{{ $prod->product->name }}</h4>
                                            <span>{{ $prod->product->price }}€</span>
                                            <ul class="stars">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="info">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row text-center">
                        <div class="col-6">
                            <ul>
                                <li>Horario:<br><span>9:00 - 19:00</span></li>
                                <li>Email:<br><span><a href="mailto:diavlahookahspain@gmail.com">diavlahookahspain@gmail.com</a></span></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li>Teléfono:<br><span><a href="callto:+34689759849">+34 689759849</a></span></li>
                                <li>Redes Sociales:<br><span><a href="https://www.instagram.com/diavlahookah/" target="_blank">@diavlahookah</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const form = document.getElementById('form')

        document.getElementById("submit").addEventListener("click", function (e) {
            e.preventDefault();
            form.submit();
        });

    </script>

@endsection