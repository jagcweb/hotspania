@extends('layouts.app')

@section('title') Mi cuenta @endsection

@section('content')

<main role="main">

    
    <div class="container">

        <div class="profile">

            <div class="profile-image">

                @if(is_object($frontimage))
                    @if(!is_null($frontimage->route_gif))
                        <img class="img_profile" src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" />
                    @else
                        <img class="img_profile" src="{{ route('home.imageget', ['filename' => $frontimage->route]) }}" />
                    @endif
                @else
                    <img class="img_profile" src="{{ asset('images/user.jpg') }}"/>
                @endif

            </div>

            <div class="profile-user-settings">

                <h1 class="profile-user-name text-white">{{ \Auth::user()->nickname }}</h1>

                <button class="btn profile-settings-btn" aria-label="profile settings"><i class="fas fa-cog"
                        aria-hidden="true"></i></button>

            </div>

            <div class="profile-stats">

                <ul>
                    <li><span class="profile-stat-count">{{ count($images) }}</span> archivos</li>
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
                <p style="font-size:16px; color:#fff;">
                    @if(\Auth::user()->start_day == "fulltime" && \Auth::user()->end_day == "fulltime")
                    Todos los días
                    @else
                    {{ ucfirst(\Auth::user()->start_day) }} a {{ ucfirst(\Auth::user()->end_day) }}
                    @endif
                </p>
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

                <p class="mt-4 w-100 text-center">
                    <i class="fa-solid fa-link mr-1" style="font-size:18px;"></i><a href="{{ \Auth::user()->link }}" target="_blank" style="color:#f65807; font-size:16px; text-decoration:underline!important;">{{ \Auth::user()->link }}</a>
                </p>
            </div>

        </div>
        <!-- End of profile section -->

    </div>

    <div class="container mt-5 container_mobile">
        <h2 class="w-100 text-center text-white" style="font-size: 20px;">Aquí solo aparecerán las imágenes aprobadas.</h2>
        <div class="gallery">
            @foreach ($images as $i=>$image)
                @php
                    $mimeType = \Storage::disk('images')->mimeType($image->route);
                @endphp
                @if ($mimeType && strpos($mimeType, 'image/') === 0)
                    @php list($width, $height) = getimagesize(\Storage::disk('images')->path($image->route)); @endphp
                    <div class="gallery-item-container">
                        <div class="gallery-item image-hover-zoom" tabindex="0">

                            <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                                class="gallery-image" alt="">

                            @if(!is_null($image->frontimage))
                            <div class="gallery-item-type">

                                <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                            </div>
                            @endif

                            <div class="gallery-item-info">

                                <ul>
                                    <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                                            class="fas fa-eye" aria-hidden="true"></i> {{56 * ($i+2)}}</li>
                                </ul>

                            </div>


                        </div>
                        <div class="gallery-item-buttons">
                            @if(is_null($image->visible))
                                <a title="Hacer imagen visible" href="{{ route('account.images.visible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye"></i></a>
                            @else
                                <a title="Hacer imagen invisible" href="{{ route('account.images.invisible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye-slash"></i></a>
                            @endif
                            @if($image->frontimage === 1)
                                <a title="Imagen portada" href="javascript:void(0)" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-image"></i></a>
                            @endif

                            @if(is_null($image->frontimage) && !is_null($height) && $height > $width)
                                <a title="Hacer imagen portada" href="{{ route('account.images.setfront', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-secondary"><i class="fa-regular fa-image"></i></a>
                            @endif
                        </div>
                    </div>
                @elseif ($mimeType && strpos($mimeType, 'video/') === 0)
                    @if(!is_null($image->route_gif))
                        @php list($width, $height) = getimagesize(\Storage::disk('videogif')->path($image->route_gif)); @endphp
                        <div class="gallery-item-container">
                            <div class="gallery-item image-hover-zoom" tabindex="0">

                                <img src="{{ route('home.gifget', ['filename' => $image->route_gif]) }}"
                                    class="gallery-image" alt="">

                                @if(!is_null($image->frontimage))
                                <div class="gallery-item-type">

                                    <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                                </div>
                                @endif

                                <div class="gallery-item-info">

                                    <ul>
                                        <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                                                class="fas fa-eye" aria-hidden="true"></i> {{56 * ($i+2)}}</li>
                                    </ul>

                                </div>

                            </div>
                            <div class="gallery-item-buttons">
                                @if(is_null($image->visible))
                                    <a title="Hacer imagen visible" href="{{ route('account.images.visible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye"></i></a>
                                @else
                                    <a title="Hacer imagen invisible" href="{{ route('account.images.invisible', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-eye-slash"></i></a>
                                @endif
                                @if($image->frontimage === 1)
                                    <a title="Imagen portada" href="javascript:void(0)" class="btn btn-primary" style="background:#f36e00!important;"><i class="fa-regular fa-image"></i></a>
                                @endif

                                @if(is_null($image->frontimage) && !is_null($height) && $height > $width)
                                    <a title="Hacer imagen portada" href="{{ route('account.images.setfront', ['image' => \Crypt::encryptString($image->id)]) }}" class="btn btn-secondary"><i class="fa-regular fa-image"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

        </div>

    </div>
    <!-- End of container -->

</main>

<!-- Modal Structure -->
<div id="contentModal" style="display: none;">
    <span id="closeBtn">&times;</span>
    
    <!-- Flechas de navegación -->
    <span id="prevBtn" class="nav-arrow">&#10094;</span> <!-- Flecha izquierda -->
    <span id="nextBtn" class="nav-arrow">&#10095;</span> <!-- Flecha derecha -->
    
    <!-- Contenido dinámico: imagen o video -->
    <img id="modalImage" src="" alt="Imagen ampliada" style="display:none;">
    <video autoplay crossorigin="anonymous" id="modalVideo" controls style="display:none;">
        <source src="" type="">
        Your browser does not support the video tag.
    </video>
</div>

<!-- Barra Sticky -->
<div id="stickyBar" class="sticky-bar">
    <div class="container">
        <div class="user-info">
            <span id="username">{{ \Auth::user()->nickname }}</span> <!-- Aquí puedes poner el nombre de usuario dinámicamente -->
        </div>
        <div class="contact-icons">
            <a href="https://api.whatsapp.com/send/?phone=+34{{ \Auth::user()->phone }}&text=¡Hola%20{{ \Auth::user()->nickname }}!%20Acabo%20de%20ver%20tu%20ficha%20en%20Hotspania.es%20¿Me%20comentas%20sobre%20tus%20servicios?" target="_blank" id="whatsappIcon" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i> <!-- Icono de WhatsApp -->
            </a>
            <a href="tel:+34{{ \Auth::user()->phone }}" id="phoneIcon" aria-label="Llamar">
                <i class="fas fa-phone"></i> <!-- Icono de Teléfono -->
            </a>
        </div>
    </div>
</div>

<style>
    /* Estilos generales para la barra sticky */
    .sticky-bar {
        position: fixed; /* Hacer la barra fija en la parte superior */
        top: -60px; /* Inicialmente fuera de la pantalla */
        left: 0;
        width: 100%;
        background-color: #111; /* Fondo oscuro para la barra */
        color: white;
        padding: 10px 0;
        z-index: 9999; /* Asegurarse de que esté por encima de otros elementos */
        transition: top 0.3s; /* Animación para que aparezca suavemente */
    }

    /* Contenedor de la barra */
    .sticky-bar .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    /* Información del usuario */
    .user-info {
        font-size: 16px;
    }

    /* Iconos de contacto */
    .contact-icons a {
        color: white;
        font-size: 20px;
        margin-left: 15px;
        transition: color 0.3s ease;
    }

    .contact-icons a:hover {
        color: #25d366; /* Cambio de color en hover para WhatsApp */
    }

    .contact-icons i {
        vertical-align: middle;
    }

    /* Mostrar la barra cuando está activa */
    .sticky-bar.show {
        top: 0; /* Cuando la barra está activa, la movemos hacia la parte superior */
    }

</style>

<style>
    /* Modal Styles */
    #contentModal {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        z-index: 1000;
    }

    /* Modal image and video styles */
    #modalImage, #modalVideo {
        max-width: 80%;  /* Maximum width */
        max-height: 80%; /* Maximum height */
        display: block;  /* Make sure they are displayed */
    }

    /* Close button */
    #closeBtn {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 30px;
        color: #fff;
        cursor: pointer;
    }

    /* Flechas de navegación */
    .nav-arrow {
        position: absolute;
        top: 50%;
        font-size: 40px;
        color: #fff;
        cursor: pointer;
        z-index: 1001;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 50%;
        transition: background-color 0.3s;
        z-index: 999;
    }

    #prevBtn {
        left: 10px;
        transform: translateY(-50%);
        z-index: 999;
    }

    #nextBtn {
        right: 10px;
        transform: translateY(-50%);
        z-index: 999;
    }

    /* Cambio de color al pasar el ratón por encima */
    .nav-arrow:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }


</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let currentIndex = 0; // Índice de la imagen/video actual
        let contentList = []; // Array de contenido (imagen/video)
        let isModalActive = false; // Flag para verificar si el modal está activo

        // Al cargar la galería, almacenamos todas las imágenes y videos
        $('.gallery-item').each(function() {
            let isImage = $(this).find('img').length > 0;
            let contentSrc = isImage ? $(this).find('img').attr('src') : $(this).find('video source').attr('src');
            contentList.push({
                type: isImage ? 'image' : 'video',
                src: contentSrc
            });
        });

        // Función para cargar el contenido en el modal (imagen o video)
        function loadContent(index) {
            let content = contentList[index];

            // Pausar el video si estamos cambiando de contenido y es un video
            $('#modalVideo')[0].pause(); 

            if (content.type === 'image') {
                $('#modalImage').attr('src', content.src).show(); // Mostrar imagen
                $('#modalVideo').hide(); // Ocultar video
            } else {
                $('#modalVideo').find('source').attr('src', content.src);
                $('#modalVideo')[0].load(); // Recargar el video
                $('#modalVideo').show(); // Mostrar video
                $('#modalImage').hide(); // Ocultar imagen
            }
        }

        // Cuando se hace clic en cualquier .gallery-item
        $('.gallery-item').on('click', function() {
            currentIndex = $(this).index(); // Obtener el índice de la imagen/video
            loadContent(currentIndex); // Cargar el contenido en el modal
            $('#contentModal').fadeIn(); // Mostrar el modal
            isModalActive = true; // Marcar que el modal está activo
        });

        // Función para navegar a la imagen/video anterior
        function prevContent() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : contentList.length - 1;
            loadContent(currentIndex);
        }

        // Función para navegar a la imagen/video siguiente
        function nextContent() {
            currentIndex = (currentIndex < contentList.length - 1) ? currentIndex + 1 : 0;
            loadContent(currentIndex);
        }

        // Navegar a la imagen/video anterior
        $('#prevBtn').on('click', function() {
            prevContent(); // Cargar contenido anterior
        });

        // Navegar a la imagen/video siguiente
        $('#nextBtn').on('click', function() {
            nextContent(); // Cargar contenido siguiente
        });

        // Cerrar el modal
        $('#closeBtn').on('click', function() {
            $('#contentModal').fadeOut();
            $('#modalVideo')[0].pause(); // Detener el video
            isModalActive = false; // Desactivar el modal
        });

        // Cerrar el modal si se hace clic fuera del contenido
        $('#contentModal').on('mousedown', function(e) {
            // Si el clic es fuera de la imagen/video y de los botones, cerramos el modal
            if (!$(e.target).closest('#modalImage, #modalVideo, #prevBtn, #nextBtn, #closeBtn').length && isModalActive) {
                $('#contentModal').fadeOut();
                $('#modalVideo')[0].pause(); // Detener el video si está reproduciéndose
                isModalActive = false; // Desactivar el modal
            }
        });

        // Detectar click en el video para pausar o reproducir
        $('#modalVideo').on('click', function() {
            const video = $('#modalVideo')[0];

            // Toggle play/pause based on current video state
            if (video.paused) {
                video.play();  // Play the video if it is paused
            } else {
                video.pause();  // Pause the video if it is playing
            }
        });

        // Detectar si el clic fue dentro del contenido del modal (imagen o video)
        $('#contentModal').on('mousedown', function(e) {
            if ($(e.target).is('#modalImage') || $(e.target).is('#modalVideo')) {
                // Mark that the click was inside the content
                isInsideContent = true;
            } else {
                isInsideContent = false; // Mark that the click was outside the content
            }
        });

        // Si se hace clic fuera del contenido, cerrar el modal
        $('#contentModal').on('mouseup', function(e) {
            if (!isInsideContent && !$(e.target).closest('#prevBtn, #nextBtn, #modalImage, #modalVideo').length) {
                $('#contentModal').fadeOut();
                $('#modalVideo')[0].pause(); // Detener el video
                isModalActive = false; // Desactivar el modal
            }
        });
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
        background: #F65807;
        border: 1px solid #F65807!important;
    }

    .fa-clone,
    .fa-comment {
        transform: rotateY(180deg);
    }

    /* Contenedor que agrupa imagen y botones */
    .gallery-item-container {
        display: flex;
        flex-direction: column; /* Asegura que la imagen y los botones se apilen verticalmente */
        align-items: center; /* Centra el contenido */
        margin: 1rem; /* Espacio alrededor de cada galería */
    }

    /* Estilo de la galería (imagen) */
    .gallery-item {
        position: relative;
        flex: 1 0 22rem; /* Ajusta el tamaño de la imagen */
        color: #fff;
        cursor: pointer;
        width: 100%;
        padding-top: 100%; /* Mantiene la relación cuadrada */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Estilo para la imagen */
    .gallery-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    /* Estilo de los botones debajo de cada imagen */
    .gallery-item-buttons {
        display: flex;
        justify-content: center; /* Centra los botones horizontalmente */
        margin-top: 1rem; /* Espacio entre la imagen y los botones */
        gap: 10px; /* Espacio entre los botones */
        width: 100%;
    }

    /* Estilo de los botones */
    .gallery-item-buttons a {
        text-decoration: none;
        padding: 8px 16px;
        font-size: 14px;
        color: #fff;
        background-color: #007bff; /* Botón azul */
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    /* Hover de los botones */
    .gallery-item-buttons a:hover {
        background-color: #0056b3; /* Color más oscuro al pasar el ratón */
    }

    /* Estilo de los botones secundarios */
    .gallery-item-buttons a.btn-secondary {
        background-color: #28a745; /* Botón verde */
    }

    .gallery-item-buttons a.btn-secondary:hover {
        background-color: #218838; /* Color más oscuro para el botón verde */
    }

    /* Media query para dispositivos móviles */
    @media screen and (max-width: 768px) {
        .gallery-item-buttons {
            flex-direction: column; /* Apila los botones verticalmente en dispositivos pequeños */
            align-items: stretch; /* Los botones se estiran para ocupar todo el ancho */
        }

        .gallery-item-buttons a {
            width: 100%; /* Hace que los botones ocupen el ancho completo */
            margin-bottom: 10px; /* Espacio entre los botones */
        }
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
    document.getElementById('profile-edit-btn').addEventListener('click', function() {
        window.location.href = '/account/edit';
    });
    
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos todos los videos de la galería
        const videos = document.querySelectorAll('.gallery-item video');
        videos.forEach((video) => {
            // Añadimos el atributo crossorigin para evitar problemas con CORS
            video.setAttribute('crossorigin', 'anonymous');

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
            video.currentTime = randomTime;
    
            // Usamos el evento timeupdate para asegurarnos de que el video ha alcanzado el fotograma adecuado
            video.ontimeupdate = function() {
                if (Math.abs(video.currentTime - randomTime) < 0.1) {
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
    
                    // Detenemos el evento once el fotograma se captura
                    video.ontimeupdate = null;
                }
            };
        }
    });
</script>

@endsection