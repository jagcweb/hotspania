@extends('layouts.admin')

@section('title') Cambiar de Ciudad @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Cambiar de ciudad</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" action="{{ route('admin.citychanges.apply') }}" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="zone" class="form-label">Ciudad</label>
                                <select class="form-control" id="city_id" name="city" required>
                                    <option hidden selected disabled>
                                        Escoge una ciudad...
                                    </option>
                                    <option value="todas">Todas</option>
                                    @foreach($cities as $c)
                                        <option value="{{strtolower($c->name)}}">{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p>Ciudad Actual: {{ !is_null(\Cookie::get('city')) ? ucfirst(\Cookie::get('city')) : 'Ninguna ciudad seleccionada. Por defecto: Barcelona' }}</p>
                              <button type="submit" class="btn">Aplicar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
<!-- Bootstrap Tables js -->

@endsection

@section('css')


@endsection