@extends('layouts.admin')
@section('title') Editar usuario {{$user->full_time}} @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Editar usuario {{$user->full_time}}</h4>
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
                    <form action="{{ route('admin.users.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf  
                        <div class="form-group">
                            <label for="full_name">Nombre Completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required value="{{$user->full_name}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required value="{{$user->dni}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_of_birth">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required value="{{$user->date_of_birth}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required value="{{$user->email}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="dni_file">Subir DNI</label>
                            <input type="file" class="form-control-file" id="dni_file" name="dni_file" accept=".jpeg,.png,.jpg,.gif,.webp">
                            @if(!is_null($user->dni_file))
                                <p>DNI actual:</p>
                                <img src="{{ route('admin.images.get', ['filename' => $user->dni_file]) }}" alt="DNI actual" class="img-fluid" width="200" />
                            @endif
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="nickname">Defina el NickName (nombre fantasía)</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" required value="{{$user->nickname}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="age">Edad (imposibilidad de cambiarlo después)</label>
                            <input type="number" class="form-control" id="age" name="age" min="18" max="99" required value="{{$user->age}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="whatsapp_number">WhatsApp</label>
                            <input type="tel" class="form-control" id="whatsapp" name="whatsapp" required value="{{$user->whatsapp}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="phone">Llamadas</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required value="{{$user->phone}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="smoker">Fumadora?</label>
                            <select class="form-control" id="smoker" name="smoker" required>
                                @if($user->is_smoker === 1)
                                <option value="1" selected hidden>Si</option>
                                @else
                                <option value="0" selected hidden>No</option>
                                @endif
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                            </div>

                            <div class="form-group">
                                <label for="smoker">Ciudades</label>
                                <div style="background:#f1f1f1; border:2px solid #aaa; padding:20px; min-height:80px;">
                                    @foreach ($cities->sortBy('name') as $c)
                                    <div class="form-check mt-2">
                                        @php $city = \App\Models\CityUser::where('user_id', $user->id)->where('city_id', $c->id)->first(); @endphp
                                        <input class="form-check-input" type="checkbox" id="{{$c->id}}" name="city[]" value="{{$c->id}}" @if(is_object($city)) checked @endif/>
                                        <label class="form-check-label" for="{{$c->id}}">
                                            {{$c->name}}
                                        </label>  
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        
                            <div class="form-group">
                            <label for="working_zone">Zona donde Trabajara</label>
                            <input type="text" class="form-control" id="working_zone" name="working_zone" required value="{{$user->working_zone}}">
                            </div>

                            <div class="form-group">
                                <label for="service_location">Donde atendera?</label>
                                <div style="background:#f1f1f1; border:2px solid #aaa; padding:20px; min-height:80px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="piso_propio" name="service_location[]" value="piso_propio"
                                            {{ in_array('piso_propio', json_decode($user->service_location, true) ?? []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="piso_propio">
                                            Piso Propio
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="domicilio" name="service_location[]" value="domicilio"
                                            {{ in_array('domicilio', json_decode($user->service_location, true) ?? []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="domicilio">
                                            Domicilio
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="hotel" name="service_location[]" value="hotel"
                                            {{ in_array('hotel', json_decode($user->service_location, true) ?? []) ? 'checked' : '' }}/>
                                        <label class="form-check-label" for="hotel">
                                            Hoteles
                                        </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group">
                            <label for="gender">Genero</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="{{$user->gender}}" selected hidden>{{ucfirst($user->gender)}}</option>
                                <option value="hombre">Hombre</option>
                                <option value="mujer">Mujer</option>
                                <option value="lgbti">LGTBI+</option>
                            </select>
                            </div>
                        
                            <div class="form-group">
                            <label for="height">Estatura (en cm)</label>
                            <input type="number" class="form-control" id="height" name="height" required value="{{$user->height}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="weight">Peso</label>
                            <input type="number" class="form-control" id="weight" name="weight" required value="{{$user->weight}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="bust">Busto</label>
                            <input type="number" class="form-control" id="bust" name="bust" required value="{{$user->bust}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="waist">Cintura</label>
                            <input type="number" class="form-control" id="waist" name="waist" required value="{{$user->waist}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="hip">Cadera</label>
                            <input type="number" class="form-control" id="hip" name="hip" required value="{{$user->hip}}">
                            </div>
                        
                            <div class="form-group">
                            <label for="start_day">Dia de Inicio</label>
                            <select class="form-control" id="start_day" name="start_day" required>
                                <option value="{{$user->start_day}}" selected hidden>{{ucfirst($user->start_day)}}</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miercoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sabado">Sabado</option>
                                <option value="domingo">Domingo</option>
                            </select>
                            </div>
                        
                            <div class="form-group">
                            <label for="end_day">Dia de Fin</label>
                            <select class="form-control" id="end_day" name="end_day" required>
                                <option value="{{$user->end_day}}" selected hidden>{{ucfirst($user->end_day)}}</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miercoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sabado">Sabado</option>
                                <option value="domingo">Domingo</option>
                            </select>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="fulltime" name="fulltime_time" value="1" @if($user->start_time == "fulltime") checked @endif/>
                                <label class="form-check-label" for="fulltime">
                                    Horario fulltime
                                </label>  
                            </div>
                        
                            <div class="form-group @if($user->start_time == "fulltime") d-none @endif" id="start_time_div">
                            <label for="start_time">Hora de Inicio</label>
                            <select class="form-control" id="start_time" name="start_time" >
                                @if($user->start_time == "fulltime")
                                <option disabled selected hidden>Selecciona una hora inicio</option>
                                @else
                                <option value="{{$user->start_time}}" selected hidden>{{ucfirst($user->start_time)}}</option>
                                @endif
                                
                                @for ($i = 0; $i <= 23; $i++) {
                                <option value="{{ $i }}">{{ $i }}</option>
                                }
                                @endfor
                            </select>
                            </div>
                        
                            <div class="form-group @if($user->start_time == "fulltime") d-none @endif" id="end_time_div">
                            <label for="end_time">Hora de Fin</label>
                            <select class="form-control" id="end_time" name="end_time" >
                                @if($user->start_time == "fulltime")
                                <option disabled selected hidden>Selecciona una hora fin</option>
                                @else
                                <option value="{{$user->end_time}}" selected hidden>{{ucfirst($user->end_time)}}</option>
                                @endif
                                
                                
                                @for ($i = 0; $i <= 23; $i++) {
                                <option value="{{ $i }}">{{ $i }}</option>
                                }
                                @endfor
                            </select>
                            </div>

                            <div class="form-group">
                                <label for="link">Enlace</label>
                                <input type="url" class="form-control" id="link" name="link" value="{{ $user->link }}" placeholder="https://example.com" pattern="https://.*" size="80" />
                            </div>

                            <input type="text" hidden name="user_id" value="{{$user->id}}" />
                        
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
            } else {
                startTimeDiv.classList.remove('d-none');
                endTimeDiv.classList.remove('d-none');
            }
        });
    </script>
    
@endsection