@extends('layouts.admin')

@section('title') Ciudades y Zonas @endsection

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Ciudades</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <form method="POST" action="{{ route('admin.utilities.cities_save') }}" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="city" class="form-label">Nombre de la ciudad</label>
                                <input type="text" class="form-control" id="city" aria-describedby="city" name="name" placeholder="asdf..." required>
                            </div>
                              <button type="submit" class="btn">Guardar</button>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <table id="citiesTable" class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $c)
                                    <tr>
                                        <td>{{$c->id}}</td>
                                        <td>{{ucfirst($c->name)}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#update-city-{{$c->id}}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="{{ route('admin.utilities.cities_delete', ['id' => $c->id]) }}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('modals.admin.city.modal_update_city')
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
            <h5 class="w-100 text-center mt-2">Zonas</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <form method="POST" action="{{ route('admin.utilities.zones_save') }}" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="zone" class="form-label">Nombre de la zona</label>
                                <input type="text" class="form-control" id="zone" aria-describedby="zone" name="name" placeholder="asdf..." required>
                            </div>
                            <div class="form-group">
                                <label for="zone" class="form-label">Ciudad</label>
                                <select class="form-control" id="city_id" name="city" required>
                                    <option hidden selected disabled>
                                        Escoge una ciudad...
                                    </option>
                                    @foreach($cities as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                              <button type="submit" class="btn">Guardar</button>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <table id="zonesTable" class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ciudad</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($zones as $z)
                                    <tr>
                                        <td>{{$z->id}}</td>
                                        <td>{{$z->name}}</td>
                                        <td>{{ucfirst($z->city->name)}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#update-zone-{{$z->id}}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="{{ route('admin.utilities.zones_delete', ['id' => $z->id]) }}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('modals.admin.zone.modal_update_zone')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
<script>
    $( document ).ready(function() {
        $('#citiesTable, #zonesTable').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "language": {
                "sEmptyTable": "No hay datos disponibles en la tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
                "sInfoPostFix": "",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing": "Procesando...",
                "sSearch": "Buscar:",
                "sZeroRecords": "No se encontraron resultados",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
</script>    

@endsection