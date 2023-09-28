@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center w-75">
        <p class="w-100 text-center" style="font-size:25px; color:#fff;">
            Descuentos ({{$discounts->count()}})
            <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-discount"><i class="fa-solid fa-square-plus"></i></a>
        </p>

        <div class="w-100 mt-4">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Nombre</th>
                        <th class="text-center" scope="col">% Descuento</th>
                        <th class="text-center" scope="col">Usos</th>
                        <th class="text-center" scope="col">F. Expiración</th>
                        <th class="text-center" scope="col">F. Creación</th>
                        <th class="text-center" scope="col">F. Modificación</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($discounts as $disc)
                    <tr>
                        <th class="text-center" scope="row">{{$disc->id}}</th>
                        <td class="text-center">{{$disc->name}}</td>
                        <td class="text-center">{{$disc->discount}}</td>
                        <td class="text-center">{{$disc->uses}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($disc->expiration_date)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($disc->created_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($disc->updated_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#update-discount-{{$disc->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#delete-discount-{{$disc->id}}" style="font-size: 20px;" class="text-danger">
                                <i class="fa-solid fa-square-xmark"></i>
                            </a>
                        </td>
                        @include('admin.discounts.update')
                        @include('admin.discounts.delete')
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('admin.discounts.create')
@endsection