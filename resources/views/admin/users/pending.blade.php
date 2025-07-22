@extends('layouts.admin')

@section('title') Fichas Pendientes @endsection

@section('content')

<div class="">
    <div class="">
        <div class="card">
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

                                $randomImage = \App\Models\Image::where('user_id', $u->id)
                                    ->inRandomOrder()
                                    ->first();
                            @endphp
                            <div class="col-md-1">
                                <div class="image-container">
                                    <div class="franja">
                                        <p>{{ Str::limit($u->nickname, 11) }}</p>
                                    </div>
                                    @if(is_object($frontimage))
                                        @if(!is_null($frontimage->route_gif))
                                            <img src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @else
                                            <img src="{{ route('home.imageget', ['filename' => $frontimage->route_frontimage]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @endif
                                    @else
                                        @if(is_object($randomImage))
                                            @if(!is_null($randomImage->route_gif))
                                                <img src="{{ route('home.gifget', ['filename' => $randomImage->route_gif]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                            @else
                                            <img src="{{ route('home.imageget', ['filename' => $randomImage->route_frontimage]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                            @endif
                                        @else
                                        <img src="{{ asset('images/user.jpg') }}" class="img-fluid" alt="Usuario sin imagen">
                                        @endif
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
                                        <a title="Cambiar estado cuenta" href="#" data-toggle="modal" data-target="#cambiar-estado-cuenta-{{$u->id}}" class="icon">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @include('modals.admin.modal_asignar_paquete')
                            @include('modals.admin.modal_ver_perfil')
                            @include('modals.admin.user.modal_editar_status')
                            @include('modals.admin.modal_historial_paquete')
                            @include('modals.admin.modal_cambiar_estado_cuenta')
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .franja {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(40, 40, 40, 0.5); /* Gris semi-transparente */
        color: white; /* Color del texto */
        text-align: center;
        padding: 5px 0; /* Espaciado dentro de la franja */
        height: 30px; /* Reducido de 38px a 30px */
        z-index: 3;
    }

    .franja p {
        margin: 0;
        line-height: 20px;
        font-size: 16px; /* Reducido de 20px a 16px */
        color: white; /* Color del texto */
    }

    .image-container {
        position: relative;
        flex: 1 0 calc(8.33%); /* 100% / 12 columnas */
        margin: 0;
        color: #fff;
        cursor: pointer;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #000;
        max-width: 150px; /* Ajustado para 12 columnas */
    }

    /* Ajustamos el tamaño de la columna */
    .col-md-1 {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 150px;
        justify-content: center;
    }

    .row {
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start; /* Alinear a la izquierda */
        gap: 0; /* Sin espacio entre fichas */
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
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        height: 54px; /* 2 filas x 24px + margen */
        width: 78px;  /* 3 columnas x 18px + margen */
    }

    .overlay.visible {
        display: flex;
    }

    .icon {
        color: white;
        font-size: 18px;
        margin: 3px;
        transition: transform 0.2s ease;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon:hover {
        transform: scale(1.2);
        color: white;
    }

    .image-darkened {
        filter: brightness(0.4);
    }

    /* 3 iconos por fila, 2 filas */
    .overlay .icon {
        box-sizing: border-box;
        display: inline-flex;
    }
</style>

<script>
    $(document).ready(function() {
        $('.image-container').hover(
            function() {
                // Mouse enter
                $(this).find('img').addClass('image-darkened');
                $(this).find('.overlay').addClass('visible').fadeIn(300);
            },
            function() {
                // Mouse leave
                $(this).find('img').removeClass('image-darkened');
                $(this).find('.overlay').removeClass('visible').fadeOut(300);
            }
        );
    });
</script>

@endsection