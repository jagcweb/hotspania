@extends('layouts.app')

@section('title') Mi cuenta @endsection

@section('content')

<main role="main">
    <div id="miDiv" class="container">
    <script>
        function actualizarClaseSegunAnchoPantalla() {
            const div = document.getElementById('miDiv');
            if (window.innerWidth > 768) {
                div.style.marginLeft = '';
                div.style.marginRight = ''; 
                div.classList.add('container');
            } else {
                div.classList.remove('container');
                div.style.marginLeft = '10px';
                div.style.marginRight = '10px';
            }
        }
        
        // Ejecutar al cargar la página
        actualizarClaseSegunAnchoPantalla();
        
        // Ejecutar al redimensionar la ventana
        window.addEventListener('resize', actualizarClaseSegunAnchoPantalla);
    </script>

        <div class="profile">

            <div class="profile-image">
                <div class="image-container">
                    @php
                        $isAvailable = false;
                        if ($user->available_until !== null) {
                            $now = \Carbon\Carbon::now('Europe/Madrid');
                            $endTime = \Carbon\Carbon::parse($user->available_until)->setTimezone('Europe/Madrid');
                            $isAvailable = $now->lt($endTime);
                        }

                        $colorClass = 'flame-color-' . rand(1, 15);
                    @endphp

                    @if($isAvailable)
                        <img src="{{ asset('images/llamas.gif') }}" class="flame-border-profile {{ $colorClass }}" alt="Online">
                    @endif

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
            </div>

            <div style="width: 100%; display: flex; justify-content: flex-start; align-items: center; flex-direction: column;">

                <div class="profile-user-settings">

                    <h1 class="profile-user-name text-white">{{ $user->nickname }}</h1>
                    @if($isAvailable)
                        <small class="availability-text" style="margin-top: -10px; margin-left:2px;">Disponible ahora</small>
                    @endif

                </div>

                <p style="font-size:16px;" class="text-justify text-city">{{ $user->working_zone ?? '' }} - <span id="selectedCity"></span></p>

                <script>
                    // Get cookie value from JavaScript
                    function getCookie(name) {
                        let value = `; ${document.cookie}`;
                        let parts = value.split(`; ${name}=`);
                        if (parts.length === 2) {
                            return decodeURIComponent(parts.pop().split(';').shift());
                        }
                        return null;
                    }
                    
                    // Update the city name from cookie
                    document.addEventListener('DOMContentLoaded', function() {
                        const selectedCity = getCookie('selected_city') || 'Barcelona';
                        const cityElement = document.getElementById('selectedCity');
                        if (cityElement) {
                            cityElement.textContent = selectedCity.charAt(0).toUpperCase() + selectedCity.slice(1);
                        }
                    });
                </script>

                <div class="profile-stats">

                    @php
                        $totalVisits = $images->sum('visits') ?? 0;
                        $totalLikes = \App\Models\ImageLike::whereIn('image_id', $images->pluck('id'))->count() ?? 0;
                        $totalPoints = floor($totalVisits * 0.2 + $totalLikes * 0.5);
                    @endphp
                    <ul>
                        <li><span class="profile-stat-count">{{ count($images) }}</span> archivos</li>
                        <li><span class="profile-stat-count">{{ $totalVisits }}</span> visitas</li>
                        <li><span class="profile-stat-count">{{ $totalLikes }}</span> me gusta</li>
                    </ul>

                </div>

                <div class="profile-bio mt-3">
                    <p class="mt-2"></p>
                    <div class="properties">
                        <p style="font-size:16px; color:#fff;" class="text-justify">{{ $user->age }} Años</p>
                        <p style="font-size:16px; color:#fff;" class="text-justify">{{ $user->weight }} KG</p>
                    </div>
                    <div class="properties">
                        <p style="font-size:16px; color:#fff;" class="text-justify">{{ $user->height }} CM</p>
                        <p style="font-size:16px; color:#fff;" class="text-justify">Fuma: {{ $user->is_smoker === 1 ? 'Si' : 'No' }}</p>
                    </div>
                    <p style="font-size:16px; color:#fff;" class="text-justify">{{ $user->bust }} - {{ $user->waist }} - {{ $user->hip }}</p>
                    <p style="font-size:16px; color:#fff;" class="text-justify">
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
                    @if(!is_null($user->link))
                        <p class="link" style="font-size:16px; color:#fff;">
                            <a href="{{ $user->link }}" target="_blank" style="color:#fff; font-size:16px; text-decoration:underline!important;">{{ str_replace(['http://', 'https://'], '', $user->link) }}</a>
                        </p>
                    @endif
                </div>
            </div>

        </div>
        <!-- End of profile section -->

    </div>

    
    <div class="container container_mobile">
        <div class="buttons mt-5 container_mobile">
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
                    $hasLike = in_array($image->id, $likedImages ?? []);
                @endphp
                @if ($mimeType && strpos($mimeType, 'image/') === 0)
                    <div class="gallery-item image-hover-zoom {{ $hasLike ? 'has-like' : '' }}" tabindex="0" data-id="{{ $image->id }}">
                        <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                            class="gallery-image" alt="" loading="lazy">
                        @if(!is_null($image->frontimage))
                        <div class="gallery-item-type">

                            <span class="visually-hidden">Portada</span><i class="fa-solid fa-star" aria-hidden="true"></i>

                        </div>
                        @endif

                        <div class="gallery-item-info">

                            <ul>
                                <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                                        class="fas fa-eye" aria-hidden="true"></i> {{ $image->visits ?? 0 }}</li>
                                <li class="gallery-item-comments"><span class="visually-hidden">Likes:</span><i
                                    class="fas fa-heart" aria-hidden="true"></i> {{ \App\Models\ImageLike::where('image_id', $image->id)->count() }}</li>
                                <li class="gallery-item-points">
                                    <span class="visually-hidden">Points:</span>
                                    <i class="fas fa-bullseye" aria-hidden="true"></i> {{$totalPoints}}
                                </li>
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
    <span id="prevBtn" class="nav-arrow">&#10094;</span>
    <span id="nextBtn" class="nav-arrow">&#10095;</span>
    
    <!-- Añadir un contenedor para la imagen/video -->
    <div class="modal-content-wrapper">
        <img id="modalImage" src="" alt="Imagen ampliada" style="display:none;">
        <video autoplay crossorigin="anonymous" id="modalVideo" style="display:none;">
            <source src="" type="">
            Your browser does not support the video tag.
        </video>
        <!-- Modificar el div del corazón permanente para que sea clickeable -->
        <div id="permanentHeart" class="permanent-heart" style="cursor: pointer;">❤️</div>
    </div>
    
    <div id="floatingHeart" class="floating-heart">🤍</div>
</div>

<!-- Barra Sticky -->
<div id="stickyBar" class="sticky-bar">
    <div class="container">
        <div class="user-info">
            <span id="username">{{ $user->nickname }}</span> <!-- Aquí puedes poner el nombre de usuario dinámicamente -->
        </div>
        <div class="contact-icons">
            <a href="https://api.whatsapp.com/send/?phone=+34{{ $user->phone }}&text=Hola%20{{ $user->nickname }}%20Acabo%20de%20ver%20tu%20ficha%20en%20Hotspania.es%20y%20quisiera%20información,%20que%20me%20cuentes%20un%20poco" target="_blank" id="whatsappIcon" aria-label="WhatsApp">
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

    .properties {
        display: flex;
        flex-direction: column;
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

    /* Estilo para el corazón flotante */
    .floating-heart {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 150px;
        opacity: 0;
        z-index: 1002;
        pointer-events: none;
        transition: all 0.5s ease;
    }

    .floating-heart.show {
        animation: heartBeat 1s ease-in-out;
    }

    @keyframes heartBeat {
        0% { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }
        50% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
        100% { opacity: 0; transform: translate(-50%, -50%) scale(1); }
    }

    /* Actualizar el estilo del corazón permanente */
    .permanent-heart {
        position: absolute;
        left: 20px;
        bottom: 20px;
        font-size: 30px;
        transition: opacity 0.3s ease;
        z-index: 1002;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        pointer-events: none;
    }

    .permanent-heart.active {
        opacity: 1;
        color: #ff4444;
    }

    .permanent-heart.inactive {
        opacity: 1;
        color: #fff;
    }

    /* Añadir estilos para el wrapper */
    .modal-content-wrapper {
        position: relative;
        display: inline-block;
        max-width: 80%;
        max-height: 80vh;
    }

    /* Actualizar estilos del modal y sus elementos */
    #modalImage, #modalVideo {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
    }

    .permanent-heart {
        /* ...existing styles... */
        pointer-events: auto; /* Permitir interacción con el elemento */
    }

</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
<script>
    $(document).ready(function() {
        // Check if profile visit should be counted
        let profileVisitKey = 'profile_visits_{{ $user->id }}';
        let lastProfileVisit = localStorage.getItem(profileVisitKey);
        let shouldCountProfileVisit = true;

        if (lastProfileVisit) {
            let lastVisitDate = new Date(lastProfileVisit);
            let now = new Date();
            let hoursDiff = (now - lastVisitDate) / (1000 * 60 * 60);
            
            if (hoursDiff < 24) {
            shouldCountProfileVisit = false;
            }
        }

        if (shouldCountProfileVisit) {
            $.ajax({
            url: `/account/load/addVisit/profile/{{ \Crypt::encryptString($user->id) }}`,
            method: 'GET',
            }).done(function (response){
            if(response.success) {
                const timestamp = new Date().toISOString();
                localStorage.setItem(profileVisitKey, timestamp);
            }
            });
        }

        let currentIndex = 0; 
        window.contentList = []; // Hacemos contentList global
        let isModalActive = false;
        
        // Add this at the top of your script
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
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

        // Añadir esta función al inicio
        function checkInitialLikes() {
            $('.gallery-item').each(function() {
                let imageId = $(this).data('id');
                if (localStorage.getItem('image_like_' + imageId)) {
                    $(this).addClass('has-like');
                }
            });
        }
        
        function loadContent(index) {
            const heart = $('#floatingHeart');
            $('#permanentHeart').html('🤍').hide();
            let content = window.contentList[index];
            $('#modalVideo')[0].pause();

            let imageId = $(content.element).data('id');
            let thisItem = $(content.element);
            
            // LIMPIAR EVENTOS ANTERIORES - ESTO ES CLAVE
            $('#permanentHeart').off('click');
            $('#modalImage, #modalVideo').off('dblclick');
            
            // Funciones auxiliares para evitar redundancia
            function updateLikesDisplay(likes) {
                let likesElement = $(window.contentList[currentIndex].element).find('.gallery-item-comments');
                if(likesElement.length) {
                    likesElement.html(`<span class="visually-hidden">Me gusta:</span><i class="fas fa-heart" aria-hidden="true"></i> ${likes}`);
                }
            }
            
            function showFloatingHeart() {
                $('#floatingHeart').html('❤️').addClass('show');
                setTimeout(() => $('#floatingHeart').removeClass('show'), 1000);
            }
            
            function addLike() {
                showFloatingHeart();
                $.ajax({
                    url: `/account/load/like/${imageId}`,
                    method: 'GET',
                    success: function(response) {
                        if(response.success) {
                            updateLikesDisplay(response.likes);
                            $('#permanentHeart').addClass('active').html('❤️');
                            $(window.contentList[currentIndex].element).addClass('has-like');
                            
                            @if(!Auth::check())
                                localStorage.setItem('image_like_' + imageId, 'true');
                            @endif
                        }
                    }
                });
            }
            
            function removeLike() {
                $.ajax({
                    url: `/account/remove-like/${imageId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            updateLikesDisplay(response.likes);
                            $('#permanentHeart').removeClass('active').html('🤍');
                            $(window.contentList[currentIndex].element).removeClass('has-like');
                            
                            @if(!Auth::check())
                                localStorage.removeItem('image_like_' + imageId);
                            @endif
                        }
                    }
                });
            }
            
            function setupHeartClick(hasLike) {
                $('#permanentHeart').on('click', function(e) {
                    e.stopPropagation();
                    console.log(`Heart clicked to ${hasLike ? 'remove' : 'add'} like for image ID:`, imageId);
                    
                    if (hasLike) {
                        removeLike();
                    } else {
                        addLike();
                    }
                });
            }
            
            function setupDoubleClick() {
                $('#modalImage, #modalVideo').on('dblclick', function() {
                    addLike();
                });
            }
            
            // Verificar el estado del like
            @if(Auth::check())
                $.get(`/account/check-like/${imageId}`, function(response) {
                    $('#permanentHeart').addClass('active').html(response.hasLiked ? '❤️' : '🤍').show();
                    setupHeartClick(response.hasLiked);
                    
                    if (!response.hasLiked) {
                        setupDoubleClick();
                    }
                });
            @else
                let hasLike = localStorage.getItem('image_like_' + imageId);
                $('#permanentHeart').addClass('active').html(hasLike ? '❤️' : '🤍').show();
                setupHeartClick(!!hasLike);
                
                if (!hasLike) {
                    setupDoubleClick();
                }
            @endif

            // Lógica de visitas
            let lastVisit = localStorage.getItem('image_visits_' + imageId);
            let shouldCount = true;

            if (lastVisit) {
                console.log('Last visit:', lastVisit);
                let lastVisitDate = new Date(lastVisit);
                let now = new Date();
                let hoursDiff = (now - lastVisitDate) / (1000 * 60 * 60);
                
                if (hoursDiff < 24) {
                    shouldCount = false;
                }

                console.log('shouldCount:', shouldCount);
            }

            if (shouldCount) {
                console.log('Incrementing visits for image ID:', imageId);
                $.ajax({
                    url: `/account/load/show/${imageId}`,
                    method: 'GET',
                }).done(function (response){
                    if(response.success) {
                        let visitsElement = thisItem.find('.gallery-item-likes');
                        if(visitsElement.length) {
                            visitsElement.html(`<span class="visually-hidden">Visitas:</span><i class="fas fa-eye" aria-hidden="true"></i> ${response.visits || 0}`);
                        }
                        localStorage.removeItem('image_visits_' + imageId);
                        const timestamp = new Date().toISOString();
                        localStorage.setItem('image_visits_' + imageId, timestamp);
                    }
                });
            }

            // Mostrar contenido
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
            let imageId = $(this).data('id');
            let thisItem = $(this);
            
            updateContentList();
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
            if (!$(e.target).closest('#modalImage, #modalVideo, #prevBtn, #nextBtn, #closeBtn, #permanentHeart').length && isModalActive) {
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
            if ($(e.target).is('#modalImage') || $(e.target).is('#modalVideo') || $(e.target).is('#permanentHeart')) {
                // Mark that the click was inside the content
                isInsideContent = true;
            } else {
                isInsideContent = false; // Mark that the click was outside the content
            }
        });

        // Si se hace clic fuera del contenido, cerrar el modal
        $('#contentModal').on('mouseup', function(e) {
            if (!isInsideContent && !$(e.target).closest('#prevBtn, #nextBtn, #modalImage, #modalVideo, #permanentHeart').length) {
                $('#contentModal').fadeOut();
                $('#modalVideo')[0].pause(); // Detener el video
                isModalActive = false; // Desactivar el modal
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

        $(window).scroll(function() {
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();

            const scrollBottom = scrollTop + windowHeight;
            const threshold = 100;

            if (
                scrollBottom + threshold >= documentHeight &&
                !isLoading &&
                hasMore
            ) {
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

        // Llamar a la función cuando se carga la página
        checkInitialLikes();

        // Agregar el evento click para el corazón permanente
        /*$('#permanentHeart').on('click', function(e) {
            e.stopPropagation();
            let currentItem = window.contentList[currentIndex];
            let imageId = $(currentItem.element).data('id');

            // Verifica si ya tiene like
            let hasLike = $(this).hasClass('active');

            if (hasLike) {
                // Quitar like
                $.ajax({
                    url: `/account/remove-like/${imageId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let likesElement = $(currentItem.element).find('.gallery-item-comments');
                            if (likesElement.length) {
                                likesElement.html(`<span class="visually-hidden">Me gusta:</span><i class="fas fa-heart" aria-hidden="true"></i> ${response.likes}`);
                            }

                            $('#permanentHeart').removeClass('active').html('🤍');
                            $('#floatingHeart').html('❤️');
                            $(currentItem.element).removeClass('has-like');

                            if (!response.isAuthenticated) {
                                localStorage.removeItem('image_like_' + imageId);
                            }
                        }
                    }
                });
            } else {
                // Agregar like
                $.ajax({
                    url: `/account/load/like/${imageId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let likesElement = $(currentItem.element).find('.gallery-item-comments');
                            if (likesElement.length) {
                                likesElement.html(`<span class="visually-hidden">Me gusta:</span><i class="fas fa-heart" aria-hidden="true"></i> ${response.likes}`);
                            }

                            $('#permanentHeart').addClass('active').html('❤️');
                            $('#floatingHeart').html('❤️');
                            $(currentItem.element).addClass('has-like');

                            if (!response.isAuthenticated) {
                                localStorage.setItem('image_like_' + imageId, 'true');
                            }
                        }
                    }
                });
            }
        });*/




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
        width: 280px;
        aspect-ratio: 2/3;
        object-fit: cover;
        object-position: center;
    }

    @media screen and (max-width: 640px) {
        .img_profile {
            width: 100%;
            aspect-ratio: 2/3;
            object-fit: cover;
            object-position: center;
            margin: 0 auto;
        }

        .container {
            width: 100%!importasnt;
            margin: 0;
            padding: 0;
        }
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
        width: calc(50% - 1rem); /* Changed from 33.333% to 50% */
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 3rem;
    }

    .profile-image .image-container {
        position: relative;
        width: 280px;
        height: 420px; /* Ajusta esto según el aspect ratio 2/3 que necesitas */
    }

    .profile-user-settings,
    .profile-stats,
    .profile-bio {
        float: left;
        width: calc(50% - 2rem); /* Changed from 66.666% to 50% */
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

    .gallery-item-info ul {
        white-space: nowrap;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .gallery-item-info li {
        display: inline-block;
        font-size: 1.7rem;
        font-weight: 600;
        margin-right: 0.8rem;
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
        display: flex; /* Cambiado a flex */
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
            grid-template-columns: minmax(280px, 0.4fr) 0.6fr; /* Changed ratio to 40/60 */
            grid-template-rows: min-content auto auto; /* Changed to explicit content sizing */
            grid-column-gap: 3rem;
            align-items: start;
            padding: 1rem;
        }

        .profile-image {
            grid-row: 1 / -1;
            grid-column: 1;
            width: 100%;
            max-width: 380px;
            align-self: start;
            margin: 0;
            display: flex;          /* Add flex display */
            justify-content: center; /* Center horizontally */
            align-items: center;    /* Center vertically */
        }

        .profile-user-settings,
        .profile-stats,
        .profile-bio {
            grid-column: 2;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .profile-user-settings {
            grid-row: 1;
        }

        .profile-stats {
            grid-row: 2;
        }

        .profile-bio {
            grid-row: 3;
            margin-top: 1rem;
        }

        /* Link styles with important flags */
        .link-text {
            color: #f65807 !important;
            font-size: 16px !important;
            text-decoration: underline !important;
            display: inline-block !important;
            word-break: break-all !important;
        }

        .link-icon {
            color: #f65807 !important;
            vertical-align: middle !important;
        }

        @media screen and (max-width: 640px) {
            .profile {
                display: grid;
                grid-template-columns: minmax(120px, 0.4fr) 0.6fr; /* Changed to 40/60 ratio */
                grid-gap: 0.5rem;
                padding: 0.5rem;
            }

            .profile-image {
                width: 100%;
                aspect-ratio: 2/3!important;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
                height: 100%;
            }

            .profile-image img {
                width: 100%;
                aspect-ratio: 2/3 !important;
                object-fit: cover;
                
            }

            .profile-user-settings,
            .profile-stats,
            .profile-bio {
                grid-column: 2;
                margin: 0;
                padding: 0 0 0 0.5rem;
                width: 100%;
            }
        }

        @media screen and (max-width: 480px) {
            .profile-image .image-container {
                height: auto;
            }
            .profile {
                grid-template-columns: minmax(120px, 0.38fr) 0.62fr; /* Maintain 40/60 ratio */
            }

            .profile-image {
                grid-row: 1 / span 3;
                width: 100%;
                max-width: none;
                aspect-ratio: 2/3 !important;
                margin: 0;
            }

            .profile-image img {
                width: 100%;
                aspect-ratio: 2/3 !important;
                object-fit: cover;
                
            }

            .profile-user-settings,
            .profile-stats,
            .profile-bio {
                padding-left: 1rem;
            }
        }

        @media screen and (max-width: 420px) {

            .profile-image {
                grid-row: 1 / span 3;
                width: 100%;
                max-width: none; /* Remove max-width constraint */
                aspect-ratio: 2/3 !important;
                margin: 0;
            }

            .profile-image img {
                width: 100%;
                aspect-ratio: 2/3 !important;
                object-fit: cover;
                
            }

            .profile-user-settings,
            .profile-stats,
            .profile-bio {
                padding-left: 1rem;
            }
        }

        @media screen and (max-width: 360px) {
            .profile {
                grid-template-columns: minmax(100px, 0.38fr) 0.62fr; /* Maintain 40/60 ratio */
            }
        }

        @media screen and (max-width: 320px) {
            .profile-image {
                grid-row: 1 / span 3; /* Explicitly span 3 rows */
                width: 100px;
                max-width: 100px;
                aspect-ratio: 2/3 !important;
                margin: 0;
            }

            .profile-image img {
                width: 100%;
                aspect-ratio: 2/3 !important;
                object-fit: cover;
                 /* Remove fixed height */
            }
        }

        @media screen and (max-width: 260px) {
            .profile {
                grid-template-columns: 60px 1fr;
                grid-gap: 0.2rem;
            }

            .profile-image {
                grid-row: 1 / span 3; /* Explicitly span 3 rows */
                width: 60px;
                max-width: 60px;
                aspect-ratio: 2/3 !important;
                margin: 0;
            }

            .profile-image img {
                width: 100%;
                aspect-ratio: 2/3 !important;
                object-fit: cover;
                 /* Remove fixed height */
            }
        }

        @media screen and (max-width: 260px) {
            .profile {
                grid-template-columns: 60px 1fr;
                grid-gap: 0.2rem;
            }

            .profile-image {
                width: 60px;
                max-width: 60px;
            }

            .profile-image img {
                width: 60px;
                height: 90px;
            }

            .profile-bio p {
                font-size: 0.9rem !important;
                margin: 0.1rem 0;
            }

            .profile-user-name {
                font-size: 1rem;
            }
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
        }

        .profile-image img {
            width: 100%;
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
            order: 1;
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



    @media screen and (max-width: 600px) {
        .profile-stats{
            margin-left: 0;
        }
        .profile-stats li {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .profile-stat-count {
            display: inline;
            margin: 0;
            font-size: 1.2rem;
        }

        .profile-stats ul {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
    }

    @media screen and (max-width: 480px) {
        .container-mobile {
            margin-top: 0!important;
        }

        .link, .link a {
            font-size: 12px!important;
        }

        .properties {
            flex-direction: row;
        }

        .properties p:nth-child(2) {
            margin-left: 15px;
        }

        .mt-5 {
            margin-top: 10px!important;
        }
        .text-city {
            font-size: 13px!important;
        }
        .profile-stats li {
            display: inline-block;
            line-height: 1.5;
            margin-right: 0.7rem;
        }

        .profile-user-name {
            font-size: 18px;
            margin: 0;
        }

        .profile-stats {
            width: 100%;
            justify-content: space-between;
        }

        .profile-stats ul { 
            gap: 0!important;
            margin: 0!important; padding: 0!important;
            justify-content: space-between;
        }

        .profile-stats ul li {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 1.1rem;
            gap: 0!important;
            margin: 0!important; padding: 0!important;
        }

        .profile-bio {
            margin: 0;
            width: 100%;
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

    .link-icon {
        font-size: 18px;
        color: #f65807!important;
    }
    
    .link-text {
        color: #f65807!important;
        font-size: 16px;
        text-decoration: underline !important;
    }

    @media screen and (max-width: 480px) {
        .link-text { font-size: 14px !important; }
        .link-icon { font-size: 16px !important; }
    }

    @media screen and (max-width: 380px) {
        .link-text { font-size: 12px !important; }
        .link-icon { font-size: 14px !important; }
    }

    @media screen and (max-width: 320px) {
        .link-text { font-size: 11px !important; }
        .link-icon { font-size: 13px !important; }
    }

    @media screen and (max-width: 260px) {
        .link-text { font-size: 10px !important; }
        .link-icon { font-size: 12px !important; }
    }

    .flame-border-profile {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        pointer-events: none;
    }

    /* Variaciones de color usando filtros CSS */
    .flame-color-1 { filter: hue-rotate(0deg) saturate(100%) brightness(100%); }
    .flame-color-2 { filter: hue-rotate(30deg) saturate(150%) brightness(110%); }
    .flame-color-3 { filter: hue-rotate(60deg) saturate(140%) brightness(120%); }
    .flame-color-4 { filter: hue-rotate(120deg) saturate(150%) brightness(90%); }
    .flame-color-5 { filter: hue-rotate(180deg) saturate(130%) brightness(100%); }
    .flame-color-6 { filter: hue-rotate(240deg) saturate(160%) brightness(110%); }
    .flame-color-7 { filter: hue-rotate(270deg) saturate(140%) brightness(100%); }
    .flame-color-8 { filter: hue-rotate(300deg) saturate(150%) brightness(120%); }
    .flame-color-9 { filter: hue-rotate(330deg) saturate(170%) brightness(90%); }
    .flame-color-10 { filter: hue-rotate(15deg) saturate(200%) brightness(130%); }
    .flame-color-11 { filter: hue-rotate(90deg) saturate(120%) brightness(140%); }
    .flame-color-12 { filter: hue-rotate(150deg) saturate(180%) brightness(80%); }
    .flame-color-13 { filter: hue-rotate(200deg) saturate(160%) brightness(110%); }
    .flame-color-14 { filter: hue-rotate(290deg) saturate(130%) brightness(120%); }
    .flame-color-15 { filter: hue-rotate(320deg) saturate(190%) brightness(100%); }

    .availability-text {
        display: block;
        color: #F65807;
        font-size: 1.4rem;
        margin-top: 0.5rem;
    }

    .text-justify {
        text-align: justify;
    }
    
    .profile-bio p {
        text-align: justify;
    }

    .profile-user-name {
        text-align: justify;
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
                outputStr[i];
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