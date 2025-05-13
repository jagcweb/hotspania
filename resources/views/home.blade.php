@extends('layouts.app')

@section('title') Inicio @endsection

@section('content')
<div id="page-wrapper background-transparent">

    <main role="main" style="margin-top:-70px;">
        <!-- Content -->
        <article>
            <div class="section background-transparent">
                <div class="stories-container">
                    <a href="{{ route('home', ['filter' => 'disponibles']) }}" class="story-item">
                        <div class="story-circle">
                            <span class="circle-text">Disponibles</span>
                        </div>
                        <span class="story-text">Disponibles</span>
                    </a>
                    <a href="{{ route('home', ['filter' => 'lgtbi']) }}" class="story-item">
                        <div class="story-circle">
                            <span class="circle-text">LGTBI+</span>
                        </div>
                        <span class="story-text">LGTBI+</span>
                    </a>
                    <a href="{{ route('home', ['filter' => 'nuevas']) }}" class="story-item">
                        <div class="story-circle">
                            <span class="circle-text">Nuevas</span>
                        </div>
                        <span class="story-text">Nuevas</span>
                    </a>
                    <div class="story-item">
                        <div class="story-circle">
                            <span class="circle-text">Fotos</span>
                        </div>
                        <span class="story-text">Fotos</span>
                    </div>
                    <a href="{{ route('home', ['filter' => 'ranking']) }}" class="story-item">
                        <div class="story-circle">
                            <span class="circle-text">Ranking</span>
                        </div>
                        <span class="story-text">Ranking</span>
                    </a>
                </div>

                <style>
                    .stories-container {
                        display: flex;
                        gap: 20px;
                        padding: 20px 10px;
                        overflow-x: auto;
                        justify-content: center;
                        width: auto;
                    }

                    .story-item {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 8px;
                        min-width: fit-content;
                    }
        
                    .story-circle {
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: #f36e00;
                        padding: 4px;
                        border: 2px solid #808080;
                        cursor: pointer;
                        transition: transform 0.5s;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .circle-text {
                        color: white;
                        font-weight: bold;
                        font-size: 12px;
                    }
        
                    .story-circle:hover {
                        transform: scale(1.1);
                    }
        
                    .story-text {
                        color: white;
                        font-size: 12px;
                        text-align: center;
                    }

                    @media (max-width: 768px) {
                        .stories-container { 
                            gap: 15px; 
                            width: 100%;
                            justify-content: space-around;
                        }
                        .story-circle {
                            width: 70px;
                            height: 70px;
                        }
                    }

                    @media (max-width: 576px) {
                        .stories-container { gap: 10px; }
                        .story-circle {
                            width: 60px;
                            height: 60px;
                        }
                    }

                    @media (max-width: 400px) {
                        .stories-container { 
                            gap: 8px;
                            padding: 20px 5px;
                        }
                        .story-circle {
                            width: 45px;
                            height: 45px;
                        }
                        .circle-text {
                            font-size: 8px;
                        }
                        .story-text {
                            font-size: 8px;
                        }
                    }

                    @media (max-width: 320px) {
                        .stories-container { 
                            gap: 5px;
                            padding: 20px 2px;
                        }
                        .story-circle {
                            width: 40px;
                            height: 40px;
                        }
                        .circle-text {
                            font-size: 7px;
                        }
                        .story-text {
                            font-size: 7px;
                        }
                    }

                    .availability-indicator {
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        width: 15px;
                        height: 15px;
                        background-color: #2ecc71;
                        border-radius: 50%;
                        border: 2px solid white;
                        z-index: 2;
                        box-shadow: 0 0 4px rgba(0,0,0,0.3);
                    }
                </style>
            </div>

            <div class="container_mobile mt-5">
                <div class="gallery" id="gallery">
                    @include('partials.user-grid')
                </div>
                <div id="loading" style="display: {{ count($users) >= 20 ? 'none' : 'none' }}; text-align: center; padding: 20px; margin: 20px 0;">
                    <div class="modern-loader"></div>
                </div>
            </div>
        </article>
    </main>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json'
    }
});

let page = 1;
const loading = document.getElementById('loading');
const gallery = document.getElementById('gallery');
let isLoading = false;
let hasMore = true;
// Inicializar el array con los IDs pasados desde el controlador
let loadedUserIds = @json($loadedUserIds ?? []);

function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

const loadMoreUsers = () => {
    if (isLoading || !hasMore) return;
    
    const galleryItems = document.querySelectorAll('.gallery-item').length;
    if (galleryItems < 20) {
        loading.style.display = 'none';
        return;
    }
    
    isLoading = true;
    if (hasMore) {
        loading.style.display = 'block';
    }

    $.ajax({
        url: `/home/load-more/${page + 1}`,
        method: 'GET',
        data: {
            loaded_users: JSON.stringify(loadedUserIds)
        },
        beforeSend: function() {
            alert('Enviando solicitud AJAX a: /home/load-more/' + (page + 1));
            alert('loadedUserIds: ' + JSON.stringify(loadedUserIds));
        },
        success: function(response) {
            alert('Respuesta recibida');

            try {
                alert('response.html: ' + response.html);
                if (response.html && response.html.trim()) {
                    gallery.insertAdjacentHTML('beforeend', response.html);
                    loadedUserIds = response.loadedUsers;
                    alert('IDs actualizados: ' + JSON.stringify(loadedUserIds));
                    page++;
                    hasMore = response.hasMore;
                } else {
                    alert('No hay HTML en la respuesta');
                    hasMore = false;
                }
                loading.style.display = hasMore ? 'block' : 'none';
            } catch (e) {
                alert('Error en bloque success: ' + e.message);
                hasMore = false;
            }
        },
        error: function(xhr, status, error) {
            alert('Error en la petición AJAX:\nStatus: ' + status + '\nError: ' + error);
            hasMore = false;
            loading.style.display = 'none';
        },
        complete: function() {
            alert('Petición completada');
            isLoading = false;
            if (!hasMore) {
                loading.style.display = 'none';
            }
        }
    });

};

$(window).scroll(function() {
    const galleryItems = document.querySelectorAll('.gallery-item').length;
    if (isElementInViewport(loading) && !isLoading && hasMore && galleryItems >= 20) {
        loadMoreUsers();
    }
});
</script>

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
        aspect-ratio: 2 / 3; /* Proporción 2:3 */
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

    .franja {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(40, 40, 40, 0.5); /* Gris semi-transparente */
        color: white; /* Color del texto */
        text-align: center;
        padding: 10px 0; /* Espaciado dentro de la franja */
        height: 38px;
        z-index: 3;
    }

    .franja p {
        margin: 0;
        line-height: 19px;
        font-size: 20px; /* Tamaño del texto */
        color: white; /* Color del texto */
    }


    @media screen and (max-width: 1280px) {
        .gallery-item {
            flex: 1 0 calc(33.333% - 1rem); /* Tres elementos por fila */
            aspect-ratio: 2 / 3;
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

        
        .franja p {
            line-height: 14px;
            font-size: 16px;
        }
    }




    /* Loader */
    .modern-loader {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: 0 auto;
        position: relative;
        border: 3px solid transparent;
        border-top-color: #F65807;
        animation: spin 1s linear infinite;
    }

    .modern-loader:before, .modern-loader:after {
        content: '';
        position: absolute;
        border-radius: 50%;
        border: 3px solid transparent;
    }

    .modern-loader:before {
        top: -12px;
        left: -12px;
        right: -12px;
        bottom: -12px;
        border-top-color: #F65807;
        animation: spin 2s linear infinite;
    }

    .modern-loader:after {
        top: 6px;
        left: 6px;
        right: 6px;
        bottom: 6px;
        border-top-color: #F65807;
        animation: spin 3s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Media Query */

    @media screen and (max-width: 40rem) {
        .profile {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
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