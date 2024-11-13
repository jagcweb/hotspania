@extends('layouts.app')

@section('title') Mi cuenta @endsection

@section('content')

<main role="main">

    <div class="container">

        <div class="profile">

            <div class="profile-image">

                @if(!is_null(\Auth::user()->profile_image))
                    <img class="img_profile" src="{{ route('home.imageget', ['filename' => \Auth::user()->profile_image]) }}" />
                @else
                    <img class="img_profile" src="{{ asset('images/user.jpg') }}"/>
                @endif

            </div>

            <div class="profile-user-settings">

                <h1 class="profile-user-name text-white">{{ \Auth::user()->nickname }}</h1>

                <button class="btn profile-edit-btn">Editar perfil</button>

                <button class="btn profile-settings-btn" aria-label="profile settings"><i class="fas fa-cog"
                        aria-hidden="true"></i></button>

            </div>

            <div class="profile-stats">

                <ul>
                    <li><span class="profile-stat-count">{{ count(\Auth::user()->images) }}</span> archivos</li>
                    <li><span class="profile-stat-count">43534</span> visitas</li>
                    <li><span class="profile-stat-count">3678</span> me gusta</li>
                </ul>

            </div>

            <div class="profile-bio mt-3">

                <p style="font-size:16px;">{{ \Auth::user()->working_zone ?? '' }} - Barcelona</p>
                <p class="mt-2"></p>
                <p style="font-size:16px; color:#fff;">{{ \Auth::user()->age }} Años</p>
                <p style="font-size:16px; color:#fff;">{{ \Auth::user()->weight }} KG</p>
                <p style="font-size:16px; color:#fff;">{{ \Auth::user()->height }} CM</p>
                <p style="font-size:16px; color:#fff;">{{ \Auth::user()->bust }} - {{ \Auth::user()->waist }} - {{ \Auth::user()->hip }}</p>
                <p style="font-size:16px; color:#fff;">Fuma: {{ \Auth::user()->is_smoker === 1 ? 'Si' : 'No' }}</p>
                <p style="font-size:16px; color:#fff;">{{ ucfirst(\Auth::user()->start_day) }} a {{ ucfirst(\Auth::user()->end_day) }}</p>
                <p style="font-size:16px; color:#fff;">
                    Horario: 
                    @if(\Auth::user()->start_time == 0 && \Auth::user()->end_time == 0)
                    Todo el día
                    @else
                        @if(\Auth::user()->start_time == 0)
                            00
                        @else
                            {{ \Auth::user()->start_time }}
                        @endif 
                        
                        a 
                        
                        @if(\Auth::user()->end_time == 0)
                            00
                        @else
                            {{ \Auth::user()->end_time }}
                        @endif 
                    @endif 
                </p>
            </div>

        </div>
        <!-- End of profile section -->

    </div>

    <div class="container mt-5 container_mobile">

        <div class="gallery">
            @foreach (\Auth::user()->images as $i=>$image)
                @php
                    $mimeType = \Storage::disk('images')->mimeType($image->route);
                    list($width, $height) = getimagesize(\Storage::disk('images')->path($image->route));
                @endphp
                @if ($mimeType && strpos($mimeType, 'image/') === 0)
                    <div class="gallery-item image-hover-zoom" tabindex="0" data="{{ asset('storage/images/'.$image->route) }}">

                        <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                            class="gallery-image" alt="">

                        @if(!is_null($image->frontimage))
                        <div class="gallery-item-type">

                            <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                        </div>
                        @endif

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
                @elseif ($mimeType && strpos($mimeType, 'video/') === 0)
                    <div class="gallery-item" tabindex="0" data="{{ asset('storage/images/'.$image->route) }}">

                        <video controls class="gallery-image">
                            <source src="{{ route('home.imageget', ['filename' => $image->route]) }}" type="{{ $mimeType }}" class="gallery-image">
                            Your browser does not support the video tag.
                        </video>

                        <div class="gallery-item-type">

                            <span class="visually-hidden">Video</span><i class="fas fa-video" aria-hidden="true"></i>

                        </div>

                        <div class="gallery-item-info">

                            <ul>
                                <li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i
                                        class="fas fa-heart" aria-hidden="true"></i> 30</li>
                                {{--
                                <li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i
                                        class="fas fa-comment" aria-hidden="true"></i> 2</li> --}}
                            </ul>
                        </div>
                    </div>
                @endif

            @endforeach

        </div>

    </div>
    <!-- End of container -->

</main>

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
    flex: 1 0 22rem; /* Ajusta el tamaño base de cada imagen */
    margin: 1rem;
    color: #fff;
    cursor: pointer;
    width: 100%; /* Asegura que la imagen ocupe todo el ancho disponible */
    padding-top: 100%; /* Esto hace que el contenedor sea cuadrado, con una altura igual al ancho */
    overflow: hidden; /* Oculta cualquier parte de la imagen que sobresalga */
    display: flex; /* Usamos flexbox para centrar la imagen */
    justify-content: center; /* Centrado horizontal */
    align-items: center; /* Centrado vertical */
    }

    .gallery-image {
        position: absolute; /* La imagen se posiciona dentro del contenedor */
        top: 0;
        left: 0;
        width: 100%; /* Hace que la imagen ocupe todo el espacio disponible */
        height: 100%; /* Asegura que la imagen cubra todo el área cuadrada */
        object-fit: cover; /* La imagen cubre el contenedor sin distorsionarse */
        object-position: center; /* Centra la imagen dentro del contenedor */
    }

    @media screen and (max-width: 1280px) {
        .gallery-item {
            flex: 1 0 22rem; /* Ajusta el tamaño base para las imágenes */
        }
        
        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Mantiene la imagen con la misma proporción */
            object-position: center; /* Centra la imagen */
        }

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
            grid-template-columns: repeat(auto-fit, minmax(22rem, 1fr));
            grid-gap: 0.2rem;
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

    /* New media query for max-width < 1280px */
    @media (max-width: 1280px) {
        .gallery {
            grid-template-columns: repeat(3, 1fr);  /* 3 items per row */
        }
    }
    }

</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos todos los videos de la galería
        const videos = document.querySelectorAll('.gallery-item video');
        videos.forEach((video) => {
            // Seleccionamos el contenedor de la portada de cada video
            const thumbnail = video.parentElement.querySelector('.video-thumbnail');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
    
            // Esperamos a que el video se haya cargado
            video.onloadeddata = function() {
                // Aseguramos que el video está listo para obtener un fotograma
                if (video.readyState >= 3) {
                    captureRandomFrame(video, thumbnail, canvas, ctx);
                }
            };
        });
    
        // Función para capturar un fotograma aleatorio
        function captureRandomFrame(video, thumbnail, canvas, ctx) {
            // Obtener un tiempo aleatorio dentro del video
            const randomTime = Math.random() * video.duration;
    
            // Movemos el video a ese tiempo aleatorio
            video.currentTime = randomTime;
    
            // Cuando el video llegue a la posición deseada, extraemos el fotograma
            video.onseeked = function() {
                // Establecemos el tamaño del canvas al tamaño del video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
    
                // Dibujamos el fotograma del video en el canvas
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
                // Convertimos el canvas a una imagen base64
                const imageUrl = canvas.toDataURL('image/png');
    
                // Asignamos la imagen base64 como fuente de la imagen de la portada
                thumbnail.src = imageUrl;
                thumbnail.style.display = 'block'; // Hacemos visible la portada
            };
        }
    });
</script>

<script>
    document.querySelectorAll('.gallery-item').forEach(function(item) {
      item.addEventListener('click', function() {
        var url = item.getAttribute('data'); // Get the URL from the data attribute
        window.open(url, '_blank'); // Open the URL in a new tab
      });
    });
</script>
@endsection