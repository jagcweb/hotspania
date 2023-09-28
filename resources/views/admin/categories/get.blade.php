@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center w-75">
        <p class="w-100 text-center" style="font-size:25px; color:#fff;">
            Categorías ({{$categories->count()}})
            <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-category"><i class="fa-solid fa-square-plus"></i></a>
        </p>

        <div class="w-100 mt-4">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Nombre</th>
                        <th class="text-center" scope="col">Descripción</th>
                        <th class="text-center" scope="col">Imagen</th>
                        <th class="text-center" scope="col">F. Creación</th>
                        <th class="text-center" scope="col">F. Modificación</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                    <tr>
                        <th class="text-center" scope="row">{{$cat->id}}</th>
                        <td class="text-center">{{$cat->name}}</td>
                        <td class="text-center">{{$cat->description}}</td>
                        <td class="text-center">
                            <a href="{{ asset('storage/categories/'.$cat->image) }}" target="_blank"> 
                                <img width="50" src="{{url('/get-image', ['filesystem' => 'categories', 'filename' => $cat->image])}}"/>
                            </a>
                        </td>
                        <td class="text-center">{{\Carbon\Carbon::parse($cat->created_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($cat->updated_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#update-category-{{$cat->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#delete-category-{{$cat->id}}" style="font-size: 20px;" class="text-danger">
                                <i class="fa-solid fa-square-xmark"></i>
                            </a>
                        </td>
                        @include('admin.categories.update')
                        @include('admin.categories.delete')
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('admin.categories.create')
@endsection