@extends('layouts.app')

@section('title') Inicio @endsection

@section('content')
<div id="page-wrapper background-transparent">

    <main role="main" style="margin-top:-70px;">
        <!-- Content -->
        <article>
            <div class="section background-transparent">
                <div class="line text-center">
                    <h1
                        class="text-white text-s-size-30 text-m-size-40 text-l-size-headline text-thin text-line-height-1">Las chicas</h1>
                    <p class="margin-bottom-0 text-size-16 text-white">más calientes de tu zona</p>
                </div>
            </div>
            <div class=" mt-5 container_mobile">

                <div class="gallery">
                    @foreach ($users as $i=>$user)
                        @if(count($user->images) > 0)
                            @php
                                $image = \App\Models\Image::where('user_id', $user->id)
                                    ->whereNotNull('frontimage')
                                    ->first();
                                
                                if (!is_object($image)) {
                                    $image = \App\Models\Image::where('user_id', $user->id)->orderBy('id', 'asc')->first();
                                }
                            @endphp
                            
                            <a href="{{ route('account.get', ['nickname' => $user->nickname]) }}">
                                <div class="gallery-item image-hover-zoom" tabindex="0">
            
                                    <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                                        class="gallery-image" alt="">
            
                                    <div class="gallery-item-info">
            
                                        <ul>
                                            <li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i
                                                    class="fas fa-eye" aria-hidden="true"></i> {{56 * ($i+2)}}</li>
                                            {{--
                                            <li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i
                                                    class="fas fa-comment" aria-hidden="true"></i> 2</li> --}}
                                        </ul>
            
                                    </div>
            
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
        
            </div>
        </article>
    </main>
</div>
<style>
    /*

    All grid code is placed in a 'supports' rule (feature query) at the bottom of the CSS (Line 310). 
            
    The 'supports' rule will only run if your browser supports CSS grid.

    Flexbox and floats are used as a fallback so that browsers which don't support grid will still recieve a similar layout.

    */

    /* Base Styles */

    :root {
        font-size: 10px;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    body {
        font-family: "Open Sans", Arial, sans-serif;
        min-height: 100vh;
        color:#fff;
        background: #111;
        padding-bottom: 3rem;
    }

    img {
        display: block;
    }

    .img_profile {
        max-width: 280px;
    }

    .container {
        max-width: 93.5rem;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .btn {
        display: inline-block;
        font: inherit;
        background: none;
        border: none;
        color: inherit;
        padding: 0;
        cursor: pointer;
    }

    .btn:focus {
        outline: 0.5rem auto #4d90fe;
    }

    .visually-hidden {
        position: absolute !important;
        height: 1px;
        width: 1px;
        overflow: hidden;
        clip: rect(1px, 1px, 1px, 1px);
    }

    /* Profile Section */

    .profile {
        padding: 5rem 0;
    }

    .profile::after {
        content: "";
        display: block;
        clear: both;
    }

    .container_mobile {
        margin-top: 20px!important;
        padding:0!important;
        width:100%!important;

    }

    .profile-image {
        float: left;
        width: calc(33.333% - 1rem);
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 3rem;
    }

    .profile-image img {
       
    }

    .profile-user-settings,
    .profile-stats,
    .profile-bio {
        float: left;
        width: calc(66.666% - 2rem);
    }

    .profile-user-settings {
        margin-top: 1.1rem;
    }

    .profile-user-name {
        display: inline-block;
        font-size: 3.2rem;
        font-weight: 300;
    }

    .profile-edit-btn {
        font-size: 1.4rem;
        line-height: 1.8;
        border: 0.1rem solid #dbdbdb;
        border-radius: 0.3rem;
        padding: 0 2.4rem;
        margin-left: 2rem;
        color:#fff;
    }

    .profile-edit-btn:hover, .profile-settings-btn {
        color:#fff!important;
    }

    .profile-settings-btn {
        font-size: 2rem;
        margin-left: 1rem;
    }

    .profile-stats {
        margin-top: 2.3rem;
    }

    .profile-stats li {
        display: inline-block;
        font-size: 1.6rem;
        line-height: 1.5;
        margin-right: 4rem;
        cursor: pointer;
    }

    .profile-stats li:last-of-type {
        margin-right: 0;
    }

    .profile-bio {
        font-size: 1.6rem;
        font-weight: 400;
        line-height: 1.5;
        margin-top: 2.3rem;
    }

    .profile-real-name,
    .profile-stat-count,
    .profile-edit-btn {
        font-weight: 600;
    }

    /* Gallery Section */

    .gallery {
        display: flex;
        flex-wrap: wrap;
        margin: -1rem -1rem;
        padding-bottom: 3rem;
    }



    .gallery-item:hover .gallery-item-info,
    .gallery-item:focus .gallery-item-info {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
    }

    .gallery-item-info {
        display: none;
    }

    .gallery-item-info li {
        display: inline-block;
        font-size: 1.7rem;
        font-weight: 600;
    }

    .gallery-item-likes {
        margin-right: 2.2rem;
    }

    .gallery-item-type {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2.5rem;
        text-shadow: 0.2rem 0.2rem 0.2rem rgba(0, 0, 0, 0.1);
    }

    .buttons {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
    }

    .buttons button {
        width: 100%;
    }

    .buttons button:first-child {
        color: #F65807;
        border: 1px solid #F65807!important;
    }

    .buttons button:first-child:hover {
        color: #F65807!important;
    }

    .fa-clone,
    .fa-comment {
        transform: rotateY(180deg);
    }

    

    .gallery-item {
        position: relative;
        flex: 1 0 calc(33.333% - 1rem); /* Ajusta el ancho base del contenedor */
        margin: 1rem;
        color: #fff;
        cursor: pointer;
        width: 100%; /* La anchura ocupará todo el espacio disponible */
        padding-top: 177.78%; /* Proporción 16:9 (100 / 9 * 16) */
        overflow: hidden; /* Oculta el contenido que sobresalga */
        display: flex;
        justify-content: center; /* Centra la imagen horizontalmente */
        align-items: center; /* Centra la imagen verticalmente */
    }

    .gallery-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; /* Hace que la imagen ocupe todo el ancho del contenedor */
        height: 100%; /* La altura también ocupa todo el contenedor */
        object-fit: cover; /* Escala la imagen manteniendo sus proporciones */
        object-position: center; /* Centra la imagen dentro del contenedor */
    }

    @media screen and (max-width: 1280px) {
        .gallery-item {
            flex: 1 0 calc(33.333% - 1rem); /* Tres elementos por fila */
            padding-top: 177.78%; /* Proporción 16:9 (más alargada) */
            margin: 1rem;
            position: relative;
            overflow: hidden; /* Oculta cualquier parte de la imagen que sobresalga */
        }
        
        .gallery-image {
            width: 100%; /* La imagen ocupa todo el ancho del contenedor */
            height: 100%; /* La imagen cubre todo el área disponible */
            object-fit: cover; /* Escala la imagen para llenar el contenedor */
            object-position: center; /* Centra la imagen en el contenedor */
        }

        .container_mobile {
            margin: 0 !important;
            margin-top: 20px !important;
            padding: 0 !important;
            width: 100% !important;
        }
    }


    @media screen and (max-width: 720px) {
        .container_mobile {
            margin:0!important;
            margin-top: 20px!important;
            padding:0!important;
            width:100%!important;

        }
    }




    /* Loader */

    .loader {
        width: 5rem;
        height: 5rem;
        border: 0.6rem solid #999;
        border-bottom-color: transparent;
        border-radius: 50%;
        margin: 0 auto;
        animation: loader 500ms linear infinite;
    }

    /* Media Query */

    @media screen and (max-width: 40rem) {
        .profile {
            display: flex;
            flex-wrap: wrap;
            padding: 4rem 0;
        }

        .profile::after {
            display: none;
        }

        .profile-image,
        .profile-user-settings,
        .profile-bio,
        .profile-stats {
            float: none;
            width: auto;
        }

        .profile-image img {
            width: 7.7rem;
        }

        .profile-user-settings {
            flex-basis: calc(100% - 10.7rem);
            display: flex;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .profile-user-name {
            font-size: 2.2rem;
        }

        .profile-edit-btn {
            order: 1;
            padding: 0;
            text-align: center;
            margin-top: 1rem;
        }

        .profile-edit-btn {
            margin-left: 0;
        }

        .profile-bio {
            font-size: 1.4rem;
            margin-top: 1.5rem;
        }

        .profile-edit-btn,
        .profile-bio,
        .profile-stats {
            flex-basis: 100%;
        }

        .profile-stats {
            order: 1;
            margin-top: 1.5rem;
        }

        .profile-stats ul {
            display: flex;
            text-align: center;
            padding: 1.2rem 0;
            border-top: 0.1rem solid #dadada;
            border-bottom: 0.1rem solid #dadada;
        }

        .profile-stats li {
            font-size: 1.4rem;
            flex: 1;
            margin: 0;
        }

        .profile-stat-count {
            display: block;
        }
    }

    /* Spinner Animation */

    @keyframes loader {
        to {
            transform: rotate(360deg);
        }
    }

    /*

    The following code will only run if your browser supports CSS grid.

    Remove or comment-out the code block below to see how the browser will fall-back to flexbox & floated styling. 

    */

    @supports (display: grid) {
        .profile {
            display: grid;
            grid-template-columns: 1fr 2fr;
            grid-template-rows: repeat(3, auto);
            grid-column-gap: 3rem;
            align-items: center;
        }

        .profile-image {
            grid-row: 1 / -1;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            
        }

    .profile-image,
    .profile-user-settings,
    .profile-stats,
    .profile-bio,
    .gallery-item,
    .gallery {
        width: auto;
        margin: 0;
    }

    @media (max-width: 40rem) {
        .profile {
            grid-template-columns: auto 1fr;
            grid-row-gap: 1.5rem;
        }

        .profile-image {
            grid-row: 1 / 2;
        }

        .profile-user-settings {
            display: grid;
            grid-template-columns: auto 1fr;
            grid-gap: 1rem;
        }

        .profile-edit-btn,
        .profile-stats,
        .profile-bio {
            grid-column: 1 / -1;
        }

        .profile-user-settings,
        .profile-edit-btn,
        .profile-settings-btn,
        .profile-bio,
        .profile-stats {
            margin: 0;
        }
    }

        /* Media query for max-width 800px */
        @media (max-width: 1440px) {
            .gallery {
                grid-template-columns: repeat(8, 1fr);  /* 3 items per row */
            }
        }
        @media (max-width: 1280px) {
            .gallery {
                grid-template-columns: repeat(6, 1fr);  /* 3 items per row */
            }
        }
        /* Media query for max-width 800px */
        @media (max-width: 800px) {
            main {
                margin-top: 0px!important;
            }

            .gallery {
                grid-template-columns: repeat(4, 1fr);  /* 3 items per row */
            }
        }

        /* Media query for max-width 700px */
        @media (max-width: 600px) {
            .gallery {
                grid-template-columns: repeat(3, 1fr);  /* 3 items per row */
            }
        }
    }

</style>

@endsection