@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center w-75">
        <p class="w-100 text-center" style="font-size:25px; color:#fff;">
            Productos ({{$products->count()}})
            <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-product"><i class="fa-solid fa-square-plus"></i></a>
        </p>

        <div class="w-100 mt-4">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Categoría</th>
                        <th class="text-center" scope="col">Subcategoría</th>
                        <th class="text-center" scope="col">Nombre</th>
                        <th class="text-center" scope="col">Descripción</th>
                        <th class="text-center" scope="col">Precio</th>
                        <th class="text-center" scope="col">Unidades</th>
                        <th class="text-center" scope="col">¿Descontinuado?</th>
                        <th class="text-center" scope="col">Imágenes</th>
                        <th class="text-center" scope="col">F. Creación</th>
                        <th class="text-center" scope="col">F. Modificación</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $pro)
                    <tr>
                        <th class="text-center" scope="row">{{$pro->id}}</th>
                        <td class="text-center">{{$pro->category->name}}</td>
                        <td class="text-center">
                            @if(!is_null($pro->subcategory_id))
                            {{$pro->subcategory->name}}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">{{$pro->name}}</td>
                        <td class="text-center">{{$pro->description}}</td>
                        <td class="text-center">{{$pro->price}}</td>
                        <td class="text-center">{{$pro->units}}</td>
                        <td class="text-center">
                            @if(!is_null($pro->discontinued))
                                Si
                            @else
                                No
                            @endif
                        </td>
                        <td class="text-center">
                            @foreach (json_decode($pro->images, true) as $im)
                               <a href="{{ asset('storage/products/'.$im) }}" target="_blank"> 
                                <img width="50" src="{{url('/get-image', ['filesystem' => 'products', 'filename' => $im])}}"/>
                               </a>
                            @endforeach
                        </td>
                        <td class="text-center">{{\Carbon\Carbon::parse($pro->created_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($pro->updated_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#update-product-{{$pro->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#delete-product-{{$pro->id}}" style="font-size: 20px;" class="text-danger">
                                <i class="fa-solid fa-square-xmark"></i>
                            </a>
                        </td>
                        @include('admin.products.update')
                        @include('admin.products.delete')
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('admin.products.create')
@endsection