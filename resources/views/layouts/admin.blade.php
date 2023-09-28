<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Diavbla">
  <meta name="author" content="jagcweb">
  <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
    rel="stylesheet">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'DiavlaHookah') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">

  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}">

  <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

  <link rel="stylesheet" href="{{ asset('css/templatemo-hexashop.css') }}">

</head>

<body style="background:#1d1d1d;">
  <div class="loader-container">
    <div class="loader lds-ripple">
      <div></div>
      <div></div>
    </div>
  </div>

  @yield('content')


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
</body>

</html>