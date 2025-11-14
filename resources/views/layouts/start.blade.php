<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre Hotspania, el portal de masajistas y anuncios para adultos en España. Perfiles verificados, máxima discreción y un sitio rápido pensado para tu comodidad">
    <meta name="author" content="jagcweb">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>Hotspania - Bienvenido</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #131313;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(247, 110, 8, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 69, 0, 0.08) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Efectos de fuego suaves */
        .fire-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 8px;
            background: linear-gradient(to top, #F76E08, #ff6b35, transparent);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            animation: float-flame 6s infinite ease-in-out;
            opacity: 0;
        }

        @keyframes float-flame {
            0% {
                opacity: 0;
                transform: translateY(100vh) translateX(0px) scale(0.5);
            }
            15% {
                opacity: 0.8;
                transform: translateY(85vh) translateX(10px) scale(1);
            }
            85% {
                opacity: 0.6;
                transform: translateY(15vh) translateX(-10px) scale(0.8);
            }
            100% {
                opacity: 0;
                transform: translateY(0) translateX(5px) scale(0.3);
            }
        }

        /* Contenedor principal mejorado */
        .container {
            background: linear-gradient(145deg, rgba(25, 25, 25, 0.95), rgba(15, 15, 15, 0.98));
            border: 1px solid rgba(247, 110, 8, 0.3);
            border-radius: 24px;
            padding: 60px 50px;
            text-align: center;
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.4),
                0 0 40px rgba(247, 110, 8, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            z-index: 2;
            max-width: 480px;
            width: 90%;
            backdrop-filter: blur(20px);
            animation: containerEntry 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        @keyframes containerEntry {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
                filter: blur(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        /* Logo mejorado */
        .logo-placeholder {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #F76E08 0%, #ff4500 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            color: #131313;
            font-weight: 900;
            box-shadow: 
                0 10px 30px rgba(247, 110, 8, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .logo-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: logoShine 3s ease-in-out infinite;
        }

        @keyframes logoShine {
            0%, 80% { left: -100%; }
            100% { left: 100%; }
        }

        /* Título elegante */
        .title {
            font-size: 3.2em;
            font-weight: 900;
            background: linear-gradient(135deg, #F76E08 0%, #ff6b35 50%, #F76E08 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            text-shadow: 0 0 30px rgba(247, 110, 8, 0.3);
            letter-spacing: -2px;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 50px;
            font-size: 1.1em;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        /* Selector de ciudad mejorado */
        .city-selector {
            margin-bottom: 40px;
        }

        .city-selector label {
            display: block;
            color: #F76E08;
            margin-bottom: 20px;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .select-wrapper {
            position: relative;
        }

        .city-selector select {
            width: 100%;
            padding: 18px 24px;
            font-size: 16px;
            font-weight: 500;
            border: 2px solid rgba(247, 110, 8, 0.3);
            border-radius: 16px;
            background: rgba(25, 25, 25, 0.8);
            color: #ffffff;
            outline: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            appearance: none;
        }

        .city-selector select:focus {
            border-color: #F76E08;
            box-shadow: 0 0 0 4px rgba(247, 110, 8, 0.1);
            background: rgba(35, 35, 35, 0.9);
        }

        .select-wrapper::after {
            content: '▼';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #F76E08;
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .select-wrapper:hover::after {
            transform: translateY(-50%) scale(1.1);
        }

        .city-selector option {
            background: #1a1a1a;
            color: #fff;
            padding: 12px;
            font-weight: 500;
        }

        .enter-btn {
            display: flex;
            justify-content: center;
            width: 100%;
            position: relative;
            background: #2a2a2a;
            color: #fff;
            border: 2px solid #444;
            height: 60px !important;   /* altura fija */
            padding: 0 20px;           /* padding horizontal */
            font-size: 1.2em;
            font-weight: 700;
            border-radius: 16px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'Poppins', sans-serif;
            transition: background 0.4s ease, border-color 0.4s ease, 
                        transform 0.4s ease, box-shadow 0.4s ease, color 0.4s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3),
                        inset 0 1px 0 rgba(255,255,255,0.1);
            z-index: 10;
            text-align: center;
            overflow: hidden;
            display: flex;              
            align-items: center;
            justify-content: center;
            gap: 8px;
        }


        /* Texto del botón */
        .enter-btn span {
            display: inline-block;
            position: relative;
            z-index: 5;
            text-align: center!important;
        }

        /* Spinner */
        .enter-btn .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
            flex-shrink: 0; /* no deforma el botón */
        }
        .enter-btn.loading .loading-spinner {
            opacity: 1;
        }

        /* Animación de spinner */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


        .enter-btn span {
            position: relative;
            z-index: 5;
            display: flex;          /* FLEX desde el inicio */
            align-items: center;
            justify-content: center;
            gap: 8px;               /* Espacio entre texto y spinner */
            transition: all 0.3s ease;
        }



        .enter-btn.loading span {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Espacio entre texto y spinner */
        }

        /* Estilos para el spinner */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Efecto de fuego desde abajo */
        .enter-btn::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: linear-gradient(to top, 
                #F76E08 0%, 
                #ff4500 30%, 
                #ff6b35 60%, 
                rgba(247, 110, 8, 0.8) 80%,
                transparent 100%);
            border-radius: 0 0 16px 16px;
            transition: height 0.5s ease;
            z-index: -1;
        }

        /* Resplandor exterior */
        .enter-btn::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            background: linear-gradient(45deg, #F76E08, #ff4500, #F76E08);
            border-radius: 20px;
            opacity: 0;
            filter: blur(15px);
            transition: all 0.4s ease;
            z-index: -2;
        }

        /* Efectos al hacer hover */
        .enter-btn:hover:not(.loading) {
            background: linear-gradient(135deg, #F76E08 0%, #ff4500 50%, #F76E08 100%);
            color: #ffffff;
            border-color: #ff6b35;
            transform: translateY(-3px);
            box-shadow: 
                0 12px 30px rgba(247, 110, 8, 0.4),
                0 0 40px rgba(247, 110, 8, 0.2);
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            font-weight: 800;
        }

        .enter-btn:hover:not(.loading)::before {
            height: 100%;
            border-radius: 16px;
        }

        .enter-btn:hover:not(.loading)::after {
            opacity: 0.6;
        }

        .enter-btn:active:not(.loading) {
            transform: translateY(-1px);
            transition-duration: 0.1s;
        }

        /* Texto siempre visible */
        .enter-btn span {
            position: relative;
            z-index: 5;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .enter-btn:hover:not(.loading) span {
            text-shadow: 
                0 0 10px rgba(0, 0, 0, 0.8),
                0 1px 2px rgba(0, 0, 0, 0.9);
            color: #ffffff;
        }

        /* Efecto de ripple en el botón */
        .enter-btn {
            position: relative;
            overflow: hidden;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Animaciones de entrada escalonadas */
        .logo-placeholder {
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.2s both;
        }

        .title {
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.4s both;
        }

        .subtitle {
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.6s both;
        }

        .city-selector {
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.8s both;
        }

        .enter-btn {
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 1s both;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 520px) {
            .container {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .title {
                font-size: 2.5em;
            }
            
            .enter-btn {
                padding: 18px 40px;
                font-size: 1.1em;
                min-width: 180px; /* Ancho mínimo menor en móviles */
            }
        }

        /* === ESTILOS DEL MODAL === */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: block;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            max-width: 400px; /* Ancho máximo más pequeño */
            margin: 0.5rem auto; /* Centrado automático */
            pointer-events: none;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center; /* Centrado horizontal */
            min-height: calc(100% - 1rem);
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 350px; /* Altura mínima para hacerlo más cuadrado */
            pointer-events: auto;
            background: linear-gradient(145deg, rgba(25, 25, 25, 0.98), rgba(15, 15, 15, 0.98));
            border: 1px solid rgba(247, 110, 8, 0.3);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: center; /* Centrar el título */
            padding: 30px 30px 25px; /* Más padding superior */
            border-bottom: 1px solid rgba(247, 110, 8, 0.2);
        }

        .modal-title {
            margin-bottom: 0;
            line-height: 1.5;
            color: #F76E08;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.3em;
            text-align: center;
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 30px 30px; /* Más padding */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: center; /* Centrar el botón */
            padding: 25px 30px 30px; /* Más padding inferior */
            border-top: 1px solid rgba(247, 110, 8, 0.2);
        }

        .form-check {
            position: relative;
            display: block;
            padding-left: 1.25rem;
            text-align: center; /* Centrar el texto del checkbox */
        }

        .form-check-input {
            position: absolute;
            margin-top: 0.3rem;
            margin-left: -1.25rem;
            accent-color: #F76E08;
        }

        .form-check-label {
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.9);
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            letter-spacing: normal;
            text-transform: none;
        }

        .modal-footer .btn {
            background: linear-gradient(135deg, #F76E08 0%, #ff4500 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            padding: 15px 40px; /* Menos ancho */
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(247, 110, 8, 0.3);
            width: auto; /* No 100% de ancho */
            min-width: 150px; /* Ancho mínimo */
        }

        .modal-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(247, 110, 8, 0.4);
        }

        /* Responsive para el modal */
        @media (max-width: 480px) {
            .modal-dialog {
                max-width: 90%; /* En móviles sí usar casi todo el ancho */
                margin: 1rem auto;
            }
            
            .modal-content {
                min-height: 300px; /* Altura mínima menor en móviles */
            }
            
            .modal-footer .btn {
                width: 100%; /* En móviles sí usar todo el ancho */
                padding: 15px 30px;
            }
        }

        /* === ESTILOS ADICIONALES PARA ZONA === */
        #appened-zones {
            margin-bottom: 20px;
        }

        #appened-zones .select-wrapper {
            position: relative;
        }

        #appened-zones select {
            width: 100%;
            padding: 18px 24px;
            font-size: 16px;
            font-weight: 500;
            border: 2px solid rgba(247, 110, 8, 0.3);
            border-radius: 16px;
            background: rgba(25, 25, 25, 0.8);
            color: #ffffff;
            outline: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            appearance: none;
        }

        #appened-zones select:focus {
            border-color: #F76E08;
            box-shadow: 0 0 0 4px rgba(247, 110, 8, 0.1);
            background: rgba(35, 35, 35, 0.9);
        }

        #appened-zones .select-wrapper::after {
            content: '▼';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #F76E08;
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        #appened-zones option {
            background: #1a1a1a;
            color: #fff;
            padding: 12px;
            font-weight: 500;
        }

        .loading-text {
            color: #F76E08;
            font-size: 14px;
            opacity: 0.8;
        }

        .zone-label {
            display: block;
            color: #F76E08;
            margin-bottom: 20px;
            font-size: 1.1em;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Estado deshabilitado del botón */
        .enter-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            border-color: #666;
            background: #2a2a2a;
            color: #888;
        }

        .enter-btn:disabled:hover {
            background: #2a2a2a;
            color: #888;
            border-color: #666;
            transform: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .enter-btn:disabled::before {
            display: none;
        }

        .enter-btn:disabled::after {
            display: none;
        }
    </style>

    <!-- Privacy-friendly analytics by Plausible -->
    <script async src="https://plausible.io/js/pa-aMbipCPSJEpxFZ9WmeSa8.js"></script>
    <script>
        window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
        plausible.init()
    </script>
</head>
<body>
    <!-- Partículas de fuego suaves -->
    <div class="fire-particles" id="fireParticles"></div>

    <!-- Contenedor principal -->
    <div class="container">
        <!-- Logo placeholder -->
        <img class="img_logo" src="{{ asset('images/logo2.png') }}" alt="Logo" style="max-width: 150px;"/>

        <!-- Título -->
        <h1 class="title">HOTSPANIA</h1>
        <p class="subtitle">Enciende tu pasión</p>
        
        <!-- Selector de ciudad -->
        <div class="city-selector">
            <label for="city_id">Selecciona tu ciudad:</label>
            <div class="select-wrapper">
                 @php $cities = \App\Models\City::where('name', 'Barcelona')->orderBy('id', 'asc')->get(); @endphp
                <select id="city_id" name="city" required autocomplete="off">
                    <!--<option value="" hidden selected disabled>
                        Escoge una ciudad...
                    </option>-->
                    @foreach($cities as $c)
                        <option selected value="{{strtolower($c->id)}}">{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Selector de zona -->
        <div id="appened-zones"></div>
        
        <!-- Botón con efecto de fuego simple -->
        <button class="enter-btn" id="log" {{-- disabled --}}>
            <span>ENTRAR</span>
            <div class="loading-spinner" style="display: none;"></div>
        </button>
    </div>

    <!-- Modal de términos y condiciones -->
    <div class="modal fade" id="imageGuidelinesModal" tabindex="-1" role="dialog" aria-labelledby="imageGuidelinesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageGuidelinesModalLabel">Sitio para mayores de 18 años.</h5>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ageCheck" checked>
                        <label class="form-check-label" for="ageCheck">
                            <a target="_blank" style="color: #F76E08; text-decoration: underline!important;" href="/documents/Terminos_y_Condiciones.pdf">
                                acepto los términos y condiciones.
                            </a>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="acceptTerms()">Acceder</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Crear partículas de fuego más elegantes
        function createFireParticles() {
            const container = document.getElementById('fireParticles');
            
            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (6 + Math.random() * 3) + 's';
                
                // Variaciones de color más sutiles
                const colors = [
                    'linear-gradient(to top, #F76E08, #ff6b35, transparent)',
                    'linear-gradient(to top, #ff4500, #F76E08, transparent)',
                    'linear-gradient(to top, #ff6600, #ff8c42, transparent)'
                ];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                
                container.appendChild(particle);
                
                // Eliminar partícula después de la animación
                setTimeout(() => {
                    if (container.contains(particle)) {
                        container.removeChild(particle);
                    }
                }, 10000);
            }
            
            // Crear menos partículas para un efecto más sutil
            setInterval(createParticle, 800);
        }
        
        // Efecto ripple en el botón
        function createRipple(event) {
            const button = event.currentTarget;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
        
        // Animación de shake para el selector
        const shakeKeyframes = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
        `;
        
        const style = document.createElement('style');
        style.textContent = shakeKeyframes;
        document.head.appendChild(style);

        // === FUNCIONES PARA COOKIES ===
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        function setCookie(name, value, days) {
            const expirationDate = new Date();
            expirationDate.setDate(expirationDate.getDate() + days);
            document.cookie = `${name}=${value}; expires=${expirationDate.toUTCString()}; path=/`;
        }

        // === MODAL DE TÉRMINOS Y CONDICIONES ===
        function showModal() {
            const modal = document.getElementById('imageGuidelinesModal');
            modal.classList.add('show');
            modal.style.display = 'block';
        }

        function hideModal() {
            const modal = document.getElementById('imageGuidelinesModal');
            modal.classList.remove('show');
            modal.style.display = 'none';
        }

        function acceptTerms() {
            setCookie('terms', 'accepted', 365); // Cookie por 1 año
            hideModal();
        }

        // === AJAX Y FUNCIONALIDAD DEL FORMULARIO ===
        document.addEventListener("DOMContentLoaded", function() {
            const citySelect = document.getElementById("city_id");
            let selectedCity = citySelect.value;
            document.getElementById('log').disabled = !selectedCity;
            if (selectedCity) {
                citySelect.style.color = '#F76E08';
                citySelect.style.fontWeight = '600';
            } else {
                citySelect.style.color = '#ffffff';
                citySelect.style.fontWeight = '500';
            }
            document.getElementById('city_id').addEventListener('change', function() {
                selectedCity = citySelect.value;
                if (selectedCity) {
                    citySelect.style.color = '#F76E08';
                    citySelect.style.fontWeight = '600';
                } else {
                    citySelect.style.color = '#ffffff';
                    citySelect.style.fontWeight = '500';
                }
                
                document.getElementById('log').disabled = !selectedCity;

                // Guardar cookie de ciudad
                setCookie('selected_city', selectedCity, 30);

                // AJAX para obtener zonas
                const appenedZones = document.getElementById('appened-zones');
                appenedZones.innerHTML = '';
                
                /*if (selectedCity) {
                    appenedZones.innerHTML = '<div class="loading-text">Cargando zonas...</div>';

                    // Simular petición AJAX (puedes reemplazar con tu endpoint real)
                    fetch(`/home/get-zones/${selectedCity}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('No se encontraron zonas para esta ciudad.');
                            }
                            return response.json();
                        })
                        .then(zones => {
                            if (Array.isArray(zones) && zones.length > 0) {
                                let html = '<label for="zone_id" class="zone-label">Selecciona tu zona:</label>';
                                html += '<div class="select-wrapper">';
                                html += '<select id="zone_id" name="zone" required autocomplete="off">';
                                html += '<option value="" hidden selected disabled>-- Elige tu zona --</option>';
                                zones.forEach(zone => {
                                    html += `<option value="${zone.id}">${zone.name}</option>`;
                                });
                                html += '</select>';
                                html += '</div>';
                                appenedZones.innerHTML = html;

                                // Event listener para el selector de zona
                                document.getElementById('zone_id').addEventListener('change', function() {
                                    const zoneSelect = document.getElementById('zone_id');
                                    const selectedZone = zoneSelect.value;
                                    
                                    if (selectedZone) {
                                        zoneSelect.style.color = '#F76E08';
                                        zoneSelect.style.fontWeight = '600';
                                    } else {
                                        zoneSelect.style.color = '#ffffff';
                                        zoneSelect.style.fontWeight = '500';
                                    }
                                    
                                    setCookie('selected_zone', selectedZone, 30);
                                    document.getElementById('log').disabled = !selectedCity || !selectedZone;
                                });
                            } else {
                                appenedZones.innerHTML = '<div style="color: rgba(255,255,255,0.7); font-size: 14px;">No hay zonas disponibles para esta ciudad.</div>';
                            }
                        })
                        .catch(error => {
                            appenedZones.innerHTML = `<div style="color: #ff6b35; font-size: 14px;">${error.message}</div>`;
                        });
                }*/
            });
        });

        // === FUNCIÓN DEL BOTÓN ENTRAR SIMPLIFICADA ===
        function enterApp(event) {
            const citySelect = document.getElementById('city_id');
            const zoneSelect = document.getElementById('zone_id');
            console.log('City selected:', citySelect.value);
            if (!citySelect.value) {
                const select = document.querySelector('.city-selector .select-wrapper');
                select.style.animation = 'shake 0.5s';
                setTimeout(() => {
                    select.style.animation = '';
                }, 500);
                return;
            }
            
            /*if (!zoneSelect || !zoneSelect.value) {
                const zoneWrapper = document.querySelector('#appened-zones .select-wrapper');
                if (zoneWrapper) {
                    zoneWrapper.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        zoneWrapper.style.animation = '';
                    }, 500);
                }
                return;
            }*/
            
            const button = event.currentTarget;
            const span = button.querySelector('span');
            const spinner = button.querySelector('.loading-spinner');
            
            // Evitar múltiples clics
            if (button.classList.contains('loading')) {
                return;
            }
            
            // Crear efecto ripple
            createRipple(event);
            
            // Activar estado de carga
            button.classList.add('loading');
            button.disabled = true;
            button.style.opacity = '0.8';
            
            // Cambiar texto y mostrar spinner
            span.textContent = 'CARGANDO';
            spinner.style.display = 'inline-block';

            setTimeout(() => {
                // Restaurar estado original
                span.textContent = 'ENTRAR';
                spinner.style.display = 'none';
                button.classList.remove('loading');
                button.style.opacity = '1';
                button.disabled = false;
                
                // Redirigir a /home
                window.location.href = '/home';
            }, 1000);
        }
        // === INICIALIZACIÓN ===
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si necesita mostrar el modal
            if (!getCookie('terms')) {
                showModal();
            }

            // Cargar ciudades y zonas guardadas en cookies
            const savedCity = getCookie('selected_city');
            const savedZone = getCookie('selected_zone');
            
            if (savedCity) {
                const citySelect = document.getElementById('city_id');
                citySelect.value = savedCity;
                citySelect.style.color = '#F76E08';
                citySelect.style.fontWeight = '600';
                
                // Disparar evento change para cargar zonas
                citySelect.dispatchEvent(new Event('change'));
            }
            
            // Verificar estado del botón
            //if (savedCity && savedZone) {
            /*if (savedCity) {
                document.getElementById('log').disabled = false;
            } else {
                document.getElementById('log').disabled = true;
            }*/

            // Inicializar efectos
            createFireParticles();
        });

        // Mejorar interacción del selector
        document.getElementById('city_id').addEventListener('change', function() {
            if (this.value) {
                this.style.color = '#F76E08';
                this.style.fontWeight = '600';
            } else {
                this.style.color = '#ffffff';
                this.style.fontWeight = '500';
            }
        });
        
        // Event listener para el botón - SOLO UNO
        document.getElementById('log').addEventListener('click', enterApp);
        // Eliminamos esta línea: document.getElementById('log').addEventListener('click', createRipple);
    </script>
</body>
</html>