@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center w-75">
        <p class="w-100 text-center" style="font-size:25px; color:#fff;">
            Pedidos ({{$orders->count()}})
        </p>

        <div class="w-100 mt-4">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Usuario</th>
                        <th class="text-center" scope="col">Referencia</th>
                        <th class="text-center" scope="col">Total sin impuestos (€)</th>
                        <th class="text-center" scope="col">IVA (€)</th>
                        <th class="text-center" scope="col">Descuento (%)</th>
                        <th class="text-center" scope="col">Descuento (€)</th>
                        <th class="text-center" scope="col">Total (€)</th>
                        <th class="text-center" scope="col">Estado</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <th class="text-center" scope="row">{{$order->id}}</th>
                        <td class="text-center" scope="row">{{$order->user->email}}</td>
                        <td class="text-center" scope="row">{{$order->reference}}</td>
                        <td class="text-center" scope="row">{{$order->total}}</td>
                        <td class="text-center" scope="row">{{$order->total * 0.21}}</td>
                        <td class="text-center" scope="row">@if(!is_null($order->discount)) {{$order->discount}} @else 0 @endif</td>
                        <td class="text-center" scope="row">{{number_format(($order->total * 1.21 * 0.25), 2, '.', '')}}</td>
                        <td class="text-center" scope="row">{{number_format($order->total * 1.21 - ($order->total * 1.21 * 0.25), 2, '.', '')}}</td>
                        
                        @switch($order->status)
                            @case(0)
                                <td class="text-center" scope="row" style="color:orange;">Pendiente</td>
                            @break
                            
                            @case(1)
                                <td class="text-center" scope="row" style="color:green;">Finalizado</td>
                            @break

                            @case(2)
                                <td class="text-center" scope="row" style="color:yellow;">Pendiente envío</td>
                            @break

                            @case(3)
                                <td class="text-center" scope="row" style="color:steelblue;">En transporte</td>
                            @break

                            @case(4)
                                <td class="text-center" scope="row" style="color:red;">Cancelado</td>
                            @break
                        @endswitch

                        <td class="text-center" scope="row">
                            <a href="#" data-toggle="modal" data-target="#view-order-{{$order->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#update-order-{{$order->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        @include('admin.orders.view')
                        @include('admin.orders.update')
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection