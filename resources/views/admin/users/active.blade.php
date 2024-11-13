@extends('layouts.admin')

@section('title') Fichas Activas @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Fichas Activas en <b>{{ ucfirst(\Cookie::get('city')) ?? 'Barcelona' }}</b></h5>
            <div class="card-body">
                <table class="table mt-4">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Ficha</th>
                        <th>DNI</th>
                        <th>Telefono</th>
                        <th>Visible</th>
                        <th>Online</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            @if(\Cookie::get('city') != "todas")
                                @php 
                                    $city = \App\Models\City::where('name', \Cookie::get('city') ?? 'Barcelona')->first();
                                    $city_user = \App\Models\CityUser::where('user_id', $u->id)->where('city_id', $city->id)->first(); 
                                @endphp
                            @else
                                @php $city_user = null; @endphp
                            @endif
                            @if(is_object($city_user) || \Cookie::get('city') == "todas")
                                <tr>
                                    <td>{{$u->full_name}}</td>
                                    <td>{{$u->nickname}}</td>
                                    <td>{{$u->dni}}</td>
                                    <td>{{$u->phone}}</td>
                                    <td>{{$u->visible ? "Si" : "No"}}</td>
                                    <td>{{$u->online ? "Si" : "No"}}</td>
                                    <td>
                                        <a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'todas']) }}" style="font-size: 18px; color:black;">
                                            <i class="fa-solid fa-image"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#ver-perfil-{{$u->id}}" style="font-size: 18px; color:black;">
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                        <a href="#" style="font-size: 18px; color:black;">
                                            <i class="fa-solid fa-euro-sign"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#editar-status-{{$u->id}}" style="font-size: 18px; color:black;">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                                @include('modals.admin.modal_ver_perfil')
                                @include('modals.admin.user.modal_editar_status')
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')



@endsection

@section('css')



@endsection