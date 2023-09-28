@extends('layouts.app')

@section('title') Mis Pedidos @endsection

@section('content')
<section class="section" id="products">
    <div class="container">
        <div class="row">

            @if(count($category->subcategories)>0)
                <div class="main-banner" id="top" style="width:100%; margin-top:-50px;">
                    <div class="container-fluid">
                        <div class="row">
                            <h3>Subcategorías de <b>{{ $category->name }}</b></h3>
                            <br>
                            <div class="col-lg-12 mt-3">
                                <div class="right-content">
                                    <div class="row">
                                        @foreach ($category->subcategories as $subcat)
                                            <div class="right-first-image" style="width:300px; height:300px;">
                                                <div class="thumb">
                                                    <div class="inner-content">
                                                        <h4 class="text-white">{{$subcat->name}}</h4>
                                                        <span>{{$subcat->description}}</span>
                                                    </div>
                                                    <div class="hover-content">
                                                        <div class="inner">
                                                            <h4 class="text-white">{{$subcat->name}}</h4>
                                                            <p>{{$subcat->description}}</p>
                                                            <div class="main-border-button">
                                                                <a href="{{ route('categories.get', ['name' => $subcat->name]) }}">Ver más</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <img class="image_gray"
                                                        src="{{url('/get-image', ['filesystem' => 'categories', 'filename' => $category->image])}}" style="width:300px; height:300px;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($category->products)>0)
                <h3 @if(count($category->subcategories) == 0) style="margin-top:100px;" @endif>Productos categoría <b>{{ $category->name }}</b></h3>
                <br>
                <div class="col-lg-12 mt-3 mb-5">
                    <div class="men-item-carousel">
                        <div @if(count($category->products)>=3) class="owl-men-item owl-carousel" @else style="width:100%; display:flex; flex-direction:row;" @endif>
                            @php $products = \App\Models\Product::where('category_id', $category->id)->get(); @endphp
                            @foreach ($products as $i=>$p)
                                <div class="item @if(count($category->products)<3 && $i>0) ml-5 @endif" style="width:320px; height:320px;">
                                    <div class="thumb">
                                        <div class="hover-content">
                                            <ul>
                                                <li><a href="{{route('products.get', ['name' => str_replace(" ", "-", strtolower($p->name))])}}"><i class="fa fa-eye"></i></a></li>
                                                @if(\Auth::user())
                                                <li>
                                                    <form id="form-{{$i}}" method="POST" action="{{route('cart.add')}}" style="display: inline-block;">
                                                        @csrf
                                                        <input type="text" value="{{\Crypt::encryptString($p->id)}}" name="product_id" hidden />
                                                        <input type="number" step="1" min="1" name="quantity" value="1" hidden />
                                                        <a class="submit" id="submit-{{$i}}" href="#"><i class="fa fa-shopping-cart"></i></a>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <img src="{{url('/get-image', ['filesystem' => 'products', 'filename' => json_decode($p->images, true)[0]])}}" alt="Diavla Hookah - {{$p->name}}" style="width:320px; height:320px;">
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
            @endif
        </div>
    </div>
</section>

<script>
    document.getElementByClassName("submit").addEventListener("click", function (e) {
        e.preventDefault();
        form.submit();
    });

</script>

@endsection