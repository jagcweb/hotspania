@extends('layouts.admin')
@section('title') Crear Usuario @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Crear nuevo usuario</h4>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <form action="{{ route('admin.users.save') }}" method="POST" enctype="multipart/form-data" autocomplete="on">
                        @csrf  
                        <div class="form-group">
                            <label for="full_name">Nombre Completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_of_birth">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dni_file">Subir DNI</label>
                            <input type="file" class="form-control-file" id="dni_file" name="dni_file" required accept=".jpeg,.png,.jpg,.gif,.webp">
                        </div>
                    
                        <hr>
                    
                        <div class="form-group">
                            <label for="nickname">Defina el NickName (nombre fantasía)</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="age">Edad (imposibilidad de cambiarlo después)</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" min="18" max="99" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_number">WhatsApp</label>
                            <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp" value="{{ old('whatsapp') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Llamadas</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="smoker">¿Fumadora?</label>
                            <select class="form-control" id="smoker" name="smoker" >
                                <option value="0" {{ old('smoker') == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('smoker') == 1 ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>          
                    
                        <div class="form-group">
                            <label for="cities">Ciudades</label>
                            <div style="background:#f1f1f1; border:2px solid #aaa; padding:20px; min-height:80px;">
                                @foreach ($cities as $c)
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
                            <label for="working_zone">Zona donde Trabajara</label>
                            <input type="text" class="form-control" id="working_zone" name="working_zone" value="{{ old('working_zone') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="service_location">Donde atendera?</label>
                            <select class="form-control" id="service_location" name="service_location" required>
                                <option value="piso_propio" {{ old('service_location') == 'piso_propio' ? 'selected' : '' }}>Piso Propio</option>
                                <option value="domicilio" {{ old('service_location') == 'domicilio' ? 'selected' : '' }}>Domicilio</option>
                                <option value="hotel" {{ old('service_location') == 'hotel' ? 'selected' : '' }}>Hoteles</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Genero</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="mujer" {{ old('gender') == 'mujer' ? 'selected' : '' }}>Mujer</option>
                                <option value="hombre" {{ old('gender') == 'hombre' ? 'selected' : '' }}>Hombre</option>
                                <option value="lgbti" {{ old('gender') == 'lgbti' ? 'selected' : '' }}>LGTBI+</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="height">Estatura (en cm)</label>
                            <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="weight">Peso</label>
                            <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bust">Busto</label>
                            <input type="number" class="form-control" id="bust" name="bust" value="{{ old('bust') }}" >
                        </div>
                        
                        <div class="form-group">
                            <label for="waist">Cintura</label>
                            <input type="number" class="form-control" id="waist" name="waist" value="{{ old('waist') }}" >
                        </div>
                        
                        <div class="form-group">
                            <label for="hip">Cadera</label>
                            <input type="number" class="form-control" id="hip" name="hip" value="{{ old('hip') }}" >
                        </div>
                        
                        <div class="form-group">
                            <label for="start_day">Dia de Inicio</label>
                            <select class="form-control" id="start_day" name="start_day" required>
                                <option value="lunes" {{ old('start_day') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                <option value="martes" {{ old('start_day') == 'martes' ? 'selected' : '' }}>Martes</option>
                                <option value="miercoles" {{ old('start_day') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                <option value="jueves" {{ old('start_day') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                <option value="viernes" {{ old('start_day') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                <option value="sabado" {{ old('start_day') == 'sabado' ? 'selected' : '' }}>Sabado</option>
                                <option value="domingo" {{ old('start_day') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="end_day">Dia de Fin</label>
                            <select class="form-control" id="end_day" name="end_day" required>
                                <option value="lunes" {{ old('end_day') == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                <option value="martes" {{ old('end_day') == 'martes' ? 'selected' : '' }}>Martes</option>
                                <option value="miercoles" {{ old('end_day') == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                                <option value="jueves" {{ old('end_day') == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                <option value="viernes" {{ old('end_day') == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                <option value="sabado" {{ old('end_day') == 'sabado' ? 'selected' : '' }}>Sabado</option>
                                <option value="domingo" {{ old('end_day') == 'domingo' ? 'selected' : '' }}>Domingo</option>
                            </select>
                        </div>
                    
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="fulltime" name="fulltime_time" value="1" {{ old('fulltime_time') ? 'checked' : '' }}/>
                            <label class="form-check-label" for="fulltime">
                                Horario fulltime
                            </label>  
                        </div>
                        
                        <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="start_time_div">
                            <label for="start_time">Hora de Inicio</label>
                            <select class="form-control" id="start_time" name="start_time">
                                <option disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora inicio</option>
                                @for ($i = 0; $i <= 23; $i++)
                                    <option value="{{ $i }}" {{ old('start_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="form-group {{ old('fulltime_time') ? 'd-none' : '' }}" id="end_time_div">
                            <label for="end_time">Hora de Fin</label>
                            <select class="form-control" id="end_time" name="end_time">
                                <option disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora fin</option>
                                @for ($i = 0; $i <= 23; $i++)
                                    <option value="{{ $i }}" {{ old('end_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="link">Enlace</label>
                            <input type="url" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com" pattern="https://.*" size="80" />
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>                    
                </div>
            </div>
        </div>
    </div>

    <script>
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

        whatsappInput.addEventListener('change', () => {
            phoneInput.value = whatsappInput.value;
        });
    </script>
    
@endsection