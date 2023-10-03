@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center w-75">
        <p class="w-100 text-center" style="font-size:25px; color:#fff;">
            Usuarios ({{$users->count()}})
        </p>

        <div class="w-100 mt-4">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">ID</th>
                        <th class="text-center" scope="col">Nombre</th>
                        <th class="text-center" scope="col">Apellidos</th>
                        <th class="text-center" scope="col">Email</th>
                        <th class="text-center" scope="col">Verificación Email</th>
                        <th class="text-center" scope="col">Baneado</th>
                        <th class="text-center" scope="col">F. Creación</th>
                        <th class="text-center" scope="col">F. Modificación</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $us)
                    <tr>
                        <th class="text-center" scope="row">{{$us->id}}</th>
                        <td class="text-center">{{$us->name}}</td>
                        <td class="text-center">{{$us->surname}}</td>
                        <td class="text-center">{{$us->email}}</td>
                        <td class="text-center">@if(!is_null($us->email_verified_at)) {{\Carbon\Carbon::parse($us->email_verified_at)->format('d/m/Y H:i')}} @else NO Verificado @endif</td>
                        <td class="text-center">@if(!is_null($us->banned)) SI @else NO @endif</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($us->created_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">{{\Carbon\Carbon::parse($us->updated_at)->format('d/m/Y H:i')}}</td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#update-user-{{$us->id}}" style="font-size: 20px;" class="text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#verify-user-{{$us->id}}" style="font-size: 20px;" @if(is_null($us->email_verified_at)) class="text-success" @else class="text-danger" @endif>
                                @if(is_null($us->email_verified_at)) <i class="fa-solid fa-user-check"></i> @else <i class="fa-solid fa-user-xmark"></i> @endif
                            </a>

                            <a href="#" data-toggle="modal" data-target="#ban-user-{{$us->id}}" style="font-size: 20px;" @if(is_null($us->banned)) class="text-danger" @else class="text-success" @endif>
                                <i class="fa-solid fa-user-slash"></i>
                            </a>
                        </td>
                        @include('admin.users.update')
                        @include('admin.users.ban')
                        @include('admin.users.verify')
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection