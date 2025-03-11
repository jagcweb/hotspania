<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <meta name="description" content="Hotspania" />
        <meta name="author" content="jagcweb" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link
            href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
            rel="stylesheet"
        />
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') | Hotspania</title>
    

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com" />
        <link
            href="https://fonts.googleapis.com/css?family=Nunito"
            rel="stylesheet"
        />

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <link
            rel="stylesheet"
            type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css"
        />

        <link
            rel="stylesheet"
            type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />

        <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}" />

        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}" />

        <link
            rel="stylesheet"
            href="{{ asset('css/templatemo-hexashop.css') }}"
        />
    </head>

    <body style="background: #1d1d1d; min-height:100vh;">
        <!-- ***** Preloader Start ***** -->
        <div id="preloader">
            <div class="jumper">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>  
        <!-- ***** Preloader End ***** -->

        <div class="container-fluid" >
            <div class="row">
                <div class="col-md-2 bg-dark sidebar" style=" min-height:93vh; ">
                    <div class="logo text-center py-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" width="200"/>
                    </div>
                    <ul class="nav flex-column mt-5 ml-5">
                        <li class="menu-title mt-2">
                            <a href="javascript:void(0);" class="text-white">
                                <span>
                                    Ciudad Actual: 
                                    {{ !is_null(\Cookie::get('city')) ? 
                                    ucfirst(\Cookie::get('city')) :
                                    'Ninguna ciudad seleccionada. Por defecto: Barcelona' }}
                                </span>
                            </a>
                        </li>
                        <hr style="border-top:2px solid #fff; width:200px;">
                        <li class="menu-title mt-2"></li>

                        <li>
                            <a href="{{route('admin.citychanges')}}" class="text-white">
                                <i class="fa-solid fa-tree-city"></i>

                                <span>Cambiar de ciudad</span>
                            </a>
                        </li>

                        <li class="mt-3">
                            <a href="#fichas" class="text-white" data-toggle="collapse">
                                <i class="fas fa-user"></i>

                                <span>Fichas</span>
                            </a>
                            <div class="collapse" id="fichas">
                                <ul class="nav-second-level">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.create') }}" class="nav-link text-white"
                                            ><i class="fas fa-plus"></i>
                                            Crear</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.getPending') }}" class="nav-link text-white"
                                            ><i class="fas fa-list-alt"></i>
                                            Fichas pendientes</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.getActive') }}" class="nav-link text-white"
                                            ><i class="fas fa-list-check"></i>
                                            Fichas activas</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.getPositionals') }}" class="nav-link text-white"
                                            ><i class="fa-solid fa-up-down-left-right"></i>
                                            Posicionar Fichas Activas</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.getRequests') }}" class="nav-link text-white"
                                            ><i
                                                class="fas fa-file-contract"
                                            ></i>
                                            Peticiones</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.getLoginRecords') }}" class="nav-link text-white"
                                            ><i class="fas fa-history"></i>
                                            Login records</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu-title mt-2"></li>

                        <li class="mt-3">
                            <a href="#utilidades" class="text-white" data-toggle="collapse">
                                <i class="fa-solid fa-wrench"></i>

                                <span>Utilidades</span>
                            </a>
                            <div class="collapse" id="utilidades">
                                <ul class="nav-second-level">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.utilities.zones') }}" class="nav-link text-white"
                                            ><i
                                                class="fas fa-map-marker-alt"
                                            ></i>
                                            Ciudades y Zonas</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.utilities.tags') }}" class="nav-link text-white"
                                            ><i class="fas fa-tags"></i> Tags</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.utilities.packages') }}" class="nav-link text-white"
                                            ><i class="fas fa-archive"></i>
                                            Paquetes</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white"
                                            ><i class="fas fa-newspaper"></i>
                                            Noticias</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-title mt-2"></li>

                        <li class="mt-3">
                            <a href="#finanzas" class="text-white" data-toggle="collapse">
                                <i class="fa-solid fa-euro-sign"></i>

                                <span>Finanzas</span>
                            </a>
                            <div class="collapse" id="finanzas">
                                <ul class="nav-second-level">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white"
                                            ><i class="fas fa-chart-line"></i>
                                            Finanzas general</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white"
                                            ><i class="fas fa-calendar-alt"></i>
                                            Próximos vencimientos</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white"
                                            ><i class="fas fa-file-invoice"></i>
                                            Letras</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                      <a href="#" class="nav-link text-white">
                                        <i class="fas fa-file-invoice"></i> Fichas deudoras
                                      </a>
                                    </li>
                                    <li class="nav-item">
                                      <a href="#" class="nav-link text-white">
                                        <i class="fas fa-money-bill-alt"></i> Realizar ingreso
                                      </a>
                                    </li>
                                    <li class="nav-item">
                                      <a href="#" class="nav-link text-white">
                                        <i class="fas fa-clock"></i> Agregar Tiempo
                                      </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-title mt-2"></li>

                        <li class="mt-3">
                            <a href="#admins" class="text-white" data-toggle="collapse">
                                <i class="fa-solid fa-user-shield"></i>

                                <span>Admins</span>
                            </a>
                            <div class="collapse" id="admins">
                                <ul class="nav-second-level">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white"
                                            ><i class="fa-solid fa-user-plus"></i>
                                            Crear</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-md-10" style="min-height:93vh; height:auto; padding:25px; background: #1d1d1d;">
                    <nav class="navbar navbar-expand-sm navbar-light bg-light">
                        <button
                            type="button"
                            class="navbar-toggler"
                            data-toggle="collapse"
                            data-target="#navbarNav"
                        >
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-left">
                                <li class="nav-item search-container">
                                    <a href="#" class="nav-link search-toggle">
                                        <i class="fas fa-magnifying-glass search-icon"></i>
                                    </a>
                                    <input type="text" class="search-input d-none" placeholder="Buscar...">
                                </li>
                                <li class="nav-item city-menu">
                                    <span class="nav-link">Ciudades <i class="fas fa-chevron-down"></i></span>
                                    @php $cities = \App\Models\City::orderBy('name', 'asc')->get(); @endphp
                                    <ul class="city-dropdown">
                                        <li>Todas</li>
                                        @foreach($cities as $c)
                                        <li>{{$c->name}}</li>
                                        @endforeach
                                    </ul>
                                </li>

                                <form class="formsubmit d-none" method="POST" action="{{ route('admin.citychanges.apply') }}" autocomplete="off">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="selected_city" name="city" hidden required />
                                    </div>
                                </form>
                            </ul>
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" id="bell-icon">
                                        <i class="fas fa-bell"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" id="envelope-icon">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a
                                        href="#"
                                        class="nav-link dropdown-toggle"
                                        id="user-icon"
                                    >
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" id="bell-menu">
                                        <a href="#" class="dropdown-item">Sin Notificaciones</a>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right" id="envelope-menu">
                                        <a href="#" class="dropdown-item">Sin Mensajes</a>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right" id="user-menu">
                                        <a href="#" class="dropdown-item">Perfil</a>
                                        <a href="#" class="dropdown-item">Configuración</a>
                                        <a href="#" class="dropdown-item">Registro de actividad</a>
                                        <div class="dropdown-divider"></div>
                                        <form id="logout-form" action="{{ url('logout') }}" method="POST">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" 
                                               onclick="document.getElementById('logout-form').submit();"
                                               class="dropdown-item notify-item">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Cerrar sesión</span>
                                            </a>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                
                        <style>
                            .navbar-nav .nav-link,
                            .navbar-nav .nav-link i,
                            .navbar-nav .search-icon {
                                color: #000 !important;
                            }
                        
                            .search-container {
                                position: relative;
                                display: flex;
                                align-items: center;
                            }
                        
                            .search-input {
                                padding: 5px 10px;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                                margin-right: 10px;
                                width: 0;
                            }
                        
                            .search-toggle {
                                padding: 0.5rem 1rem;
                                display: flex;
                                align-items: center;
                            }
                        
                            .search-icon {
                                cursor: pointer;
                                font-size: 16px;
                            }
                        
                            .dropdown-menu a {
                                color: #000 !important;
                            }
                        
                            /* Estilos para el menú "Ciudades" */
                            .city-menu {
                                position: relative;
                                padding: 0.5rem 1rem;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                            }

                            .city-menu i {
                                margin-left: 5px;
                                transition: transform 0.3s ease;
                            }

                            .city-dropdown {
                                position: absolute;
                                top: 100%;
                                left: 0;
                                background: rgba(0, 0, 0, 0.6);
                                color: white;
                                padding: 10px;
                                width: auto;
                                border-radius: 4px;
                                z-index: 1000;
                                display: none; /* Oculto por defecto */
                                grid-template-columns: repeat(4, 1fr); /* 3 columnas */
                                gap: 10px;
                                white-space: nowrap;
                            }

                            .city-menu:hover .city-dropdown {
                                display: grid; /* Se muestra en grid */
                            }

                            .city-dropdown li {
                                padding: 5px;
                                cursor: pointer;
                            }

                            .city-dropdown li:hover {
                                background: rgba(255, 255, 255, 0.2);
                            }

                            .city-menu:hover i {
                                transform: rotate(180deg);
                            }

                            #bell-menu, #envelope-menu, #user-menu {
                                display: none;
                                position: absolute;
                                top: 30px; /* Ajusta la distancia del menú con respecto al ícono */
                                right: 0;  /* Alinea el menú al borde derecho del contenedor padre */
                                width: 150px; /* Ajusta el ancho del menú */
                                background-color: #fff;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                                font-size: 12px;
                                padding: 5px 0;
                            }

                            #bell-menu .dropdown-item, #envelope-menu .dropdown-item, #user-menu .dropdown-item {
                                padding: 8px 12px;
                                font-size: 12px;
                            }

                            #bell-menu .dropdown-item:hover, , #envelope-menu .dropdown-item:hover, , #user-menu .dropdown-item:hover {
                                background-color: #f0f0f0;
                            }



                        </style>
                
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                
                        <script>
                            $(document).ready(function() {
                                const $container = $('.search-container');
                                const $input = $('.search-input');
                                const $toggle = $('.search-toggle');
                        
                                $input.addClass('d-none');
                        
                                $toggle.on('click', (e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                        
                                    if ($input.hasClass('d-none')) {
                                        $input.removeClass('d-none')
                                            .css('opacity', 0)
                                            .animate({
                                                width: '200px',
                                                opacity: 1
                                            }, 300, () => {
                                                $input.focus();
                                            });
                                    }
                                });
                        
                                $input.on('keydown', (e) => {
                                    if (e.key === 'Escape') {
                                        cerrarBusqueda();
                                    } else if (e.key === 'Enter' && $input.val().trim() !== '') {
                                        console.log('Buscando:', $input.val());
                                    }
                                });
                        
                                $input.on('click', (e) => {
                                    e.stopPropagation();
                                });
                        
                                $(document).on('click', () => {
                                    if ($input.val().trim() === '') {
                                        cerrarBusqueda();
                                    }
                                });
                        
                                const cerrarBusqueda = () => {
                                    if (!$input.hasClass('d-none')) {
                                        $input.animate({
                                            width: '0',
                                            opacity: 0
                                        }, 300, () => {
                                            $input.val('').addClass('d-none');
                                        });
                                    }
                                };

                                $('.city-dropdown li').on('click', function() {
                                    const cityName = $(this).text().trim().toLowerCase();
                                    $('.selected_city').val(cityName);
                                    $('.formsubmit').submit();
                                });

                                function toggleMenu(icon, menu) {
                                    $(icon).click(function (e) {
                                        e.stopPropagation();
                                        $(menu).toggle();
                                    });

                                    $(document).click(function (e) {
                                        if (!$(e.target).closest(icon + ', ' + menu).length) {
                                            $(menu).hide();
                                        }
                                    });
                                }

                                toggleMenu('#bell-icon', '#bell-menu');
                                toggleMenu('#envelope-icon', '#envelope-menu');
                                toggleMenu('#user-icon', '#user-menu');
                            });
                        </script>
                        
                        
                    </nav>
                
                    @include('partial_msg')
                
                    <div class="container mt-3" style="max-width: 100% !important;">@yield('content')</div>
                </div>
            </div>

            <footer class="footer mt-auto py-3 bg-dark text-light" style="width:101%; margin-left: -13px;">
                <div class="text-center">Copyright © Hotspania</div>
            </footer>
        </div>

        <!-- jQuery -->
        <script src="{{ asset('js/jquery-2.1.0.min.js') }}"></script>
        <script>
            $(document).ready(function(){
                $('.parentmodal a[href="#"]').click(function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    const parentModal = $(this).parent().parent().parent().parent().parent().parent().attr('id');
                    const modalId  = $(this).attr('data');
                    $('.parentmodal').css('display', 'none'); // Close the parent modal
                    $('.modal-backdrop').css('display', 'none') // Close the parent modal
                    $(`#${modalId}`).modal('toggle'); // Open the corresponding modal
                });

                $('.close').click(function() {
                    $(this).closest('.modal').modal('hide');
                });
            });
        </script>

        <script>
            window.addEventListener('scroll', function() {
                localStorage.setItem('scrollPosition', window.scrollY);
            });

            window.addEventListener('load', function() {
                const scrollPosition = localStorage.getItem('scrollPosition');
                if (scrollPosition) {
                    window.scrollTo(0, parseInt(scrollPosition));
                }
            });
        </script>

        <!-- FontAwesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

        <!-- Bootstrap -->
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js"></script>

        <!-- Plugins -->
        <script src="{{ asset('js/owl-carousel.js') }}"></script>
        <script src="{{ asset('js/accordions.js') }}"></script>
        <script src="{{ asset('js/datepicker.js') }}"></script>
        <script src="{{ asset('js/scrollreveal.min.js') }}"></script>
        <script src="{{ asset('js/waypoints.min.js') }}"></script>
        <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('js/imgfix.min.js') }}"></script>
        <script src="{{ asset('js/slick.js') }}"></script>
        <script src="{{ asset('js/lightbox.js') }}"></script>
        <script src="{{ asset('js/isotope.js') }}"></script>

        <!-- Global Init -->
        <script src="{{ asset('js/custom.js') }}"></script>
    </body>
</html>
