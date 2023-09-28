@extends('layouts.app')

@section('title') Mis Pedidos @endsection

@section('content')
<section class="section" id="product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="px-4 px-lg-0">

                    <div class="pb-5">
                        <div class="container append">
                            <div class="row py-5 p-4 bg-white rounded"
                                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
                                <div class="col-lg-12">
                                    <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Mis pedidos
                                    </div>
                                    <div class="p-4">
                                        @foreach ($orders as $i=>$ord)
                                            <div style="padding:20px; border:1px solid #c4c4c4;" @if($i>0) class="mt-3" @endif>
                                                <h5>{{ $ord->reference }} - 
                                                    @switch($ord->status)
                                                        @case(0)
                                                            <span style="color:orange;">Pendiente</span>
                                                        @break
                                                        
                                                        @case(1)
                                                            <span style="color:green;">Finalizado</span>
                                                        @break
                            
                                                        @case(2)
                                                            <span style="color:yellow;">Pendiente envío</span>
                                                        @break
                            
                                                        @case(3)
                                                            <span style="color:steelblue;">En transporte</span>
                                                        @break
                            
                                                        @case(4)
                                                            <span style="color:red;">Cancelado</span>
                                                        @break
                                                    @endswitch
                                                </h5>
                                                <br>
                                                <small class="d-block">{{ \Carbon\Carbon::parse($ord->created_at)->format('d/m/Y H:i') }}</small>
                                                <br>
                                                @foreach ($ord->products as $pr)
                                                    <img width="100" src="{{url('/get-image', ['filesystem' => 'products', 'filename' => json_decode($pr->images, true)[0]])}}"/>
                                                @endforeach
                                                <br>
                                                <br>
                                                <h5><b>{{number_format(($ord->total * 1.21 * 0.25), 2, '.', '')}}€</b></h5>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection