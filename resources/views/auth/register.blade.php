@extends('layouts.app')

@section('title') Registrarse @endsection
@section('content')

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
                <form method="POST" action="{{ route('user.save', ['step' => 'step-1', 'id' => NULL]) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <section id="step-1" class="form-step">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nickname">Apodo ("nombre fantasía")</label>
                                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="date_of_birth">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                        </div>
                                    
                                        
                                        
                                        <div class="form-group">
                                            <label for="age">Edad (imposibilidad de cambiarlo después)</label>
                                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" min="18" max="99" readonly required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="whatsapp_number">Nº teléfono WhatsApp</label>
                                            <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp" value="{{ old('whatsapp') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="phone">Nº teléfono Llamadas</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="smoker">¿Fumas?</label>
                                            <select class="form-control" id="smoker" name="smoker" required>
                                                <option class="option" value="0" {{ old('smoker') == 0 ? 'selected' : '' }}>No</option>
                                                <option class="option" value="1" {{ old('smoker') == 1 ? 'selected' : '' }}>Sí</option>
                                            </select>
                                        </div>          
                                    
                                        <div class="form-group">
                                            <label for="cities">Ciudades</label>
                                            <div style="background:transparent; border:2px solid #aaa; padding:20px; min-height:80px;">
                                                @foreach ($cities->sortBy('name') as $c)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="{{$c->id}}" name="city[]" value="{{$c->id}}"
                                                            {{ (is_array(old('city')) && in_array($c->id, old('city'))) ? 'checked' : '' }}/>
                                                        <label class="form-check-label" for="{{$c->id}}">
                                                            {{$c->name}}
                                                        </label>  
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="working_zone">Zona de trabajo</label>
                                            <input type="text" class="form-control" id="working_zone" name="working_zone" value="{{ old('working_zone') }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="service_location">Lugar de atención</label>
                                            <select class="form-control" id="service_location" name="service_location" required>
                                                <option class="option" value="piso_propio" {{ old('service_location') == 'piso_propio' ? 'selected' : '' }}>Piso Propio</option>
                                                <option class="option" value="domicilio" {{ old('service_location') == 'domicilio' ? 'selected' : '' }}>Domicilio</option>
                                                <option class="option" value="hotel" {{ old('service_location') == 'hotel' ? 'selected' : '' }}>Hoteles</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="gender">Género</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option class="option" value="mujer" {{ old('gender') == 'mujer' ? 'selected' : '' }}>Mujer</option>
                                                <option class="option" value="hombre" {{ old('gender') == 'hombre' ? 'selected' : '' }}>Hombre</option>
                                                <option class="option" value="lgbti+" {{ old('gender') == 'lgbti+' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>
                                        
                                        <div class="row row_atributes">
                                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                                <label for="height">Altura (cm)</label>
                                                <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}" required>
                                            </div>
                                        
                                        
                                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                                <label for="weight">Peso (kg)</label>
                                                <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" required>
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="bust">Busto (cm)</label>
                                                <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust') }}" >
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="waist">Cintura (cm)</label>
                                                <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist') }}" >
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                <label for="hip">Cadera (cm)</label>
                                                <input type="number" class="form-control" id="hip" name="hip" value="{{ old('hip') }}" >
                                            </div>
                                        </div>
                                        
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
                                    
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="fulltime" name="fulltime_time" value="1" {{ old('fulltime_time') ? 'checked' : '' }}/>
                                            <label class="form-check-label" for="fulltime">
                                                ¿Horario 24h? (fulltime)
                                            </label>  
                                        </div>
                                        
                                        <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="start_time_div">
                                            <label for="start_time">Hora de Inicio</label>
                                            <select class="form-control" id="start_time" name="start_time">
                                                <option class="option" disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora inicio</option>
                                                @for ($i = 0; $i <= 23; $i++)
                                                    <option class="option" value="{{ $i }}" {{ old('start_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="end_time_div">
                                            <label for="end_time">Hora de Fin</label>
                                            <select class="form-control" id="end_time" name="end_time">
                                                <option class="option" disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora fin</option>
                                                @for ($i = 0; $i <= 23; $i++)
                                                    <option class="option" value="{{ $i }}" {{ old('end_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
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
                <form method="POST" action="{{ route('user.save', ['step' => 'step-2', 'id' => \Crypt::encryptString($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    <section id="step-2" class="form-step">
                        <h2 class="font-normal">Sube tus imágenes y vídeos </h2>
                        <div class="form-group">
                            <label for="files">Archivos</label>
                            <input type="file" name="files[]" class="step2input form-control mt-1 image_upload" 
                                    id="files" accept=".png,.jpeg,.jpg,.webp,.gif,.bmp,.avi,.mp4,.mpg,.mov,.3gp,.wmv,.flv" multiple required>
                            <small style="color:#fff;">Máximo 8 archivos y 10mb por cada uno.</small>
                        </div>
                        <button class="w-100 btnstep2 disabled button btn-navigate-form-step submit_btn" disabled type="submit" step_number="3" data="step-1">Siguiente</button>
                    </section>
                </form>
            @endif
            
            

            @if($step == 3)
                <form method="POST" action="{{ route('user.save', ['step' => 'step-3', 'id' => \Crypt::encryptString($user->id)]) }}" enctype="multipart/form-data">
                    @csrf
                    <section id="step-3" class="form-step">
                        <h2 class="font-normal">Datos Personales</h2>
                        <div class="form-group">
                            <label for="full_name">Nombre y apellidos</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
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
                        <button class="w-100 btnstep3 disabled button submit_btn submit_btn_finish submit-btn" type="submit" disabled data="step-3">Guardar</button>
                    </section>
                </form>
            @endif
        </div>
    </div>
</div>

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
    if (pasoCompletado['paso-1'] && pasoActual === 'paso-1') {
        window.location.href = "{{ route('user.register', ['step' => '2', 'user' => \Crypt::encryptString($user->id)]) }}";
    }
    
    // Si el paso 2 está completado pero el usuario está en paso-2, redirige al paso-3
    if (pasoCompletado['paso-2'] && pasoActual === 'paso-2') {
        window.location.href = "{{ route('user.register', ['step' => '3', 'user' => \Crypt::encryptString($user->id)]) }}";
    }
    
    // Evita que el usuario pueda ir hacia atrás al paso anterior en el navegador
    window.history.pushState(null, null, window.location.href); // Agrega un nuevo estado en el historial
    window.onpopstate = function () {
        // Si el usuario presiona "Atrás", redirige al paso siguiente
        if (pasoActual === 'paso-1' && pasoCompletado['paso-1']) {
            window.location.href = "{{ route('user.register', ['step' => '2', 'user' => \Crypt::encryptString($user->id)]) }}";
        } else if (pasoActual === 'paso-2' && pasoCompletado['paso-2']) {
            window.location.href = "{{ route('user.register', ['step' => '3', 'user' => \Crypt::encryptString($user->id)]) }}";
        }
    };
</script>
    
    

<script>
    // Validations and AJAX
 
    document.addEventListener('DOMContentLoaded', () => {
        const validateField = (field) => {
            const value = field.value.trim();
            let valid = true;
            let errorMessage = '';

            // Validations based on field type
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
                    valid = emailPattern.test(value);
                    errorMessage = valid ? '' : 'El email es requerido y debe ser válido.';
                    break;

                case 'dni_file':
                    // Assuming validation is handled in the backend
                    break;

                case 'nickname':
                    valid = value.length > 0 && value.length <= 50;
                    errorMessage = valid ? '' : 'El apodo es requerido y no puede exceder 50 caracteres.';
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
                case 'service_location':
                    valid = value.length > 0;
                    errorMessage = valid ? '' : 'Este campo es requerido.';
                    break;

                case 'gender':
                    valid = ['hombre', 'mujer', 'otro'].includes(value);
                    errorMessage = valid ? '' : 'El género es requerido y debe ser uno de los siguientes: hombre, mujer, otro.';
                    break;

                case 'height':
                case 'weight':
                case 'bust':
                case 'waist':
                case 'hip':
                    const numericValue = parseFloat(value);
                    valid = !isNaN(numericValue) && numericValue <= 300;
                    errorMessage = valid ? '' : 'Este campo es requerido y debe ser un número menor o igual a 300.';
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

                case 'city':
                    const cityCheckboxes = document.querySelectorAll('input[name="city[]"]:checked'); // Adjust this selector based on your HTML structure
                    valid = cityCheckboxes.length > 0; // Check if at least one city is selected
                    errorMessage = valid ? '' : 'Se debe seleccionar al menos una ciudad.';
                    break;

                // Add additional validations as needed
            }

            // Show alert if there's an error and the input is not empty
            if (!valid && errorMessage && value.length > 0) {
                alert(errorMessage);
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }

            return valid;
        };

        document.querySelectorAll('input').forEach(field => {
            field.addEventListener('change', () => {
                validateField(field);
                checkRequiredFields(field.closest('.form-step').id);
            });
        });
    
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
                if (!validateField(field)) {
                    allFilled = false;
                }
            });
    
            nextButton.disabled = !allFilled;
            nextButton.classList.toggle('disabled', !allFilled);
        };
    });
    </script>
    










<script> //Min 18 years date and put input age
    // Obtener la fecha actual
    const today = new Date();
    // Calcular la fecha mínima (hace 18 años)
    const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    // Formatear la fecha a YYYY-MM-DD
    const formattedMinDate = minDate.toISOString().split('T')[0];
    const formattedMaxDate = today.toISOString().split('T')[0];

    // Establecer el atributo min y max del input
    document.getElementById('date_of_birth').setAttribute('min', '');
    document.getElementById('date_of_birth').setAttribute('max', formattedMinDate);

    // Agregar un evento al input de fecha para calcular la edad
    document.getElementById('date_of_birth').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        let age = today.getFullYear() - selectedDate.getFullYear();
        const monthDifference = today.getMonth() - selectedDate.getMonth();
        
        // Ajustar la edad si no ha llegado el cumpleaños este año
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < selectedDate.getDate())) {
            age--;
        }

        // Establecer el valor de edad en el input
        document.getElementById('age').value = age;
    });
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

    const whatsappInput = document.getElementById('whatsapp_number');
    const phoneInput = document.getElementById('phone');

    // Function to allow only numeric input
    const restrictToNumbers = (input) => {
        input.value = input.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
    };

    whatsappInput.addEventListener('input', () => {
        restrictToNumbers(whatsappInput);
        phoneInput.value = whatsappInput.value; // Sync values
    });

    phoneInput.addEventListener('input', () => {
        restrictToNumbers(phoneInput);
    });

    // Add event listeners for height, weight, bust, waist, and hip fields
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('weight');
    const bustInput = document.getElementById('bust');
    const waistInput = document.getElementById('waist');
    const hipInput = document.getElementById('hip');

    heightInput.addEventListener('input', () => {
        restrictToNumbers(heightInput);
    });

    weightInput.addEventListener('input', () => {
        restrictToNumbers(weightInput);
    });

    bustInput.addEventListener('input', () => {
        restrictToNumbers(bustInput);
    });

    waistInput.addEventListener('input', () => {
        restrictToNumbers(waistInput);
    });

    hipInput.addEventListener('input', () => {
        restrictToNumbers(hipInput);
    });
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

@endsection
