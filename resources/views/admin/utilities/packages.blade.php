@extends('layouts.admin')

@section('title') Paquetes @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Paquetes</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">

                        <form method="POST" action="{{ route('admin.utilities.packages_save') }}" autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del paquete</label>
                                <input type="text" class="form-control" id="name" placeholder="asdf..." name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="type" required>
                                    <option selected disabled hidden>Escoge un tipo...</option>
                                    <option value="ficha_general">Ficha general</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="precio" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="dias" class="form-label">Días</label>
                                <input type="number" class="form-control" id="dias" name="days" required>
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="status" required>
                                    <option selected disabled hidden>Escoge un estado...</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn">Guardar</button>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <table class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Días</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $p)
                                    <tr>
                                        <td>{{$p->id}}</td>
                                        <td>{{$p->name}}</td>
                                        <td>{{ucfirst(str_replace("_", " ", $p->type))}}</td>
                                        <td>{{$p->price}}</td>
                                        <td>{{$p->days}}</td>
                                        <td>{{ucfirst($p->status)}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#update-package-{{$p->id}}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="{{ route('admin.utilities.packages_delete', ['id' => $p->id]) }}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('modals.admin.package.modal_update_package')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Asignar paquetes a usuarios (activos)</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">

                        <form method="POST" action="{{ route('admin.utilities.packages_users_save') }}" autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Paquete</label>
                                <select class="form-select" id="tipo" name="package_id" required>
                                    <option selected disabled hidden>Escoge un paquete...</option>
                                    @foreach($packages_actives as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Usuario</label>
                                <select class="form-select" id="tipo" name="user_id" required>
                                    <option selected disabled hidden>Escoge un usuario...</option>
                                    @foreach($users as $u)
                                        <option value="{{$u->id}}">{{$u->full_name}} ({{$u->nickname}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn">Guardar</button>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <table class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Paquete</th>
                                <th>Usuario</th>
                                <th>Fecha asignación</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($packages_users as $pu)
                                    <tr>
                                        <td>{{$pu->id}}</td>
                                        <td>{{$pu->package->name}}</td>
                                        <td>{{$pu->user->fullname}} ({{$pu->user->nickname}})</td>
                                        <td>{{ \Carbon\Carbon::parse($pu->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.utilities.packages_users_delete', ['id' => $pu->id]) }}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- Bootstrap Tables js -->

@endsection

@section('css')



@endsection