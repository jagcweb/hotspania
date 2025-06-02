@extends('layouts.app')

@section('title') Iniciar sesión @endsection

@section('content')
<div class="container-fluid">
    <div class="row text-center vh-100">
        <div class="col-xl-6 col-sm-12 textura">
            <div class="d-flex flex-column align-items-center align-content-center vh-100 justify-content-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" width="200"/>
          <div>
            <h4 class="text-white">Inicia sesión o regístrate - Nunca compartiremos tu información</h4>
          </div>
          <div>
            <form class="form" method="POST" autocomplete="off">
                @csrf
              <div class="form-group mt-4">
                <span style="color:#111;">Email</span>
                <input class="form-field" type="email" placeholder="usuario14@gmail.com" name="email" required>
              </div>
              <div class="form-group mt-4">
                    <span style="color:#111;">Contraseña</span>
                    <input class="form-field" type="password" placeholder="***********" name="password" required>
              </div>
              <small class="form-text" style="color:#fff;"></small>
              <br>
              <button type="submit" class="btn boton_login">Iniciar sesión</button>
            </form>
            
          </div>
          <div class="mt-3">
            <small class="form-text mt-4" style="color:#fff;">¿Olvidaste tu usuario y contraseña?</small>
          </div>
        </div>
      </div>
      <div class="col-xl-6 col-sm-12 fondo">
        <div class="d-flex flex-column align-items-center align-content-center vh-100 justify-content-center">
          <div class="mb-3">
            <h4 class="text-white">¿Aún no eres anunciante?</h4>
          </div>
          <div>
            <p class="text-white">¿A qué esperas? Sé parte del sitio líder de masajistas en España</p>
          </div>
          <div class="d-flex justify-content-center">
          </div>
          <div class="mb-3">
            <h4 class="text-white">Todo esto ¡Y mucho más!</h4>
          </div>
          <div>
            <button class="btn boton_registro mt-4" id="registerButton">
                Regístrate gratis
            </button>
            <hr>
            @php $cities = \App\Models\City::orderBy('id', 'asc')->get(); @endphp
            <select class="form-control" id="city_id" name="city" style="border: 2px solid #f76e08;" required autocomplete="off">
                <option hidden selected disabled>
                    Escoge una ciudad...
                </option>
                @foreach($cities as $c)
                    <option value="{{strtolower($c->name)}}">{{$c->name}}</option>
                @endforeach
            </select>
            <button class="btn acceder mt-4" id="log" disabled>
                Acceder
            </button>
          </div>
        </div>
      </div>
  
  
    </div>
</div>

    <div class="modal fade" id="imageGuidelinesModal" tabindex="-1" role="dialog" aria-labelledby="imageGuidelinesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageGuidelinesModalLabel">Sitio para mayores de 18 años.</h5>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ageCheck" checked style="accent-color: #f44806;">
                        <label class="form-check-label" for="ageCheck" style="letter-spacing: normal; text-transform: none;">
                            Soy mayor de 18 años y acepto los términos y condiciones.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="acceptTerms()" style="background:#f44806; width:100%; color:#fff;">Acceder</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!getCookie('terms')) {
            const modalElement = document.getElementById('imageGuidelinesModal');
            const bootstrapModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });

            // Guardar la instancia como propiedad del elemento para reutilizar
            modalElement._bootstrapModal = bootstrapModal;
            bootstrapModal.show();
        }
    });

    // ✅ Botón que cierra el modal y guarda la cookie
    function acceptTerms() {
        const expirationDate = new Date();
        expirationDate.setFullYear(expirationDate.getFullYear() + 1);
        document.cookie = `terms=accepted; expires=${expirationDate.toUTCString()}; path=/`;

        const modalElement = document.getElementById('imageGuidelinesModal');

        // Usamos la instancia previamente almacenada o creamos una nueva
        let modalInstance = bootstrap.Modal.getInstance?.(modalElement);

        // Si getInstance no está disponible, usa la instancia guardada manualmente
        if (!modalInstance && modalElement._bootstrapModal) {
            modalInstance = modalElement._bootstrapModal;
        } else if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }

        modalInstance.hide();
    }

    // ✅ Función para leer cookies sin jQuery
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    </script>


<style>

    .container-fluid {
        min-height: 100vh; /* Asegura que el contenedor ocupe al menos la altura completa de la ventana */
        display: flex;
        flex-direction: column;
    }

    .form {
        width:500px;
    }
    :root {

        --input-color: #f1f1f1;
        --input-border: #CDD9ED;
        --input-background: #fff;
        --input-placeholder: #131313;

        --input-border-focus: #F76E08;

        --group-color: var(--input-color);
        --group-border: var(--input-border);
        --group-background: #EEF4FF;

        --group-color-focus: #fff;
        --group-border-focus: var(--input-border-focus);
        --group-background-focus: #F76E08;

    }
    .form-field {
        display: block;
        width: 100%;
        padding: 8px 16px;
        line-height: 25px;
        font-size: 14px;
        font-weight: 500;
        font-family: inherit;
        border-radius: 6px;
        -webkit-appearance: none;
        color: var(--input-color);
        border: 1px solid var(--input-border);
        background: var(--input-background);
        transition: border 0.3s ease;
    }
    .form-field::placeholder {
        color: var(--input-placeholder);
    }
    .form-field:focus {
        outline: none;
        border-color: var(--input-border-focus);
    }
 
 
    .form-group {
	    position: relative;
        display: flex;
        width: 100%;
    }
    .form-group > span, .form-group .form-field {
        white-space: nowrap;
        display: block;
    }
    .form-group > span:not(:first-child):not(:last-child), .form-group .form-field:not(:first-child):not(:last-child) {
        border-radius: 0;
    }
    .form-group > span:first-child, .form-group .form-field:first-child {
        border-radius: 6px 0 0 6px;
    }
    .form-group > span:last-child, .form-group .form-field:last-child {
        border-radius: 0 6px 6px 0;
    }
    .form-group > span:not(:first-child), .form-group .form-field:not(:first-child) {
        margin-left: -1px;
    }
    .form-group .form-field {
        position: relative;
        z-index: 1;
        flex: 1 1 auto;
        width: 1%;
        margin-top: 0;
        margin-bottom: 0;
    }
    .form-group > span {
        text-align: center;
        padding: 8px 12px;
        font-size: 14px;
        line-height: 25px;
        color: var(--group-color);
        background: var(--group-background);
        border: 1px solid var(--group-border);
        transition: background 0.3s ease, border 0.3s ease, color 0.3s ease;
        min-width: 110px;
    }

    .form-group:focus-within > span {
        color: var(--group-color-focus);
        background: var(--group-background-focus);
        border-color: var(--group-border-focus);
    }

    .textura{
    background: #131313;
    }
    .fondo{
        background-color: #252525;
    }
    .textura, .fondo {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Asegura que el contenido y el footer se distribuyan adecuadamente */
    }
    .img{
        height: auto;
        width: 400px;
    }
    .img-2{
        height: 8vh;
        width: auto;
    }
    .span{
        font-size: 8px;
    }
    .div{
        display: flex;
        flex-direction: column;
    }
    .div-3{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center;
        height: 100vh;
    }
    .div-2{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center; 
    }

    .box{
        // margin-top: 10vh;    
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        // margin-bottom: 15vh;  
    }
    .box-card{
        max-width: 450px;
        width: 350px;
        background-color: var(--ion-color-primary);
        border-radius: 10px;
    }
    .h5{
        font-size: 15px;
    font-weight: bold;
    }
    .span-2{
        font-size: 12px;
    }
    ion-item{
        --background:transparent;
    }

    .form-control {
        display: block;
        width: 100%;
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #ffff;
        background-color: #212529;
        background-clip: padding-box;
        border: 1px solid #212529;
        border-radius: .25rem;
    }

    .boton_login, .boton_registro, .acceder  {
        width: 500px;
        height: 50px!important;
        background: none;
        border: 2px solid #fff;
        font-size: 15px;
        color: #f1f1f1;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
        border-radius: 12px;
        background-color: transparent;
        font-weight: bolder;
        box-shadow: 0 2px 0 2px #000;
    }

    .boton_login:before {
        content: "";
        position: absolute;
        width: 100px;
        height: 120%;
        background-color: #F76E08;
        top: 50%;
        transform: skewX(30deg) translate(-190%, -50%);
        transition: all 0.5s;
    }

    .boton_registro:before {
        content: "";
        position: absolute;
        width: 100px;
        height: 120%;
        background-color: #F76E08;
        top: 50%;
        transform: skewX(30deg) translate(-178%, -50%);
        transition: all 0.5s;
    }

    .acceder:before {
        content: "";
        position: absolute;
        width: 100px;
        height: 120%;
        background-color: #F76E08;
        top: 50%;
        transform: skewX(30deg) translate(-210%, -50%);
        transition: all 0.5s;
    }

    .boton_login:hover, .boton_registro:hover, .acceder:hover{
    background-color: #f1f1f1;
    color: #131313;
    box-shadow: 0 2px 0 2px #F76E08;
    }

    .boton_login:hover::before {
    transform: skewX(30deg) translate(228%, -50%);
    transition-delay: 0.1s;
    }

    .boton_registro:hover::before {
    transform: skewX(30deg) translate(240%, -50%);
    transition-delay: 0.1s;
    }

    .acceder:hover::before {
        transform: skewX(30deg) translate(210%, -50%);
        transition-delay: 0.1s;
    }

    .boton_login:active, .boton_registro:active, .acceder:active {
    transform: scale(0.9);
    }

    .acceder:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        border-color: #666;
    }

    .acceder:disabled:hover {
        background-color: transparent;
        color: #f1f1f1;
        box-shadow: 0 2px 0 2px #000;
    }

    .acceder:disabled:before {
        display: none;
    }

    li{
        list-style: none;
    }

    .col2{
        border-left: 1px solid white;
    }

    @media (max-width: 576px) {
        .form{
            width: 90%!important;
        }
        .img{
            width: 80vw;
        }
        .col1{
            margin-top: 3rem;
        }
        .col2{
            margin-top: 5rem;
            padding-bottom: 5rem;
            padding-top: 5rem;
            border-left: none;
            background-color: rgb(255, 0, 0);
        }
    }

    @media (max-width: 576px) {
        .boton_login, .boton_registro, .acceder  {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .form {
            width: 90%!important;
        }

        .boton_login, .boton_registro, .acceder {
            width: 100%; /* Ajustar el tamaño de los botones para dispositivos móviles */
        }

        .col-xl-6 {
            /* Asegura que las columnas tomen todo el ancho en dispositivos móviles */
            width: 100%;
        }
    }
</style>

<script>
    // Función para obtener el valor de una cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Al cargar la página, verificar si existe la cookie
    document.addEventListener('DOMContentLoaded', function() {
        const savedCity = getCookie('selected_city');
        if (savedCity) {
            const citySelect = document.getElementById('city_id');
            citySelect.value = savedCity;
            document.getElementById('log').disabled = false;
        }
    });

    document.getElementById('city_id').addEventListener('change', function() {
        const citySelect = document.getElementById('city_id');
        const selectedCity = citySelect.value;
        document.getElementById('log').disabled = !selectedCity;
        
        // Guardar cookie con el nombre de la ciudad (expira en 30 días)
        const expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + 30);
        document.cookie = `selected_city=${selectedCity}; expires=${expirationDate.toUTCString()}; path=/`;
    });

    document.getElementById('registerButton').addEventListener('click', function() {
        window.location.href = '/register/paso-1';
    });

    document.getElementById('log').addEventListener('click', function() {
        window.location.href = '/home';
    });
</script>
  
  <script>
    const inputFields = document.querySelectorAll('.form-field');

    inputFields.forEach(field => {
        field.addEventListener('input', () => {
            const spanElement = field.parentElement.querySelector('span');
            if (field.value !== '') {
                spanElement.style.color = 'var(--group-color-focus)';
                spanElement.style.background = 'var(--group-background-focus)';
                spanElement.style.borderColor = 'var(--group-border-focus)';
            } else {
                spanElement.removeAttribute('style');
            }
        });
    });
</script>

@endsection
