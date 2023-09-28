@extends('layouts.app')

@section('title') Mis Pedidos @endsection

@section('content')
<section class="section" id="product">
    <div class="main-banner" id="top" style="padding-top: 0px;padding-left: 20px; padding-right:20px; width:100%;">
        <div class="container-fluid">
            <div class="row">
                <h3>Todas las categorías</h3>
                <br>
                <div class="col-lg-12 mt-3">
                    <div class="right-content">
                        <div class="row">
                            @foreach ($categories as $cat)
                                @if(count($cat->products) >0)
                                    <div class="right-first-image" style="width:300px; height:300px;">
                                        <div class="thumb">
                                            <div class="inner-content">
                                                <h4 class="text-white">{{$cat->name}}</h4>
                                                <span>{{$cat->description}}</span>
                                            </div>
                                            <div class="hover-content">
                                                <div class="inner">
                                                    <h4 class="text-white">{{$cat->name}}</h4>
                                                    <p>{{$cat->description}}</p>
                                                    <div class="main-border-button">
                                                        <a href="{{ route('categories.get', ['name' => $cat->name]) }}">Ver más</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img class="image_gray"
                                                src="{{url('/get-image', ['filesystem' => 'categories', 'filename' => $cat->image])}}" style="width:300px; height:300px;">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection