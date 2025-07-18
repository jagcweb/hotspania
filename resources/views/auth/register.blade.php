@extends('layouts.app')

@section('title') Registrarse @endsection
@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="main-div">
    <div class="form-div">
        <div class="d-flex flex-column align-items-center align-content-center justify-content-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" width="200"/>
        </div>
        <div class="mt-4" id="multi-step-form-container">
            <!-- Form Steps / Progress Bar -->
            <ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
                <!-- Step 1 -->
                <li class="form-stepper-active text-center form-stepper-list step-1" step="1">
                    <a class="mx-2">
                        <span class="form-stepper-circle step-2-circle" style="color: #fff;  @if(str_contains(url()->full(), 'paso-2') || str_contains(url()->full(), 'paso-3')) background:green!important;" @endif ">
                            <span>1</span>
                        </span>
                        <div class="label" @if(str_contains(url()->full(), 'paso-2') || str_contains(url()->full(), 'paso-3')) style="color:green!important;" @endif>Información</div>
                    </a>
                </li>
                <!-- Step 2 -->
                <li class="form-stepper-unfinished text-center form-stepper-list step-2" step="2">
                    <a class="mx-2">
                        <span class="form-stepper-circle step-2-circle" style="color: #fff; @if(str_contains(url()->full(), 'paso-2')) background:#F44806!important; @endif @if(str_contains(url()->full(), 'paso-3')) background:green!important; @endif">
                            <span>2</span>
                        </span>
                        <div class="label @if(str_contains(url()->full(), 'paso-1')) text-muted @endif" @if(str_contains(url()->full(), 'paso-2')) style="color:#F44806!important;" @endif @if(str_contains(url()->full(), 'paso-3')) style="color:green!important;" @endif>Contenido</div>
                    </a>
                </li>
                <!-- Step 3 -->
                <li class="form-stepper-unfinished text-center form-stepper-list step-3" step="3">
                    <a class="mx-2">
                        <span class="form-stepper-circle step-2-circle" style="color: #fff; @if(str_contains(url()->full(), 'paso-3')) background:#F44806!important; @endif">
                            <span>3</span>
                        </span>
                        <div class="label @if(str_contains(url()->full(), 'paso-1') || str_contains(url()->full(), 'paso-2')) text-muted @endif" @if(str_contains(url()->full(), 'paso-3')) style="color:#F44806!important;" @endif>Datos</div>
                    </a>
                </li>
            </ul>
            @if($step == 1)
                <form method="POST" action="{{ route('user.save', ['step' => 'step-1', 'id' => NULL]) }}" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    
                    <section id="step-1" class="form-step">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nickname">Nombre Ficticio</label>
                                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="age">Edad</label>
                                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" min="18" max="99" required>
                                        </div>
                                        
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="whatsapp_number">Nº teléfono WhatsApp</label>
                                                    <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp" value="{{ old('whatsapp') }}" required>
                                                </div>
                                            </div>
                                                
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="phone">Nº teléfono Llamadas</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                                                                
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <label for="weight">Peso (kg)</label>
                                                <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" required>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <label for="height">Altura (cm)</label>
                                                <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}" required>
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-4 form-group">
                                                <label for="bust">Busto (cm)</label>
                                                <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust') }}" >
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-4 form-group">
                                                <label for="waist">Cintura (cm)</label>
                                                <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist') }}" >
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-4 form-group">
                                                <label for="hip">Cadera (cm)</label>
                                                <input type="number" class="form-control" id="hip" name="hip" value="{{ old('hip') }}" >
                                            </div>
                                        </div>
                                        
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="smoker">¿Fumas?</label>
                                                    <select class="form-control" id="smoker" name="smoker" required>
                                                        <option class="option" value="0" {{ old('smoker') == 0 ? 'selected' : '' }}>No</option>
                                                        <option class="option" value="1" {{ old('smoker') == 1 ? 'selected' : '' }}>Sí</option>
                                                    </select>
                                                </div>    
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="gender">Género</label>
                                                    <select class="form-control" id="gender" name="gender" required>
                                                        <option class="option" value="mujer" {{ old('gender') == 'mujer' ? 'selected' : '' }}>Mujer</option>
                                                        <option class="option" value="hombre" {{ old('gender') == 'hombre' ? 'selected' : '' }}>Hombre</option>
                                                        <option class="option" value="lgbti" {{ old('gender') == 'lgbti' ? 'selected' : '' }}>LGTBI+</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>   
                                        
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="start_day">Dia de Inicio</label>
                                                    <select class="form-control" id="start_day" name="start_day" required>
                                                        <option class="option" value="lunes" {{ old('start_day') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                                        <option class="option" value="martes" {{ old('start_day') == 'martes' ? 'selected' : '' }}>Martes</option>
                                                        <option class="option" value="miercoles" {{ old('start_day') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                                        <option class="option" value="jueves" {{ old('start_day') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                                        <option class="option" value="viernes" {{ old('start_day') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                                        <option class="option" value="sabado" {{ old('start_day') == 'sabado' ? 'selected' : '' }}>Sabado</option>
                                                        <option class="option" value="domingo" {{ old('start_day') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="end_day">Dia de Fin</label>
                                                    <select class="form-control" id="end_day" name="end_day" required>
                                                        <option class="option" value="lunes" {{ old('end_day') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                                        <option class="option" value="martes" {{ old('end_day') == 'martes' ? 'selected' : '' }}>Martes</option>
                                                        <option class="option" value="miercoles" {{ old('end_day') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                                        <option class="option" value="jueves" {{ old('end_day') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                                        <option class="option" value="viernes" {{ old('end_day') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                                        <option class="option" value="sabado" {{ old('end_day') == 'sabado' ? 'selected' : '' }}>Sabado</option>
                                                        <option class="option" value="domingo" {{ old('end_day') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="fulltime" name="fulltime_time" value="1" {{ old('fulltime_time') ? 'checked' : '' }}/>
                                            <label class="form-check-label" for="fulltime">
                                                ¿Horario 24h? (fulltime)
                                            </label>  
                                        </div>
                                        
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="start_time_div">
                                                    <label for="start_time">Hora de Inicio</label>
                                                    <select class="form-control" id="start_time" name="start_time">
                                                        <option class="option" disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora inicio</option>
                                                        @for ($i = 0; $i <= 23; $i++)
                                                            <option class="option" value="{{ $i }}" {{ old('start_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="end_time_div">
                                                    <label for="end_time">Hora de Fin</label>
                                                    <select class="form-control" id="end_time" name="end_time">
                                                        <option class="option" disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora fin</option>
                                                        @for ($i = 0; $i <= 23; $i++)
                                                            <option class="option" value="{{ $i }}" {{ old('end_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="cities">Ciudades</label>
                                                    <select id="ciudades" name="city[]" multiple required>
                                                        @foreach ($cities->sortBy('name') as $c)
                                                            <option value="{{ $c->id }}" {{ (is_array(old('city')) && in_array($c->id, old('city'))) ? 'selected' : '' }}>
                                                                {{ $c->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-6 form-group">
                                                <div class="form-group">
                                                    <label for="working_zone">Zona</label>
                                                    <input type="text" class="form-control" id="working_zone" name="working_zone" value="{{ old('working_zone') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="form-group">
                                            <label for="service_location">Donde atendera?</label>
                                            <div style="background:transparent; border:2px solid #aaa; padding:20px; min-height:80px;">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="piso_propio" name="service_location[]" value="piso_propio"
                                                        {{ (is_array(old('service_location')) && in_array('piso_propio', old('service_location'))) ? 'checked' : '' }}/>
                                                    <label class="form-check-label" for="piso_propio">
                                                        Piso Propio
                                                    </label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="domicilio" name="service_location[]" value="domicilio"
                                                        {{ (is_array(old('service_location')) && in_array('domicilio', old('service_location'))) ? 'checked' : '' }}/>
                                                    <label class="form-check-label" for="domicilio">
                                                        Domicilio
                                                    </label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" id="hotel" name="service_location[]" value="hotel"
                                                        {{ (is_array(old('service_location')) && in_array('hotel', old('service_location'))) ? 'checked' : '' }}/>
                                                    <label class="form-check-label" for="hotel">
                                                        Hoteles
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="link">Enlace</label>
                                            <input type="url" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com" pattern="https://.*" size="80" />
                                        </div>

                                        <button class="btnstep1 disabled button btn-navigate-form-step submit_btn" disabled type="submit" step_number="2" data="step-1">Siguiente</button>           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                </form>
            @endif

            @if($step == 2)
                <!-- Modal -->
                <div class="modal fade" id="imageGuidelinesModal" tabindex="-1" role="dialog" aria-labelledby="imageGuidelinesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                   <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageGuidelinesModalLabel">Directrices para las Imágenes</h5>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li><span style="color:#f44806;">-</span> Imágenes con buena calidad y nitidez.</li>
                                    <li><span style="color:#f44806;">-</span> Sin marca de agua, logos, o enlaces.</li>
                                    <li><span style="color:#f44806;">-</span> Fotos con ROSTRO. Si lo quieres ocultar, haz click en "ocultar Rostro".</li>
                                    <li><span style="color:#f44806;">-</span> Retoques mal hechos o sospechosos son motivo de rechazo.</li>
                                    <li><span style="color:#f44806;">-</span> En todas las imágenes tenemos que poder reconocerte.</li>
                                </ul>
                                <br>
                                <p class="font-weight-bold text-danger text-center">NO subiremos fotos falsas. Utiliza fotos tuyas.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal" style="background:#f44806; width:100%; color:#fff;">¡De acuerdo!</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalElement = document.getElementById('imageGuidelinesModal');
                        const bootstrapModal = new bootstrap.Modal(modalElement, {
                            backdrop: 'static',
                            keyboard: false
                        });

                        // Mostrar modal
                        bootstrapModal.show();

                        // Botón que cierra el modal
                        document.getElementById('acceptModalBtn').addEventListener('click', function () {
                            bootstrapModal.hide();
                        });
                    });
                </script>

                <form id="upload-form" method="POST" action="{{ route('user.save', ['step' => 'step-2', 'id' => \Crypt::encryptString($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    <section id="step-2" class="form-step">
                        <h2 class="font-normal" style="margin:0px; padding:0px;">Sube tus imágenes y vídeos</h2>
                        <div class="form-group">
                            <label for="files">Archivos</label>
                            <input type="file" name="files[]" class="step2input form-control mt-1 image_upload" 
                                    id="files" accept=".png,.jpeg,.jpg,.webp,.gif,.bmp,.avi,.mp4,.mpg,.mov,.3gp,.wmv,.flv" multiple required>
                            <small style="color:#fff;">Máximo 8 archivos y 10mb por cada uno.</small>
                        </div>
                        <div class="form-check mt-2 mb-2">
                            <input class="form-check-input" type="checkbox" id="hide_face" name="hide_face" value="1">
                            <label class="form-check-label" for="hide_face">
                                Ocultar rostro
                            </label>
                        </div>
                        <button id="upload-btn" class="w-100 btnstep2 disabled button btn-navigate-form-step submit_btn" disabled type="submit" step_number="3" data="step-1" onclick="startLoading()">Siguiente</button>
                    </section>
                </form>
            @endif
            
            

            @if($step == 3)
                <form id="last-form" method="POST" action="{{ route('user.save', ['step' => 'step-3', 'id' => \Crypt::encryptString($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    <section id="step-3" class="form-step">
                        <h2 class="font-normal">Datos Personales</h2>
                        <div class="form-group">
                            <label for="full_name">Nombre y apellidos</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                            onkeydown="showAlert(event)" onpaste="showAlert(event)"
                            >
                        </div>
                                    

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="dni">Documento (NIE, DNI, PASAPORTE...)</label>
                            <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dni_file">Foto del documento</label>
                            <input type="file" class="form-control-file" id="dni_file" name="dni_file" required accept=".jpeg,.png,.jpg,.gif,.webp">
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required autocomplete="off">
                                <label class="form-check-label" for="terms">
                                    Acepto los términos y condiciones
                                </label>
                            </div>
                        </div>
                        <button id="last-submit" class="w-100 btnstep3 disabled button submit_btn submit_btn_finish submit-btn" type="submit" disabled data="step-3">Guardar</button>
                    </section>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/pica@7.0.0/dist/pica.min.js"></script>
<script src="https://unpkg.com/@ffmpeg/ffmpeg@0.7.0/dist/ffmpeg.min.js"></script>

<script>
    const lastSubmitBtn = document.getElementById('last-submit');
    if (lastSubmitBtn) {
        lastSubmitBtn.addEventListener('click', function (e) {
            // Obtener el user_id encriptado desde Blade
            const userId = "{{ isset($user) ? $user->id : '' }}";
            const cookieName = "ficheros_guardados_" + userId;
            // Eliminar la cookie (establecer expiración en el pasado)
            document.cookie = cookieName + "=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
            // Enviar el formulario
            const lastForm = document.getElementById('last-form');
            if (lastForm) {
                lastForm.submit();
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Helper to create or update validation message
        function setValidationMessage(input, message, isError) {
            let small = input.parentNode.querySelector('small.validation-msg');
            if (!small) {
                small = document.createElement('small');
                small.className = 'validation-msg';
                input.parentNode.appendChild(small);
            }
            small.textContent = message;
            small.style.color = isError ? '#ff4d4f' : '#13da35';
            small.style.display = message ? 'block' : 'none';
        }

        // AJAX check function
        async function checkUsernameOrEmail(type, value) {
            if (!value) {
                setValidationMessage(document.getElementById(type), '', false);
                return;
            }
            try {
                const params = new URLSearchParams();
                params.append(type, value);
                const response = await fetch(`/register/check-username-or-email?${params.toString()}`);
                const data = await response.json();
                if (type === 'nickname') {
                    if (data.nickname_exists) {
                        setValidationMessage(document.getElementById('nickname'), 'Este nombre ya está en uso.', true);
                    } else {
                        setValidationMessage(document.getElementById('nickname'), 'Nombre disponible.', false);
                    }
                }
                if (type === 'email') {
                    if (data.email_exists) {
                        setValidationMessage(document.getElementById('email'), 'Este email ya está registrado.', true);
                    } else {
                        setValidationMessage(document.getElementById('email'), 'Email disponible.', false);
                    }
                }
            } catch (e) {
                // Optionally handle error
            }
        }

        // Nickname AJAX validation
        const nicknameInput = document.getElementById('nickname');
        if (nicknameInput) {
            nicknameInput.addEventListener('blur', function () {
                checkUsernameOrEmail('nickname', nicknameInput.value.trim());
            });
        }

        // Email AJAX validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                checkUsernameOrEmail('email', emailInput.value.trim());
            });
        }
    });
</script>

<script>
    function showAlert(event) {
        event.preventDefault();
        alert("Por favor, seleccione la fecha en el calendario.");
    }

    document.addEventListener('DOMContentLoaded', () => {
        const linkInput = document.getElementById('link');
        
        if (linkInput) {
            // Add focus event listener
            linkInput.addEventListener('focus', () => {
                if (!linkInput.value) {
                    linkInput.value = 'https://';
                }
            });

            // Add blur event listener 
            linkInput.addEventListener('blur', () => {
                if (linkInput.value === 'https://') {
                    linkInput.value = '';
                }
            });

            // Helper function to validate URLs
            function isValidURL(str) {
                try {
                    new URL(str);
                    return true;
                } catch (_) {
                    return false;
                }
            }
        }
    });
</script>

<script>
    heightInput = document.getElementById('height');
    weightInput = document.getElementById('weight');
    bustInput = document.getElementById('bust');
    waistInput = document.getElementById('waist');
    hipInput = document.getElementById('hip');

    // Function to limit input to 3 characters
    const limitToThreeChars = (e) => {
        if (e.target.value.length > 3) {
            e.target.value = e.target.value.slice(0, 3);
        }
    };

    // Add event listeners for input
    [heightInput, weightInput, bustInput, waistInput, hipInput].forEach(input => {
        if (input) {
            input.addEventListener('input', limitToThreeChars);
        }
    });
</script>

<script>
    function startLoading() {
        const button = document.getElementById("upload-btn");
        const loader = document.createElement("div");
        loader.classList.add("loader-btn");
        button.appendChild(loader); // Añadimos el loader al botón

        loader.style.display = 'inline-block'; // Mostramos el loader
        button.disabled = true; // Deshabilitamos el botón para evitar clics múltiples
    }
</script>
<script>
    // Obtener elementos del formulario y el input de archivos
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('files');
    
    // Cargar el worker de FFmpeg
    const { createWorker } = FFmpeg;
    const worker = createWorker({
            logger: (message) => console.log('logger_message', message),
            progress: (p) => console.log('progess',p)
        });

    // Cargar el worker antes de procesar archivos
    (async () => {
        await worker.load();
    })();

    // Procesar imagen con Pica
    async function processImage(file) {
        const img = new Image();
        const reader = new FileReader();
        reader.onload = async (e) => {
            img.src = e.target.result;
            img.onload = async () => {
                const canvas = document.createElement('canvas');
                const width = img.width / 2;  // Redimensionamos la imagen al 50% del tamaño original
                const height = img.height / 2;
                canvas.width = width;
                canvas.height = height;

                // Usamos Pica para redimensionar la imagen
                const pica = new Pica();
                await pica.resize(img, canvas);
                const resizedImage = canvas.toDataURL(file.type);  // Convertimos a Data URL

                // Creamos un nuevo archivo con la imagen redimensionada
                const blob = await fetch(resizedImage).then(res => res.blob());
                const newFile = new File([blob], file.name, { type: file.type });

                // Sustituir el archivo original por el redimensionado
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);
                fileInput.files = dataTransfer.files;  // Establecer el nuevo archivo al input
            };
        };
        reader.readAsDataURL(file);
    }

    // Procesar video con FFmpeg
    async function processVideo(file) {
        const reader = new FileReader();
        reader.onload = async (e) => {
            const videoBlob = e.target.result;
            const inputPath = 'input_video.mp4';
            const outputPath = 'output_video.mp4';

            // Escribir el archivo de video en el sistema de archivos de FFmpeg
            await worker.write(inputPath, videoBlob);

            // Transcodificar video a una resolución más baja (sin recodificación con '-c copy')
            await worker.transcode(inputPath, outputPath, "-c copy -s 1280x720");

            // Leer el archivo procesado
            const { data } = await worker.read(outputPath);

            // Crear un nuevo archivo de video con la transcodificación
            const newBlob = new Blob([data], { type: 'video/mp4' });
            const newFile = new File([newBlob], file.name, { type: file.type });

            // Sustituir el archivo original por el redimensionado
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            fileInput.files = dataTransfer.files;  // Establecer el nuevo archivo al input
        };
        reader.readAsArrayBuffer(file);
    }

    // Manejo de eventos para cuando el usuario seleccione archivos
    if (fileInput) {
        fileInput.addEventListener('change', async (e) => {
            const files = e.target.files;

            // Iteramos sobre los archivos seleccionados y los procesamos
            for (let file of files) {
                if (file.type.startsWith('image/')) {
                    await processImage(file);  // Procesar imágenes
                } else if (file.type.startsWith('video/')) {
                    await processVideo(file);  // Procesar videos
                }
            }

            const uploadBtn = document.getElementById('upload-btn');
            if (uploadBtn) {
                uploadBtn.disabled = false;  // Habilitar el botón de carga
            }
        });
    }

    const uploadBtn = document.getElementById('upload-btn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function (e) {
            // Obtener el user_id encriptado desde Blade
            const userId = "{{ isset($user) ? $user->id : '' }}";
            // Nombre de la cookie con el user_id encriptado
            const cookieName = "ficheros_guardados_" + userId;
            // Verificar si la cookie existe para este user_id
            if (document.cookie.split(';').some((item) => item.trim().startsWith(cookieName + '='))) {
                e.preventDefault();
                alert('ficheros ya subidos');
                setTimeout(function() {
                    window.location.href = '/register/paso-3/{{ isset($user) ? \Crypt::encryptString($user->id) : '' }}';
                }, 300);
                return;
            }
            // Guardar la cookie por 60 días
            document.cookie = cookieName + "=true; path=/; max-age=5184000";
            // Ejecutar el formulario
            const form = document.getElementById('upload-form');
            if (form) {
                form.submit();
            }
        });
    }
</script>

<script>
    // Obtener el paso actual desde la URL
    const pasoActual = window.location.pathname.split('/').pop(); // Esto obtiene "paso-1", "paso-2", "paso-3" de la URL
    
    // Verifica si el paso anterior está completado (esto se debe manejar desde el backend, aquí usamos la sesión)
    const pasoCompletado = {
        'paso-1': {{ session()->has('paso-1-completado') ? 'true' : 'false' }},
        'paso-2': {{ session()->has('paso-2-completado') ? 'true' : 'false' }},
        'paso-3': {{ session()->has('paso-3-completado') ? 'true' : 'false' }}
    };
    
    // Si el paso 1 está completado pero el usuario está en paso-1, redirige al paso-2
    const encryptedUserId = "{{ isset($user) ? \Crypt::encryptString($user->id) : '' }}";
    if (pasoCompletado['paso-1'] && pasoActual === 'paso-1' && encryptedUserId) {
        window.location.href = "{{ route('user.register', ['step' => '2', 'user' => '']) }}" + encryptedUserId;
    }
    
    // Si el paso 2 está completado pero el usuario está en paso-2, redirige al paso-3
    if (pasoCompletado['paso-2'] && pasoActual === 'paso-2' && encryptedUserId) {
        window.location.href = "{{ route('user.register', ['step' => '3', 'user' => '']) }}" + encryptedUserId;
    }
    
    // Evita que el usuario pueda ir hacia atrás al paso anterior en el navegador
    window.history.pushState(null, null, window.location.href); // Agrega un nuevo estado en el historial
    window.onpopstate = function () {
        // Si el usuario presiona "Atrás", redirige al paso siguiente
        if (pasoActual === 'paso-1' && pasoCompletado['paso-1'] && encryptedUserId) {
            window.location.href = "{{ route('user.register', ['step' => '2', 'user' => '']) }}" + encryptedUserId;
        } else if (pasoActual === 'paso-2' && pasoCompletado['paso-2'] && encryptedUserId) {
            window.location.href = "{{ route('user.register', ['step' => '3', 'user' => '']) }}" + encryptedUserId;
        }
    };
</script>
    
    

<script>
    // Validations and AJAX
    document.addEventListener('DOMContentLoaded', () => {
        const btnStep3 = document.querySelector('.btnstep3');
        if (btnStep3) {
            btnStep3.addEventListener('click', (e) => {
                if (!btnStep3.disabled) {
                    e.preventDefault();
                    alert('Registro completado! Te enviamos un correo con los pasos a seguir');
                    e.target.closest('form').submit();
                }
            });
        }

        let tomSelect;
        if (document.getElementById('ciudades')) {
            tomSelect = new TomSelect('#ciudades', {
            placeholder: 'Selecciona ciudades',
            plugins: ['remove_button'],
            onChange: function(value) {
                // Si hay alguna opción seleccionada, ocultamos el placeholder
                if (value.length > 0) {
                $('#ciudades').siblings('.choices').find('.choices__placeholder').hide();
                } else {
                // Si no hay selección, mostramos el placeholder
                $('#ciudades').siblings('.choices').find('.choices__placeholder').show();
                }
            },
            });
        }
        const validateField = (field) => {
            const value = field.value.trim();
            let valid = true;
            let errorMessage = '';
            switch (field.id) {
                case 'full_name':
                    valid = value.length > 0 && value.length <= 255;
                    errorMessage = valid ? '' : 'El nombre completo es requerido y no puede exceder 255 caracteres.';
                    break;

                case 'dni':
                    valid = value.length > 0 && value.length <= 20;
                    errorMessage = valid ? '' : 'El DNI es requerido y no puede exceder 20 caracteres.';
                    break;

                case 'date_of_birth':
                    valid = value.length > 0; // Validate if not empty
                    errorMessage = valid ? '' : 'La fecha de nacimiento es requerida.';
                    break;

                case 'email':
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    // Check if the validation message for "email ya está registrado" exists and is visible
                    const emailValidationMsg = document.querySelector('#email ~ small.validation-msg');
                    const isEmailInUse = emailValidationMsg && emailValidationMsg.textContent.includes('ya está registrado') && emailValidationMsg.style.display !== 'none';
                    valid = emailPattern.test(value) && !isEmailInUse;
                    errorMessage = valid ? '' : (isEmailInUse ? 'Este email ya está registrado.' : 'El email es requerido y debe ser válido.');
                    break;

                case 'dni_file':
                    const acceptedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                    valid = field.files.length > 0 && acceptedImageTypes.includes(field.files[0].type);
                    errorMessage = valid ? '' : 'Por favor seleccione una imagen válida (JPEG, PNG, JPG, GIF o WEBP).';
                    break;

                case 'nickname':
                    // Check if the validation message for "nombre ya está en uso" exists and is visible
                    const nicknameValidationMsg = document.querySelector('#nickname ~ small.validation-msg');
                    const isNameInUse = nicknameValidationMsg && nicknameValidationMsg.textContent.includes('ya está en uso') && nicknameValidationMsg.style.display !== 'none';
                    valid = value.length > 0 && value.length <= 50 && !isNameInUse;
                    errorMessage = valid ? '' : (isNameInUse ? 'Este nombre ya está en uso.' : 'El apodo es requerido y no puede exceder 50 caracteres.');
                    break;

                case 'age':
                    const age = parseInt(value, 10);
                    valid = !isNaN(age) && age >= 18 && age <= 99;
                    errorMessage = valid ? '' : 'La edad es requerida y debe estar entre 18 y 99.';
                    break;

                case 'whatsapp_number':
                case 'phone':
                    const isNumeric = /^\d+$/.test(value); // Check if the value is numeric
                    valid = isNumeric && value.length <= 20; // Must be numeric and max length 20
                    errorMessage = valid ? '' : 'Este campo es requerido, debe ser numérico y no puede exceder 20 caracteres.';
                    break;

                case 'smoker':
                    valid = field.value !== ''; // Validate if a selection was made
                    errorMessage = valid ? '' : 'La opción de fumar es requerida.';
                    break;

                case 'working_zone':
                    valid = value.length > 0 && value.length <= 100;
                    errorMessage = valid ? '' : 'La zona es requerida y no puede exceder 100 caracteres.';
                    break;
                case 'service_location':
                    const locationCheckboxes = document.querySelectorAll('input[name="service_location[]"]:checked');
                    valid = locationCheckboxes.length > 0;
                    errorMessage = valid ? '' : 'Debe seleccionar al menos una ubicación de servicio.';
                    break;


                case 'gender':
                    valid = ['hombre', 'mujer', 'lgbti'].includes(value);
                    errorMessage = valid ? '' : 'El género es requerido y debe ser uno de los siguientes: hombre, mujer, lgbti.';
                    break;

                    case 'height':
                    case 'weight':
                    case 'bust':
                    case 'waist':
                    case 'hip':
                        const numericValue = value.trim();
                        
                        valid = numericValue.length <= 3 && !isNaN(numericValue) && numericValue <= 300;
                        
                        errorMessage = valid ? '' : 'Este campo es requerido, debe ser un número menor o igual a 300, y no debe tener más de 3 caracteres.';
                        break;

                case 'start_day':
                case 'end_day':
                    valid = ['fulltime', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'].includes(value);
                    errorMessage = valid ? '' : 'Este campo es requerido y debe ser uno de los días válidos.';
                    break;

                case 'start_time':
                case 'end_time':
                    const timeValue = parseInt(value, 10);
                    valid = timeValue >= 0 && timeValue <= 23;
                    errorMessage = valid ? '' : 'La hora debe estar entre 0 y 23.';
                    break;

                case 'link':
                    if (value.trim() === '') {
                    } else if (!/^https?:\/\//.test(value)) {
                        valid = false;
                        errorMessage = 'La URL debe comenzar con "http://" o "https://".';
                    } else if (!isValidURL(value)) {
                        valid = false;
                        errorMessage = 'La URL no es válida.';
                    } else {
                        valid = true;
                        errorMessage = '';
                    }
                    break;


                case 'user_id':
                    valid = Number.isInteger(parseInt(value, 10));
                    errorMessage = valid ? '' : 'El ID de usuario es requerido y debe ser un número.';
                    break;

                case 'ciudades':
                    const selectedCities = tomSelect.getValue();
                    valid = Array.isArray(selectedCities) ? selectedCities.length > 0 : selectedCities !== '';
                    errorMessage = valid ? '' : 'Se debe seleccionar al menos una ciudad.';
                    break;

                case 'terms':
                    valid = field.checked;
                    break;
            }
            

            // Show alert if there's an error and the input is not empty
            if (!valid && errorMessage && value.length > 0) {
                setTimeout(function() {
                    alert(errorMessage);
                }, 200);
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }

            console.log(`Field ${field.id} validation: ${valid ? 'valid' : 'invalid'}`);
        
            return valid;
        };

        function isValidURL(str) {
            try {
                new URL(str);
                return true;
            } catch (_) {
                return false;
            }
        }

            // Function to check required fields for the next button
        const checkRequiredFields = (stepId) => {
            const requiredFields = document.querySelectorAll(`#${stepId} [required]`);
    
            let nextButtonClass;
            if (stepId === 'step-1') {
                nextButtonClass = '.btnstep1';
            } else if (stepId === 'step-2') {
                nextButtonClass = '.btnstep2';
            } else if (stepId === 'step-3') {
                nextButtonClass = '.btnstep3';
            }
    
            const nextButton = document.querySelector(nextButtonClass);
            let allFilled = true;
    
            requiredFields.forEach(field => {
                console.log(`Validating field: ${field.id}`);
                if (!validateField(field)) {
                    allFilled = false;
                }
            });
    
            nextButton.disabled = !allFilled;
            nextButton.classList.toggle('disabled', !allFilled);
        };

        document.querySelectorAll('input, select').forEach(field => {
            console.log
            field.addEventListener('change', () => {
                validateField(field);
                checkRequiredFields(field.closest('.form-step').id);
            });
        });
    });
    </script>
    










<script>
    /*
    // Obtener la fecha actual
    const today = new Date();
    // Calcular la fecha mínima (hace 18 años)
    const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    // Formatear la fecha a YYYY-MM-DD
    const formattedMinDate = minDate.toISOString().split('T')[0];

    // Establecer el atributo min y max del input
    document.getElementById('date_of_birth').setAttribute('max', formattedMinDate);

    // Agregar un evento al input de fecha para calcular la edad solo si el año está completo
    document.getElementById('date_of_birth').addEventListener('input', function() {
        if (this.value.length === 10) { // Solo ejecutar cuando la fecha esté completa (YYYY-MM-DD)
            const selectedDate = new Date(this.value);
            let age = today.getFullYear() - selectedDate.getFullYear();
            const monthDifference = today.getMonth() - selectedDate.getMonth();
            
            // Ajustar la edad si no ha llegado el cumpleaños este año
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < selectedDate.getDate())) {
                age--;
            }

            // Establecer el valor de edad en el input
            document.getElementById('age').value = age;
        } else {
            document.getElementById('age').value = ''; // Limpiar el campo de edad si la fecha no está completa
        }
    });
    */
    </script>
    

<script> //Upload images
    const inputFile = document.querySelector('.image_upload');

    if(inputFile !== undefined && inputFile !== null) {
        inputFile.addEventListener('change', (event) => {
            let files = event.target.files;
            const maxSizeInBytes = 10 * 1024 * 1024; // 10 MB en bytes
            const maxFiles = 8; // Máximo de 8 archivos

            // Filtrar los archivos que cumplen con el límite de tamaño
            const validFiles = Array.from(files).filter(file => file.size <= maxSizeInBytes);

            // Mostrar un mensaje al usuario si se eliminaron archivos
            if (files.length !== validFiles.length) {
                const invalidFiles = Array.from(files).filter(file => file.size > maxSizeInBytes);
                const invalidFileNames = invalidFiles.map(file => file.name).join(', ');
                alert('Estos ficheros exceden el límite de 10MB y han sido borrados: ' + invalidFileNames);
            }

            // Filtrar los archivos válidos para asegurarse de que no excedan el máximo permitido
            const finalFiles = validFiles.slice(0, maxFiles);
            if (validFiles.length > maxFiles) {
                alert('Solo se pueden seleccionar un máximo de 8 archivos. Los primeros 8 han sido seleccionados y los demás han sido ignorados.');
            }

            // Crear un DataTransfer para asignar los archivos válidos
            const dataTransfer = new DataTransfer();
            finalFiles.forEach(file => dataTransfer.items.add(file));

            // Asignar los archivos válidos al input file
            inputFile.files = dataTransfer.files;
        });
    }
</script>
      
<script> //Same whatsapp and phone number
    const fullTimeCheckbox = document.getElementById('fulltime');
    const startTimeDiv = document.getElementById('start_time_div');
    const endTimeDiv = document.getElementById('end_time_div');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    if(fullTimeCheckbox) {
        fullTimeCheckbox.addEventListener('change', () => {
            if (fullTimeCheckbox.checked) {
                startTimeDiv.classList.add('d-none');
                endTimeDiv.classList.add('d-none');
                startTimeInput.value = '';
                endTimeInput.value = '';
                startTimeInput.selectedIndex = 0;
                endTimeInput.selectedIndex = 0;
            } else {
                startTimeDiv.classList.remove('d-none');
                endTimeDiv.classList.remove('d-none');
            }
        });
    }

    const whatsappInput = document.getElementById('whatsapp_number');
    const phoneInput = document.getElementById('phone');

    // Function to allow only numeric input
    const restrictToNumbers = (input) => {
        input.value = input.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
    };

    if(whatsappInput && phoneInput) {
        whatsappInput.addEventListener('input', () => {
            restrictToNumbers(whatsappInput);
            phoneInput.value = whatsappInput.value; // Sync values
        });

        phoneInput.addEventListener('input', () => {
            restrictToNumbers(phoneInput);
        });
    }

    // Add event listeners for height, weight, bust, waist, and hip fields
    heightInput = document.getElementById('height');
    weightInput = document.getElementById('weight');
    bustInput = document.getElementById('bust');
    waistInput = document.getElementById('waist');
    hipInput = document.getElementById('hip');

    // Add input validation if the elements exist
    if (heightInput) {
        heightInput.addEventListener('input', () => {
            restrictToNumbers(heightInput);
        });
    }

    if (weightInput) {
        weightInput.addEventListener('input', () => {
            restrictToNumbers(weightInput);
        });
    }

    if (bustInput) {
        bustInput.addEventListener('input', () => {
            restrictToNumbers(bustInput);
        });
    }

    if (waistInput) {
        waistInput.addEventListener('input', () => {
            restrictToNumbers(waistInput);
        });
    }

    if (hipInput) {
        hipInput.addEventListener('input', () => {
            restrictToNumbers(hipInput);
        });
    }
</script>

<style>

    label {
        color: #f1f1f1;
    }
    .row_atributes {
        display: flex;
        flex-direction: row;
    }
    .main-div {
        width: 100%;
        min-height: 100vh;
        background: #252525;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .correct{
        border: 4px solid #05bd2f;
    }

    .error{
        border: 4px solid red;
    }

    input, select {
        position: relative;
    }

    .disabled {
        opacity: 0.5;
        cursor: not-allowed!important;
    }

    input[type="radio"]:checked:after {
        width: 13px;
        height: 13px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #f44806;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    hr{
        border: 1px solid #f44806;
        max-width: 180px;
        min-width: 180px;
        margin: 0px auto;
        opacity: 0.5;
    }

    .form-div{
        min-width: 350px;
        width: 100%;
        border: 1px solid #f44806;
        border-radius: 10px;
        box-shadow: rgba(244, 72, 6, 0.5) 0px 3px 8px;
        padding: 25px;
        margin: 25px;
    }

    h1 {
        text-align: center;
        color: #f6f6f6;
        margin-top: 15px;
    }
    h2 {
        margin-top: 20px;
        color: #f6f6f6;
        text-align: center;
    }
    #multi-step-form-container {
        width: 100%;
        margin-top: 25px;
    }
    .text-center {
        text-align: center;
    }
    .mx-auto {
        margin-left: auto;
        margin-right: auto;
    }
    a{
        text-decoration: none;
    }
    .pl-0 {
        padding-left: 0;
    }
    .button {
        padding: 0.7rem 1.5rem;
        border: 1px solid #f44806;
        background-color: #f44806;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }
    .submit-btn {
        border: 1px solid #13da35;
        background-color: #13da35;
    }
    .mt-3 {
        margin-top: 2rem;
    }
    .d-none {
        display: none;
    }
    .form-step {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        padding: 1rem;
    }
    .font-normal {
        font-weight: normal;
    }
    ul.form-stepper {
        counter-reset: section;
        margin-bottom: 3rem;
    }
    ul.form-stepper .form-stepper-circle {
        position: relative;
    }
    ul.form-stepper .form-stepper-circle span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
    }
    .form-stepper-horizontal {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }
    ul.form-stepper > li:not(:last-of-type) {
        margin-bottom: 0.625rem;
        -webkit-transition: margin-bottom 0.4s;
        -o-transition: margin-bottom 0.4s;
        transition: margin-bottom 0.4s;
    }
    .form-stepper-horizontal > li:not(:last-of-type) {
        margin-bottom: 0 !important;
    }
    .form-stepper-horizontal li {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: start;
        -webkit-transition: 0.5s;
        transition: 0.5s;
    }
    .form-stepper-horizontal li:not(:last-child):after {
        position: relative;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height: 1px;
        content: "";
        top: 32%;
    }
    .form-stepper-horizontal li:after {
        background-color: #dee2e6;
    }
    .form-stepper-horizontal li.form-stepper-completed:after {
        background-color: #4da3ff;
    }
    .form-stepper-horizontal li:last-child {
        flex: unset;
    }
    ul.form-stepper li a .form-stepper-circle {
        display: inline-block;
        width: 40px;
        height: 40px;
        margin-right: 0;
        line-height: 1.7rem;
        text-align: center;
        background: rgba(0, 0, 0, 0.38);
        border-radius: 50%;
    }
    .form-stepper .form-stepper-active .form-stepper-circle {
        background-color: #f44806 !important;
        color: #fff;
    }

    .form-stepper .form-stepper-active .form-stepper-circle {
        background-color: #f44806 !important;
        color: #fff;
    }

    .form-stepper .form-stepper-active .label {
        color: #f44806 !important;
        
    }
    
    .form-stepper .form-stepper-active .form-stepper-circle:hover {
        background-color: #f44806 !important;
        color: #fff !important;
    }
    .form-stepper .form-stepper-unfinished .form-stepper-circle {
        background-color: red;
    }
    .form-stepper .form-stepper-completed .form-stepper-circle {
        background-color: #13da35 !important;
        color: #fff;
    }
    .form-stepper .form-stepper-completed .label {
        color: #13da35 !important;
    }
    .form-stepper .form-stepper-completed .form-stepper-circle:hover {
        background-color: #13da35 !important;
        color: #fff !important;
    }
    .form-stepper .form-stepper-active span.text-muted {
        color: #fff !important;
    }
    .form-stepper .form-stepper-completed span.text-muted {
        color: #fff !important;
    }
    .form-stepper .label {
        font-size: 1rem;
        margin-top: 0.5rem;
    }
    .form-stepper a {
        cursor: default;
    }

    .buttons_div{
        width: 100%;
        display: flex;
        justify-content: space-around;
    }

    .btn-navigate-form-step{
        width:100%;
        text-align: center!important;
    }

    .btn-navigate-form-step2{
        min-width: 100%;
    }

    .card, .card-header, .card-body {
        background-color: transparent;
        color:#f1f1f1;
    }

    input, select, select:focus {
        background: transparent!important;
        color: #f1f1f1!important;
    }

    input[type="checkbox"] {
        background: transparent!important;
        border: 1px solid #f44806;
    }
    
    input[type="checkbox"]:checked{
        accent-color: #f44806!important;
        background-color: #f44806;
        border: none;
    }

    .submit_btn {
        background: #f44806;
        color:#f1f1f1;
    }

    .submit_btn_finish {
        background: #13DA35;
    }

    .option {
        color:#000!important;
    }
</style>

<style>
    .ts-wrapper.multi .ts-control > div {
        background-color: #F65807 !important;
        color: white !important;
    }

    .ts-control {
        background: #252525!important;
        
    }

    #ciudades-ts-control {
        color: #f1f1f1 !important;
    }
</style>

@endsection
