@extends('layouts.app')

@section('title') Editar datos @endsection

@section('content')

<h1 class="w-100 text-center text-white">Editar mis Datos (Última actualización: {{ \Carbon\Carbon::parse(\Auth::user()->updated_at)->format('d/m/Y H:i') }})</h1>
@include('partial_msg')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card" style="background: white; border: none; padding: 20px;">
            <form method="POST" action="{{ route('account.update') }}" autocomplete="off">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nickname">Apodo ("nombre fantasía")</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" value="{{ \Auth::user()->nickname }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_of_birth">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ \Auth::user()->date_of_birth }}" required 
                        onkeydown="showAlert(event)" onpaste="showAlert(event)"
                        >
    
                    </div>
                
                    <div class="form-group">
                        <label for="whatsapp_number">Nº teléfono WhatsApp</label>
                        <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp" value="{{ \Auth::user()->whatsapp }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Nº teléfono Llamadas</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ \Auth::user()->phone }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="smoker">¿Fumas?</label>
                        <select class="form-control" id="smoker" name="smoker" required>
                            <option class="option" value="0" {{ \Auth::user()->is_smoker == 0 ? 'selected' : '' }}>No</option>
                            <option class="option" value="1" {{ \Auth::user()->is_smoker == 1 ? 'selected' : '' }}>Sí</option>
                        </select>
                    </div>          
                
                    <div class="form-group">
                        <label for="cities">Ciudades</label>
                        <div style="background:transparent; border:2px solid #aaa; padding:20px; min-height:80px;">
                            @php $cities_user = \App\Models\CityUser::where('user_id', \Auth::user()->id)->pluck('city_id')->toArray(); @endphp
                            @foreach ($cities->sortBy('name') as $c)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="{{$c->id}}" name="city[]" value="{{$c->id}}"
                                        {{ (is_array($cities_user) && in_array($c->id, $cities_user)) ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="{{$c->id}}">
                                        {{$c->name}}
                                    </label>  
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label for="working_zone">Zona de trabajo</label>
                        <input type="text" class="form-control" id="working_zone" name="working_zone" value="{{ \Auth::user()->working_zone }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="service_location">Lugar de atención</label>
                        <select class="form-control" id="service_location" name="service_location" required>
                            <option class="option" value="piso_propio" {{ \Auth::user()->service_location == 'piso_propio' ? 'selected' : '' }}>Piso Propio</option>
                            <option class="option" value="domicilio" {{ \Auth::user()->service_location == 'domicilio' ? 'selected' : '' }}>Domicilio</option>
                            <option class="option" value="hotel" {{ \Auth::user()->service_location == 'hotel' ? 'selected' : '' }}>Hoteles</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Género</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option class="option" value="mujer" {{ \Auth::user()->gender == 'mujer' ? 'selected' : '' }}>Mujer</option>
                            <option class="option" value="hombre" {{ \Auth::user()->gender == 'hombre' ? 'selected' : '' }}>Hombre</option>
                            <option class="option" value="lgbti" {{ \Auth::user()->gender == 'lgbti' ? 'selected' : '' }}>LGTBI+</option>
                        </select>
                    </div>
                    
                    <div class="row row_atributes">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <label for="height">Altura (cm)</label>
                            <input type="number" class="form-control" id="height" name="height" value="{{ \Auth::user()->height }}" required>
                        </div>
                    
                    
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <label for="weight">Peso (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight" value="{{ \Auth::user()->weight }}" required>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="bust">Busto (cm)</label>
                            <input type="number" class="form-control" id="bust" name="bust" value="{{ \Auth::user()->bust }}" >
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="waist">Cintura (cm)</label>
                            <input type="number" class="form-control" id="waist" name="waist" value="{{ \Auth::user()->waist }}" >
                        </div>
                        
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="hip">Cadera (cm)</label>
                            <input type="number" class="form-control" id="hip" name="hip" value="{{ \Auth::user()->hip }}" >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="start_day">Dia de Inicio</label>
                        <select class="form-control" id="start_day" name="start_day" required>
                            <option class="option" value="lunes" {{ \Auth::user()->start_day == 'lunes' ? 'selected' : '' }}>Lunes</option>
                            <option class="option" value="martes" {{ \Auth::user()->start_day == 'martes' ? 'selected' : '' }}>Martes</option>
                            <option class="option" value="miercoles" {{ \Auth::user()->start_day == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                            <option class="option" value="jueves" {{ \Auth::user()->start_day == 'jueves' ? 'selected' : '' }}>Jueves</option>
                            <option class="option" value="viernes" {{ \Auth::user()->start_day == 'viernes' ? 'selected' : '' }}>Viernes</option>
                            <option class="option" value="sabado" {{ \Auth::user()->start_day == 'sabado' ? 'selected' : '' }}>Sabado</option>
                            <option class="option" value="domingo" {{ \Auth::user()->start_day == 'domingo' ? 'selected' : '' }}>Domingo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="end_day">Dia de Fin</label>
                        <select class="form-control" id="end_day" name="end_day" required>
                            <option class="option" value="lunes" {{ \Auth::user()->end_day == 'lunes' ? 'selected' : '' }}>Lunes</option>
                            <option class="option" value="martes" {{ \Auth::user()->end_day == 'martes' ? 'selected' : '' }}>Martes</option>
                            <option class="option" value="miercoles" {{ \Auth::user()->end_day == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                            <option class="option" value="jueves" {{ \Auth::user()->end_day == 'jueves' ? 'selected' : '' }}>Jueves</option>
                            <option class="option" value="viernes" {{ \Auth::user()->end_day == 'viernes' ? 'selected' : '' }}>Viernes</option>
                            <option class="option" value="sabado" {{ \Auth::user()->end_day == 'sabado' ? 'selected' : '' }}>Sabado</option>
                            <option class="option" value="domingo" {{ \Auth::user()->end_day == 'domingo' ? 'selected' : '' }}>Domingo</option>
                        </select>
                    </div>
                
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="fulltime" name="fulltime_time" value="1" {{ old('fulltime_time') ? 'checked' : '' }}/>
                        <label class="form-check-label" for="fulltime">
                            ¿Horario 24h? (fulltime)
                        </label>  
                    </div>
                    
                    <div class="form-group @if(\Auth::user()->start_day == 'fulltime' && \Auth::user()->end_day == 'fulltime') d-none @endif" id="start_time_div">
                        <label for="start_time">Hora de Inicio</label>
                        <select class="form-control" id="start_time" name="start_time">
                            <option class="option" disabled {{ old('start_time') === null ? 'selected' : '' }} hidden>Selecciona una hora inicio</option>
                            @for ($i = 0; $i <= 23; $i++)
                                <option class="option" value="{{ $i }}" {{ old('start_time') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="form-group @if(\Auth::user()->start_day == 'fulltime' && \Auth::user()->end_day == 'fulltime') d-none @endif" id="end_time_div">
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
                        <input type="url" class="form-control" id="link" name="link" value="{{ \Auth::user()->link }}" placeholder="https://example.com" pattern="https://.*" size="80" />
                    </div>
    
                    <div class="text-center w-100">
                        <button class="btn btn-primary w-100" style="background:#f36e00!important; color:#fff; border: 2px solid white;" type="submit">
                            Finalizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        width: 80%;
        margin: 0 auto;
    }
    .form-control {
        background-color: white;
        border: 1px solid #ced4da;
        color: #495057;
    }
    .form-control:focus {
        background-color: white;
        border-color: #f36e00;
        color: #495057;
        box-shadow: none;
    }
    label {
        color: #495057;
    }
    .form-check-label {
        color: #495057;
    }
    .form-check-input:checked {
        background-color: #f36e00;
        border-color: #f36e00;
    }
    /* Estilo para el hover del checkbox */
    .form-check-input:hover {
        cursor: pointer;
        border-color: #f36e00;
    }
    /* Estilo para cuando el checkbox está enfocado */
    .form-check-input:focus {
        border-color: #f36e00;
        box-shadow: 0 0 0 0.25rem rgba(243, 110, 0, 0.25);
    }
</style>

@endsection