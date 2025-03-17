@extends('layouts.admin')

@section('title') Fichas Activas @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Fichas Activas en <b>{{ ucfirst(\Cookie::get('city')) ?? 'Barcelona' }}</b></h5>
            <div class="card-body">
                <div class="row">
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
                            @php 
                                $frontimage = \App\Models\Image::where('user_id', $u->id)
                                    ->whereNotNull('frontimage')
                                    ->first();
                            @endphp
                            <div class="col-md-3 mb-4">
                                <div class="image-container">
                                    @if(is_object($frontimage))
                                        @if(!is_null($frontimage->route_gif))
                                            <img src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @else
                                            <img src="{{ route('home.imageget', ['filename' => $frontimage->route_frontimage]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/user.jpg') }}" class="img-fluid" alt="Usuario sin imagen">
                                    @endif
                                    <div class="overlay">
                                        <a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'todas']) }}" class="icon">
                                            <i class="fa-solid fa-image"></i>
                                        </a>
                                        <a title="Ver Perfil" href="#" data-toggle="modal" data-target="#ver-perfil-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                        <a title="Asignar Paquete" href="#" data-toggle="modal" data-target="#asignar-paquete-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-archive"></i>
                                        </a>
                                        <a title="Editar Estado" href="#" data-toggle="modal" data-target="#editar-status-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a title="Historial Paquete" href="#" data-toggle="modal" data-target="#historial-paquete-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </a>
                                        <a title="Hacer disponible" href="#" data-toggle="modal" data-target="#hacer-disponible-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-check"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @include('modals.admin.modal_asignar_paquete')
                            @include('modals.admin.modal_ver_perfil')
                            @include('modals.admin.user.modal_editar_status')
                            @include('modals.admin.modal_historial_paquete')
                            @include('modals.admin.modal_hacer_disponible')
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-container {
        position: relative;
        flex: 1 0 calc(25%); /* Eliminado el margen del cálculo */
        margin: 0; /* Eliminado el margen */
        color: #fff;
        cursor: pointer;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #000;
        max-width: 180px; /* Ajustado el ancho máximo */
    }

    /* Eliminamos los estilos de espaciado de la columna */
    .col-md-3 {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 180px;
    }

    /* Ajustamos el contenedor de la fila */
    .row {
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start; /* Cambiado a flex-start para que no haya espacios */
        gap: 0; /* Aseguramos que no haya espacio entre elementos */
    }

    /* Ajustamos el contenedor principal */
    .card-body {
        padding: 0;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Hace que la imagen cubra todo el contenedor */
        object-position: center;
        transition: all 0.3s ease;
    }

    .overlay {
        display: none;  /* Oculto por defecto */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        display: none;  /* Aseguramos que esté oculto */
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 15px;  /* Aumentado el espacio entre iconos */
        padding: 15px;  /* Aumentado el padding */
        width: 80%;  /* Ancho relativo al contenedor */
    }

    .overlay.visible {
        display: grid;
    }

    .icon {
        color: white;
        font-size: 24px;  /* Iconos más grandes */
        transition: transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;  /* Espacio alrededor de los iconos */
    }

    .icon:hover {
        transform: scale(1.2);
        color: white;
    }

    .image-darkened {
        filter: brightness(0.4);
    }
</style>

<script>
    $(document).ready(function() {
        // Aseguramos que los overlays estén ocultos al cargar
        $('.overlay').hide();
        
        $('.image-container').hover(
            function() {
                // Mouse enter
                $(this).find('img').addClass('image-darkened');
                $(this).find('.overlay').addClass('visible').stop().fadeIn(300);
            },
            function() {
                // Mouse leave
                $(this).find('img').removeClass('image-darkened');
                $(this).find('.overlay').removeClass('visible').stop().fadeOut(300);
            }
        );
    });
</script>

@endsection