<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Diavbla">
    <meta name="author" content="jagcweb">

    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon.png') }}">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'DiavlaHookah') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/templatemo-hexashop.css') }}">

    <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}">

    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

    
</head>
<body>
    @include('modals.modal-login')
    @include('modals.modal-register')
    @include('partial_msg')
    <div id="app">
        <!-- ***** Preloader Start ***** -->
        <div id="preloader">
            <div class="jumper">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>  
        <!-- ***** Preloader End ***** -->

        <!-- ***** Header Start ***** -->
        <header class="header-area header-sticky">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="main-nav">
                            <!-- ***** Logo Start ***** -->
                            <a href="{{route('home')}}" class="logo">
                                <img src="{{ asset('images/logo.png') }}" width="150">
                            </a>
                            <!-- ***** Logo End ***** -->
                            <!-- ***** Menu Start ***** -->
                            <ul class="nav">
                                <li class="scroll-to-section"><a href="#top" class="active">Inicio</a></li>

                                @if(!\Auth::user())
                                    <li class="scroll-to-section"><a style="font-size: 20px;" href="#" data-toggle="modal" data-target="#login"><i class="fa-regular fa-circle-user"></i></a></li>
                                @else
                                    <li class="submenu">
                                        <a style="font-size: 20px;" href="javascript:;"><i class="fa-regular fa-circle-user"></i></a>
                                        <ul>
                                            @if(\Auth::user()->getRoleNames()[0] == "admin")
                                            <li><a href="{{route('admin')}}">Administración</a></li>
                                            @endif
                                            <li><a href="{{route('account.index')}}">Mi cuenta</a></li>
                                            <li><a href="{{route('order.index')}}">Mis pedidos</a></li>
                                            
                                            <li style="border-top: 1px solid #eee;"><a href="{{route('logout')}}">Cerrar sesión</a></li>
                                        </ul>
                                    </li>
                                @endif

                                @if(\Auth::user())
                                    @php $cart_products = 0; $cart_products = \App\Models\Cart::where('user_id', \Auth::user()->id)->count(); @endphp
                                    <li class="scroll-to-section">
                                        <a style="position:relative; font-size: 20px;" href="{{route('cart.index')}}">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span style="
                                                display: flex;
                                                justify-content:center;
                                                align-items:center;
                                                position: absolute;
                                                top: -5px;
                                                right:1.5px;
                                                color:#fff;
                                                background:#f86262;
                                                height: 20px;
                                                width: 20px;
                                                line-height:15px;
                                                text-align:center;
                                                border-radius:99999px;
                                                @if($cart_products>9)
                                                font-size:13px;
                                                @else
                                                font-size:14px;
                                                @endif
                                                ">
                                                <span style="margin-left: 1px;">
                                                @if($cart_products>9) 9+ @else {{$cart_products}} @endif
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            </ul>        
                            <a class='menu-trigger'>
                                <span>Menu</span>
                            </a>
                            <!-- ***** Menu End ***** -->
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- ***** Header End ***** -->

            <main class="py-4">
                @yield('content')
            </main>

        <!-- ***** Footer Start ***** -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="first-item">
                            <div class="logo">
                                <img src="{{ asset('images/white-logo.png') }}" alt="Diavla Hookah logo" width="130" />
                            </div>
                            <ul>
                                <li><a href="mailto:diavlahookahspain@gmail.com">diavlahookahspain@gmail.com</a></li>
                                <li><a href="callto:+34689759849">+34 689759849</a></li>
                            </ul>
                        </div>
                    </div>
                    {{--<div class="col-lg-3">
                        <h4>Categorías</h4>
                        @php $categories = \App\Models\Category::orderBy('name', 'asc')->take(4)->get(); @endphp
                        <ul>
                            @if(count($categories)>0)
                                @foreach ($categories as $cat)
                                    <li><a href="#">{{$cat->name}}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h4>Enlaces útiles</h4>
                        <ul>
                            <li><a href="#">Inicio</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Help</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>--}}
                    
                    <div class="col-lg-4">
                        <h4 class="text-center">Categorías</h4>
                        <ul class="text-center">
                            @foreach ($categories as $cat)
                                <li><a href="{{ route('categories.get', ['name' => $cat->name]) }}">{{ ucfirst($cat->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="col-lg-4 text-right">
                        <h4 class="text-center">Redes sociales</h4>
                        <ul class="text-center">
                            <li><a style="font-size: 28px;" target="_blank" href="https://www.instagram.com/diavlahookah/"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a style="font-size: 28px;" target="_blank" href="https://www.tiktok.com/@diavlahookah"><i class="fa-brands fa-tiktok"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-12">
                        <div class="under-footer">
                            <p>©<?php echo date("Y"); ?> | Diavla Hookah SL. Todos los derechos reservados. 
                            
                            <br>Desarrollado por: <a href="https://jagcweb.es/" target="_blank" title="Jagcweb.es">Jagcweb</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ***** Footer End ***** -->
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-2.1.0.min.js') }}"></script>

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

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

    <script>

        $(function() {
            var selectedClass = "";
            $("p").click(function(){
            selectedClass = $(this).attr("data-rel");
            $("#portfolio").fadeTo(50, 0.1);
                $("#portfolio div").not("."+selectedClass).fadeOut();
            setTimeout(function() {
                $("."+selectedClass).fadeIn();
                $("#portfolio").fadeTo(50, 1);
            }, 500);
                
            });
        });

    </script>

    <script>
        $( document ).ready(function() {
            $(".btn-input2").click(function(){
                $("#login").modal('toggle');
                $("#register").modal('toggle');
            });
        });
    </script>
</body>
</html>
