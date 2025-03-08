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
                    <img class="img_profile" src="{{ route('home.imageget', ['filename' => $frontimage->route_frontimage]) }}" />
                @endif
                @else
                    <img class="img_profile" src="{{ asset('images/user.jpg') }}"/>
                @endif

            </div>

            <div class="profile-user-settings">

                <h1 class="profile-user-name text-white">{{ $user->nickname }}</h1>

            </div>

            <div class="profile-stats">

                <ul>
                    <li><span class="profile-stat-count">{{ count($images) }}</span> archivos</li>
                    <li><span class="profile-stat-count">43534</span> visitas</li>
                    <li><span class="profile-stat-count">3678</span> me gusta</li>
                </ul>

            </div>

            <div class="profile-bio mt-3">

                <p style="font-size:16px;">{{ $user->working_zone ?? '' }} - Barcelona</p>
                <p class="mt-2"></p>
                <p style="font-size:16px; color:#fff;">{{ $user->age }} Años</p>
                <p style="font-size:16px; color:#fff;">{{ $user->weight }} KG</p>
                <p style="font-size:16px; color:#fff;">{{ $user->height }} CM</p>
                <p style="font-size:16px; color:#fff;">{{ $user->bust }} - {{ $user->waist }} - {{ $user->hip }}</p>
                <p style="font-size:16px; color:#fff;">Fuma: {{ $user->is_smoker === 1 ? 'Si' : 'No' }}</p>
                <p style="font-size:16px; color:#fff;">
                    @if($user->start_day == "fulltime" && $user->end_day == "fulltime")
                    Todos los días
                    @else
                    {{ ucfirst($user->start_day) }} a {{ ucfirst($user->end_day) }}
                    @endif
                </p>
                <p style="font-size:16px; color:#fff;">
                    Horario: 
                    @if($user->start_time == 0 && $user->end_time == 0 || $user->start_time == "fulltime" && $user->end_time == "fulltime")
                    Todo el día
                    @else
                        @if($user->start_time == 0)
                            00
                        @else
                            {{ $user->start_time }}
                        @endif 
                        
                        a 
                        
                        @if($user->end_time == 0)
                            00
                        @else
                            {{ $user->end_time }}
                        @endif 
                    @endif 
                </p>
            </div>

        </div>
        <!-- End of profile section -->

    </div>

    
    <div class="container">
        @if(!is_null($user->link))
            <p class="mt-5 w-100 text-center">
                <i class="fa-solid fa-link mr-1" style="font-size:18px;"></i><a href="{{ $user->link }}" target="_blank" style="color:#f65807; font-size:16px; text-decoration:underline!important;">{{ $user->link }}</a>
            </p>
        @endif
        <div class="buttons">
            <button class="btn profile-edit-btn whatsapp_btn">WhatsApp</button>
            <button class="btn profile-edit-btn call_btn" style="margin-left:20px;">Llámame</button>
        </div>
    </div>

    <input type="text" class="user_number" value="{{ $user->phone }}" hidden/>
    <input type="text" class="user_nickname" value="{{ $user->nickname }}" hidden/>

    <div class="container mt-5 container_mobile">
        <div class="gallery" id="gallery">
            @foreach ($images->take(8) as $i=>$image)
                @php
                    $mimeType = \Storage::disk(\App\Helpers\StorageHelper::getDisk('images'))->mimeType($image->route);
                @endphp
                @if ($mimeType && strpos($mimeType, 'image/') === 0)
                    <div class="gallery-item image-hover-zoom" tabindex="0">
                        <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                            class="gallery-image" alt="" loading="lazy">
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
                    <div class="gallery-item" tabindex="0">

                        <video crossorigin="anonymous" class="gallery-image">
                            <source src="{{ route('home.imageget', ['filename' => $image->route]) }}" type="{{ $mimeType }}">
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
        <div id="loading" style="display: block; text-align: center; padding: 20px; margin: 20px 0;">
            <div class="modern-loader"></div>
        </div>
    </div>

</main>

<!-- Modal Structure -->
<div id="contentModal" style="display: none;">
    <span id="closeBtn">&times;</span>
    
    <!-- Flechas de navegación -->
    <span id="prevBtn" class="nav-arrow">&#10094;</span> <!-- Flecha izquierda -->
    <span id="nextBtn" class="nav-arrow">&#10095;</span> <!-- Flecha derecha -->
    
    <!-- Contenido dinámico: imagen o video -->
    <img id="modalImage" src="" alt="Imagen ampliada" style="display:none;">
    <video autoplay crossorigin="anonymous" id="modalVideo" style="display:none;">
        <source src="" type="">
        Your browser does not support the video tag.
    </video>
</div>

<!-- Barra Sticky -->
<div id="stickyBar" class="sticky-bar">
    <div class="container">
        <div class="user-info">
            <span id="username">{{ $user->nickname }}</span> <!-- Aquí puedes poner el nombre de usuario dinámicamente -->
        </div>
        <div class="contact-icons">
            <a href="https://api.whatsapp.com/send/?phone=+34{{ $user->phone }}&text=¡Hola%20{{ $user->nickname }}!%20Acabo%20de%20ver%20tu%20ficha%20en%20Hotspania.es%20¿Me%20comentas%20sobre%20tus%20servicios?" target="_blank" id="whatsappIcon" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i> <!-- Icono de WhatsApp -->
            </a>
            <a href="tel:+34{{ $user->phone }}" id="phoneIcon" aria-label="Llamar">
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
        let currentIndex = 0; 
        window.contentList = []; // Hacemos contentList global
        let isModalActive = false;
        
        // Función para inicializar/actualizar contentList
        function updateContentList() {
            window.contentList = []; // Limpiamos el array
            $('.gallery-item').each(function() {
                let isImage = $(this).find('img').length > 0;
                let contentSrc = isImage ? $(this).find('img').attr('src') : $(this).find('video source').attr('src');
                window.contentList.push({
                    type: isImage ? 'image' : 'video',
                    src: contentSrc,
                    element: this
                });
            });
        }

        // Inicializar contentList con las imágenes existentes
        updateContentList();

        function loadContent(index) {
            let content = window.contentList[index];
            $('#modalVideo')[0].pause();

            if (content.type === 'image') {
                $('#modalImage').attr('src', content.src).show();
                $('#modalVideo').hide();
            } else {
                $('#modalVideo').find('source').attr('src', content.src);
                $('#modalVideo')[0].load();
                $('#modalVideo').show();
                $('#modalImage').hide();
            }
        }

        function findContentIndex(element) {
            return window.contentList.findIndex(item => item.element === element);
        }

        $(document).on('click', '.gallery-item', function() {
            updateContentList(); // Actualizar lista antes de abrir modal
            currentIndex = findContentIndex(this);
            loadContent(currentIndex);
            $('#contentModal').fadeIn();
            isModalActive = true;
        });

        function prevContent() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : window.contentList.length - 1;
            loadContent(currentIndex);
        }

        function nextContent() {
            currentIndex = (currentIndex < window.contentList.length - 1) ? currentIndex + 1 : 0;
            loadContent(currentIndex);
        }

        $('#prevBtn').on('click', function() {
            prevContent();
        });

        $('#nextBtn').on('click', function() {
            nextContent();
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

        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        const loadMoreImages = () => {
            if (isLoading || !hasMore) return;
            
            isLoading = true;
            loading.style.display = 'block';
            console.log('Cargando más imágenes...');

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: `/account/load-more/${page + 1}/{{ $user->id }}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Respuesta:', response);
                    
                    if (response.html && response.html.trim()) {
                        gallery.insertAdjacentHTML('beforeend', response.html);
                        page++;
                        hasMore = response.hasMore;
                        initializeNewImages();
                        if (!hasMore) {
                            loading.style.display = 'none';
                        }
                    } else {
                        hasMore = false;
                        loading.style.display = 'none';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    hasMore = false;
                    loading.style.display = 'none';
                },
                complete: function() {
                    isLoading = false;
                }
            });
        };

        // Usar scroll en lugar de Intersection Observer
        $(window).scroll(function() {
            if (isElementInViewport(loading) && !isLoading && hasMore) {
                loadMoreImages();
            }
        });

        function initializeNewImages() {
            const newItems = gallery.querySelectorAll('.gallery-item:not([data-initialized])');
            newItems.forEach(item => {
                item.setAttribute('data-initialized', 'true');
                // No necesitamos añadir el evento click aquí ya que está manejado por la delegación de eventos arriba
            });
            // Actualizar contentList después de añadir nuevas imágenes
            if (typeof window.contentList !== 'undefined') {
                updateContentList();
            }
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
        background: #F65807;
        border: 1px solid #F65807!important;
    }

    .fa-clone,
    .fa-comment {
        transform: rotateY(180deg);
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 2 / 3; /* Mantiene proporción 3:2 */
        overflow: hidden; /* Oculta contenido excedente */
        display: flex;
        justify-content: center; /* Centra el contenido horizontalmente */
        align-items: center; /* Centra el contenido verticalmente */
        background-color: #000; /* Fondo visible mientras carga la imagen */
        cursor: pointer;
    }

    .gallery-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover; /* Escala la imagen para cubrir completamente */
        object-position: center; /* Centra la imagen en su contenedor */
    }


    @media screen and (max-width: 1280px) {
        .gallery-item {
            flex: 1 0 calc(33.333% - 1rem); /* Tres elementos por fila */
            
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

    /* Nuevo loader más moderno */
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

        /*.profile-stats {
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
        }*/

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
            height: auto;
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

    @media screen and (max-width: 640px) {
        /*.profile {
            display: flex;                     
            justify-content: flex-start;      
            align-items: center;               
            flex-wrap: wrap;                   
        }*/

        .profile-image {
            width: 100%; /* Ajusta el ancho según el contenedor */
            aspect-ratio: 2 / 3; /* Proporción 2 de ancho por 3 de alto */
            margin-left: auto; /* Centra el elemento horizontalmente si es necesario */
            margin-right: auto;
            margin-top: 0; /* Ajusta según tu diseño */
            height: 100%;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Mantiene el contenido visible sin distorsión */
            border-radius: 0; /* Quita el redondeo */
        }

        .profile-stats li {
            display: inline-block;
            font-size: 1.4rem;
            line-height: 1.5;
            margin-right: 2rem;
        }

        .profile-user-name {
            font-size: 1.4rem;
        }

        .profile-bio p {
            font-size: 1.4rem!important;
        }

        .profile-user-settings,
        .profile-bio {
            margin-left: 1rem;                 /* Espaciado a la izquierda de los otros divs */
            flex-grow: 1;                      /* Los otros divs ocupan el espacio restante */
        }

        .profile-stats {
            margin-left: 1rem;                 /* Espaciado a la izquierda de los otros divs */
            flex-grow: 1;                      /* Los otros divs ocupan el espacio restante */
        }

        .profile-user-settings {
            display: flex;
            flex-wrap: wrap;                   /* Permitimos que los elementos dentro de profile-user-settings se ajusten */
            margin-top: 0;                     /* Eliminamos el margen superior innecesario */
        }

        .profile-user-name {
            font-size: 2.2rem;
        }

        .profile-edit-btn {
            order: 1;                          /* Los botones se ponen debajo del nombre */
            margin-top: 1rem;
            text-align: center;
        }

        .profile-bio {
            margin-top: 1rem;                  /* Añadimos un margen superior para la bio */
        }
    }

    @media screen and (max-width: 540px) {
        /*.profile {
            display: flex;                     
            justify-content: flex-start;      
            align-items: center;               
            flex-wrap: wrap;                   
        }*/

        .profile {
            grid-column-gap: 0rem;
        }
        .profile-user-settings,
        .profile-bio {
            margin-left: 1rem;                 /* Espaciado a la izquierda de los otros divs */
            flex-grow: 1;                      /* Los otros divs ocupan el espacio restante */
        }

        .profile-stats {
            margin-left: 1rem;                 /* Espaciado a la izquierda de los otros divs */
            flex-grow: 1;                      /* Los otros divs ocupan el espacio restante */
        }

        .profile-user-settings {
            display: flex;
            flex-wrap: wrap;                   /* Permitimos que los elementos dentro de profile-user-settings se ajusten */
            margin-top: 0;                     /* Eliminamos el margen superior innecesario */
        }

        .profile-user-name {
            font-size: 2.2rem;
        }

        .profile-edit-btn {
            order: 1;                          /* Los botones se ponen debajo del nombre */
            margin-top: 1rem;
            text-align: center;
        }

        .profile-bio {
            margin-top: 1rem;                  /* Añadimos un margen superior para la bio */
        }
    }

    @media screen and (max-width: 420px) {
        .profile-stats li {
            display: inline-block;
            font-size: 1.2rem;
            line-height: 1.5;
            margin-right: 1rem;
        }

        .profile-user-name {
            font-size: 1.2rem;
        }

        .profile-bio p {
            font-size: 1.2rem!important;
        }
    }

    @media screen and (max-width: 350px) {
        .profile-stats li {
            display: inline-block;
            font-size: 1.1rem;
            line-height: 1.5;
            margin-right: 0.7rem;
        }

        .profile-user-name {
            font-size: 1.1rem;
        }

        .profile-bio p {
            font-size: 1.1rem!important;
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
    const numberInput = document.querySelector('.user_number');
    const number = numberInput.value;
    const nicknameInput = document.querySelector('.user_nickname');
    const nickname = nicknameInput.value;
    document.querySelector('.whatsapp_btn').addEventListener('click', function() {
        // Replace the URL with your desired WhatsApp link



        let inputStr = `¡Hola ${nickname}! Acabo de ver tu ficha en Hotspania.es ¿Me comentas sobre tus servicios?`;
        let outputStr = '';
        for (let i = 0; i < inputStr.length; i++) {
            if (inputStr[i] === ' ') {
                outputStr += '%20';
            } else {
                outputStr += inputStr[i];
            }
        }

        const whatsappLink = `https://api.whatsapp.com/send/?phone=34${number}&text=${outputStr}`;
        window.open(whatsappLink, '_blank');
    });

    document.querySelector('.call_btn').addEventListener('click', function() {
        const callLink = `tel:34${number}`;
        window.location.href = callLink; // This will initiate the call
    });
</script>

<script>
    /*document.querySelectorAll('.gallery-item').forEach(function(item) {
      item.addEventListener('click', function() {
        var url = item.getAttribute('data'); // Get the URL from the data attribute
        window.open(url, '_blank'); // Open the URL in a new tab
      });
    });*/
</script>
  
@endsection