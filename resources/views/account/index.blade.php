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
                <!-- 1. Username -->
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <h1 class="profile-username-centered">
                        {{ \Auth::user()->nickname }}
                    </h1>

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

            @php
                $isAvailable = false;
                $remainingTime = '';
                $isVisible = !is_null(\Auth::user()->visible);
                $canMakeAvailable = true;
                
                if (\Auth::user()->available_until !== null) {
                    $now = \Carbon\Carbon::now('Europe/Madrid');
                    $endTime = \Carbon\Carbon::parse(\Auth::user()->available_until)->setTimezone('Europe/Madrid');
                    $isAvailable = $now->lt($endTime);
                    
                    if ($isAvailable) {
                        $canMakeAvailable = false;
                        $diff = $now->diff($endTime);
                        $remainingTime = sprintf('%d:%02d:%02d', ($diff->days * 24) + $diff->h, $diff->i, $diff->s);
                    }
                }
                
                $u = \Auth::user();
            @endphp

            <!-- Contenedor centrado -->
            <div class="profile-centered-container">
                
                <!-- 2. Badge Disponibilidad -->
                <div class="availability-status-centered">
                    {{--@if($isAvailable)
                        <span class="status-label status-available">Disponible</span>
                    @endif--}}
                </div>

                <!-- 3. Timer (solo si está disponible) -->
                @if($isAvailable)
                    <div class="countdown-timer-centered" id="availability-countdown">
                        {{ $remainingTime }}
                    </div>
                @endif

                <!-- 4. Botón para controlar disponibilidad -->
                @if($isAvailable)
                    <!-- Botón para apagar disponibilidad -->
                    {{--<a href="{{ route('account.make_unavailable', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}" 
                    class="btn-availability-control btn-turn-off">
                        <i class="fa-solid fa-power-off"></i>
                        <span>Apagar disponibilidad</span>
                    </a>--}}
                @else
                    <!-- Botón para activar disponibilidad (abre modal) -->
                    <a href="javascript:void(0);" 
                    data-toggle="modal" 
                    data-target="#hacer-disponible-{{ $u->id }}" 
                    class="btn-availability-control btn-turn-on">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Ponte disponible</span>
                    </a>
                @endif

                <!-- 5. Slider "Desliza para Off" (visible solo si la ficha está visible) -->
                @if($isVisible)
                <div class="slider-container">
                    <div class="slider-track" id="sliderTrack">
                        <div class="slider-thumb" id="sliderThumb">
                            <i class="fa-solid fa-eye-slash"></i>
                        </div>
                        <span class="slider-text" id="sliderText">Ocultar ficha</span>
                    </div>
                </div>

                <!-- 6. "Tu ficha está visible" -->
                <p class="visibility-message">
                    <i class="fa-solid fa-eye"></i> Tu ficha está visible
                </p>
                @else
                <!-- Mensaje cuando la ficha no está visible -->
                <div class="ficha-invisible-message">
                    <a href="{{ route('account.visible', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}" class="btn-make-visible">
                        <i class="fa-solid fa-eye"></i>
                        Hacer visible
                    </a>
                    <p class="visibility-message" style="color: rgba(255, 107, 107, 0.8);">
                        <i class="fa-solid fa-eye-slash"></i> Tu ficha NO está visible
                    </p>
                </div>
                @endif

                <!-- 7. Botón Chat/Consulta -->
                <a id="consultarChat" href="javascript:void(0);" class="chat-consult-btn-centered" onclick="openChatConsult()">
                    Chat/Consulta
                </a>

            </div>

            <div class="profile-stats" style="display: none;"></div>
            <div class="profile-bio" style="display: none;"></div>

        </div>
        <!-- End of profile section -->

        <!-- Incluir modal de hacer disponible -->
        @include('modals.admin.modal_hacer_disponible')

        <!-- Modal de confirmación para apagar visibilidad -->
        <div class="modal fade" id="confirmVisibilityOffModal" tabindex="-1" role="dialog" aria-labelledby="confirmVisibilityOffLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="background: #1a1a1a; border: 2px solid #f36e00; border-radius: 15px;">
                    <div class="modal-header" style="border-bottom: 1px solid rgba(243, 110, 0, 0.3);">
                        <h5 class="modal-title" id="confirmVisibilityOffLabel" style="color: #fff; font-weight: 700;">
                            <i class="fa-solid fa-exclamation-triangle" style="color: #f36e00;"></i>
                            Confirmar ocultar ficha
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 30px; text-align: center;">
                        <i class="fa-solid fa-eye-slash" style="font-size: 60px; color: #f36e00; margin-bottom: 20px;"></i>
                        <p style="color: #fff; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                            <strong>Tu ficha se ocultará y perderás tu visibilidad.</strong><br>
                            Ya no aparecerás en Hotspania.
                        </p>
                        <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px;">
                            ¿Estás seguro de que deseas continuar?
                        </p>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(243, 110, 0, 0.3); display: flex; justify-content: center; gap: 15px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background: #757575; border: none; padding: 10px 25px; border-radius: 25px; font-weight: 600;">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </button>
                        <a href="{{ route('account.visible', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}" class="btn btn-danger" style="background: linear-gradient(135deg, #f36e00 0%, #ff8c42 100%); border: none; padding: 10px 25px; border-radius: 25px; font-weight: 600; color: #fff; text-decoration: none;">
                            <i class="fa-solid fa-eye-slash"></i> Sí, ocultar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            @if($isAvailable)
            // Update countdown timer
            function updateAvailabilityCountdown() {
                const endTime = new Date("{{ \Auth::user()->available_until }}").getTime();
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    location.reload();
                    return;
                }

                const totalHours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const countdownElement = document.getElementById("availability-countdown");
                if (countdownElement) {
                    const timeStr = totalHours + ":" +
                        minutes.toString().padStart(2,'0') + ":" +
                        seconds.toString().padStart(2,'0');

                    // Mostrar el tiempo y el botón "Apagar disponibilidad" al lado
                    countdownElement.innerHTML = `
                        <span class="countdown-text" style="vertical-align:middle; font-family: inherit;">
                            ${timeStr}
                        </span>
                        <a title="Apagar disponibilidad"
                           href="{{ route('account.make_unavailable', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}"
                           style="font-size: 16px!important;">
                            <i class="fa-solid fa-power-off"></i>
                        </a>
                    `;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateAvailabilityCountdown();
                setInterval(updateAvailabilityCountdown, 1000);
            });
            @endif

            @if($isVisible)
            // Slider functionality para ocultar la ficha
            document.addEventListener('DOMContentLoaded', function() {
                initSlider();
            });

            function initSlider() {
                const sliderThumb = document.getElementById('sliderThumb');
                const sliderTrack = document.getElementById('sliderTrack');
                const sliderText = document.getElementById('sliderText');
                
                if (!sliderThumb || !sliderTrack) return;

                let isDragging = false;
                let startX = 0;
                let currentX = 0;
                const maxSlide = sliderTrack.offsetWidth - sliderThumb.offsetWidth;

                // Mouse events
                sliderThumb.addEventListener('mousedown', startDrag);
                document.addEventListener('mousemove', drag);
                document.addEventListener('mouseup', endDrag);

                // Touch events
                sliderThumb.addEventListener('touchstart', startDrag);
                document.addEventListener('touchmove', drag);
                document.addEventListener('touchend', endDrag);

                function startDrag(e) {
                    isDragging = true;
                    startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
                    sliderThumb.style.transition = 'none';
                }

                function drag(e) {
                    if (!isDragging) return;
                    
                    e.preventDefault();
                    const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
                    currentX = clientX - startX;
                    
                    // Limitar el movimiento
                    if (currentX < 0) currentX = 0;
                    if (currentX > maxSlide) currentX = maxSlide;
                    
                    sliderThumb.style.transform = `translateX(${currentX}px)`;
                    
                    // Cambiar opacidad del texto mientras se desliza
                    const opacity = 1 - (currentX / maxSlide);
                    sliderText.style.opacity = opacity;
                }

                function endDrag() {
                    if (!isDragging) return;
                    isDragging = false;
                    
                    // Si se deslizó más del 80%, mostrar modal de confirmación
                    if (currentX > maxSlide * 0.8) {
                        sliderThumb.style.transition = 'transform 0.3s ease';
                        sliderThumb.style.transform = `translateX(${maxSlide}px)`;
                        sliderText.style.opacity = '0';
                        
                        // Mostrar modal de confirmación
                        setTimeout(() => {
                            $('#confirmVisibilityOffModal').modal('show');
                            
                            // Resetear slider cuando se cierra el modal sin confirmar
                            $('#confirmVisibilityOffModal').on('hidden.bs.modal', function () {
                                sliderThumb.style.transform = 'translateX(0)';
                                sliderText.style.opacity = '1';
                                currentX = 0;
                            });
                        }, 300);
                    } else {
                        // Volver a la posición inicial
                        sliderThumb.style.transition = 'transform 0.3s ease';
                        sliderThumb.style.transform = 'translateX(0)';
                        sliderText.style.opacity = '1';
                        currentX = 0;
                    }
                }
            }
            @endif
        </script>

        <style>
            /* Contenedor centrado */
            .profile-centered-container {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
                margin-top: 20px;
                padding: 0 20px;
            }

            /* 1. Username centrado */
            .profile-username-centered {
                color: #fff;
                font-size: 28px;
                font-weight: 700;
                margin: 0;
                text-align: center;
            }

            /* 2. Badge de disponibilidad */
            .availability-status-centered {
                display: flex;
                justify-content: center;
            }

            .status-label {
                padding: 10px 30px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .status-available {
                background: linear-gradient(135deg, #4CAF50 0%, #81C784 100%);
                color: #fff;
                border: 2px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            }

            .status-unavailable {
                background: linear-gradient(135deg, #757575 0%, #9E9E9E 100%);
                color: #fff;
                border: 2px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 4px 15px rgba(117, 117, 117, 0.3);
            }

            /* 3. Timer */
            .countdown-timer-centered, .countdown-timer-centered a {
                color: #f36e00;
                font-size: 36px;
                font-weight: 700;
                font-family: 'Courier New', monospace;
                letter-spacing: 3px;
                text-align: center;
                text-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            }

            /* 4. Botones de control de disponibilidad */
            .btn-availability-control {
                width: 100%;
                max-width: 350px;
                padding: 15px 30px;
                border-radius: 30px;
                color: #fff !important;
                text-decoration: none !important;
                font-weight: 600;
                font-size: 15px;
                text-align: center;
                transition: all 0.3s ease;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                border: 2px solid rgba(255, 255, 255, 0.2);
            }

            .btn-turn-on {
                background: linear-gradient(135deg, #4CAF50 0%, #81C784 100%);
                box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            }

            .btn-turn-on:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5);
                text-decoration: none !important;
                color: #fff !important;
            }

            .btn-turn-off {
                background: linear-gradient(135deg, #f36e00 0%, #ff8c42 100%);
                box-shadow: 0 4px 15px rgba(243, 110, 0, 0.3);
            }

            .btn-turn-off:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(243, 110, 0, 0.5);
                text-decoration: none !important;
                color: #fff !important;
            }

            .btn-availability-control i {
                font-size: 18px;
                margin: 0 !important;
            }

            /* 5. Slider */
            .slider-container {
                width: 100%;
                max-width: 350px;
                padding: 10px 0;
            }

            .slider-track {
                position: relative;
                width: 100%;
                height: 60px;
                background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
                border: 2px solid #757575;
                border-radius: 35px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
            }

            .slider-text {
                position: absolute;
                color: rgba(255, 255, 255, 0.7);
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .slider-thumb {
                position: absolute;
                left: 5px;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #757575 0%, #9E9E9E 100%);
                border-radius: 50%;
                cursor: grab;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 20px;
                box-shadow: 0 4px 15px rgba(117, 117, 117, 0.5);
                transition: transform 0.3s ease;
                z-index: 1;
            }

            .slider-thumb:active {
                cursor: grabbing;
                transform: scale(1.1);
            }

            .slider-thumb i {
                margin: 0 !important;
                pointer-events: none;
            }

            /* 6. Mensaje de visibilidad */
            .visibility-message {
                color: rgba(255, 255, 255, 0.6);
                font-size: 13px;
                margin: 0;
                font-style: italic;
                text-align: center;
                display: flex;
                align-items: center;
                gap: 8px;
                justify-content: center;
            }

            .visibility-message i {
                font-size: 16px;
                margin: 0 !important;
            }

            /* Cuando la ficha no está visible */
            .ficha-invisible-message {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .btn-make-visible {
                background: linear-gradient(135deg, #4CAF50 0%, #81C784 100%);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 30px;
                padding: 12px 30px;
                color: #fff !important;
                text-decoration: none !important;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .btn-make-visible:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5);
                text-decoration: none !important;
                color: #fff !important;
            }

            .btn-make-visible i {
                margin: 0 !important;
            }

            /* 7. Botón Chat/Consulta */
            .chat-consult-btn-centered {
                width: 100%;
                max-width: 350px;
                background: #1a1a1a;
                border: 2px solid #f36e00;
                border-radius: 8px;
                padding: 15px 30px;
                color: #fff !important;
                text-decoration: none !important;
                font-weight: 600;
                font-size: 16px;
                text-align: center;
                transition: all 0.3s ease;
                cursor: pointer;
                text-transform: uppercase;
                letter-spacing: 1px;
                display: block;
            }

            .chat-consult-btn-centered:hover {
                background: #f36e00;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(243, 110, 0, 0.5);
                text-decoration: none !important;
                color: #fff !important;
            }

            /* Estilos del modal */
            .modal-content {
                animation: modalFadeIn 0.3s ease;
            }

            @keyframes modalFadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            /* Responsive */
            @media screen and (max-width: 768px) {
                .profile-username-centered {
                    font-size: 24px;
                }

                .countdown-timer-centered {
                    font-size: 30px;
                }

                .btn-availability-control {
                    max-width: 300px;
                    padding: 12px 25px;
                    font-size: 14px;
                }

                .slider-container {
                    max-width: 300px;
                }

                .slider-track {
                    height: 55px;
                }

                .slider-thumb {
                    width: 45px;
                    height: 45px;
                    font-size: 18px;
                }

                .chat-consult-btn-centered {
                    max-width: 300px;
                    font-size: 14px;
                    padding: 12px 25px;
                }
            }

            @media screen and (max-width: 480px) {
                .profile-username-centered {
                    font-size: 20px;
                }

                .countdown-timer-centered {
                    font-size: 26px;
                }

                .btn-availability-control {
                    max-width: 280px;
                    padding: 10px 20px;
                    font-size: 13px;
                }

                .slider-container {
                    max-width: 280px;
                }

                .slider-track {
                    height: 50px;
                }

                .slider-thumb {
                    width: 40px;
                    height: 40px;
                    font-size: 16px;
                }

                .slider-text {
                    font-size: 11px;
                    margin-left: 40px;
                }

                .visibility-message {
                    font-size: 11px;
                }

                .chat-consult-btn-centered {
                    max-width: 280px;
                    font-size: 13px;
                    padding: 10px 20px;
                }
            }
        </style>
    </div>

    <div class="container container_mobile">
        @php $u = \Auth::user(); @endphp
        {{--<a title="Anúnciate" href="javascript:void(0);" data-toggle="modal" data-target="#asignar-paquete-{{$u->id}}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            Anúnciate
            <i class="fa-solid fa-rocket ml-1"></i>
        </a>
        @include('modals.admin.modal_asignar_paquete')
        <a title="Subir fotos" href="{{ route('account.edit-data') }}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            Modificar datos
            <i class="fa-solid fa-user-pen ml-1"></i>
        </a>
        <a title="Subir fotos" href="javascript:void(0);" data-toggle="modal" data-target="#subir-fotos-{{$u->id}}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            Subir fotos
            <i class="fa-solid fa-upload ml-1"></i>
        </a>
        @include('modals.admin.fotos.modal_subir_fotos') --}}
        {{--<a title="Hacer cuenta visible" href="{{ route('account.visible', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            @if(!is_null(\Auth::user()->visible))
                Cuenta: Visible <i class="fa-solid fa-eye-slash ml-1"></i>
            @else
                Cuenta: NO visible <i class="fa-solid fa-eye ml-1"></i>
            @endif
        </a>

        @php
            $canMakeAvailable = true;
            if ($u->available_until !== null) {
                $now = \Carbon\Carbon::now('Europe/Madrid');
                $startTime = $now;
                $endTime = \Carbon\Carbon::parse($u->available_until)->setTimezone('Europe/Madrid');
                
                if ($now->lt($endTime)) {
                    $canMakeAvailable = false;
                    $remainingMinutes = $now->diffInMinutes($endTime);
                }
            }
        @endphp
        <a title="Ponte disponible" href="javascript:void(0);" data-toggle="modal" data-target="#hacer-disponible-{{$u->id}}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            Ponte disponible
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </a>
        @include('modals.admin.modal_hacer_disponible')
        @if (!$canMakeAvailable)
        <a title="Apagar disponibilidad" href="{{ route('account.make_unavailable', ['id' => \Crypt::encryptString(\Auth::user()->id)]) }}" class="btn btn-primary" style="background:#f36e00!important; color:#fff;">
            Apagar disponibilidad
            <i class="fa-solid fa-power-off"></i>
        </a>
        @endif --}}
        @include('account.partials.account-tabs')
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

    .properties {
        display: flex;
        flex-direction: column;
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
                url: `/account/load-more/${page + 1}/{{ \Auth::user()->id }}`,
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

    @media screen and (max-width: 350px) {
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
    document.getElementById('profile-edit-btn').addEventListener('click', function() {
        window.location.href = '/account/edit';
    });
    
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const videos = document.querySelectorAll('.gallery-item video');
        videos.forEach((video) => {
            video.setAttribute('crossorigin', 'anonymous');
            video.setAttribute('playsinline', '');
            video.muted = true;
            video.preload = 'metadata';  // Solo cargar los metadatos para evitar la reproducción automática
    
            const thumbnail = video.parentElement.querySelector('.video-thumbnail');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
    
            video.onloadeddata = function() {
                if (video.readyState >= 3) {
                    setTimeout(function() {
                        captureRandomFrame(video, thumbnail, canvas, ctx);
                    }, 300); // Aumentar el tiempo de espera para garantizar que el video está listo
                }
            };
        });
    
        function captureRandomFrame(video, thumbnail, canvas, ctx) {
            const randomTime = Math.random() * video.duration;
            video.currentTime = randomTime; // Establecer el tiempo al fotograma deseado
    
            video.onseeked = function() {
                // Solo capturamos el fotograma después de que el video haya cambiado al tiempo deseado
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Usamos requestAnimationFrame para una captura más confiable
                requestAnimationFrame(function() {
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageUrl = canvas.toDataURL('image/png');
                    thumbnail.src = imageUrl;
                    thumbnail.style.display = 'block';

                    // No es necesario reproducir el video, lo dejamos pausado
                    video.pause();
                });
            };
        }
    });
</script>

@endsection