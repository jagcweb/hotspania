@extends('layouts.app')

@section('title') Mi Cuenta @endsection

@section('content')
<section class="section" id="product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="px-4 px-lg-0">

                    <div class="pb-5">
                        <form method="POST" autocomplete="off" action="{{ route('account.update') }}">
                            @csrf
                            <div class="container append">
                                <div class="row py-5 p-4 bg-white rounded"
                                    style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
                                    <div class="col-lg-12">
                                        <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Editar datos de mi cuenta
                                        </div>
                                        <div class="p-4">
                                            <div class="form-group">
                                                <label for="name">Nombre</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ \Auth::user()->name }}" required placeholder="Pedro">

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="surname">Apellidos</label>
                                                <input type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ \Auth::user()->surname }}" required placeholder="Garcia Tena">

                                                @error('surname')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="email">E-mail</label>
                                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ \Auth::user()->email }}" required placeholder="Garcia Tena">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class='form-group mt-2'>
                                                <button class="btn-input w-100">Actualizar datos</button>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form method="POST" autocomplete="off" class="mt-5" action="{{ route('account.update_password') }}">
                            @csrf
                            <div class="container append">
                                <div class="row py-5 p-4 bg-white rounded"
                                    style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;">
                                    <div class="col-lg-12">
                                        <div class="bg-light px-4 py-3 text-uppercase font-weight-bold">Cambiar contraseña
                                        </div>
                                        <div class="p-4">
                                            <div class="form-group">
                                                <label for="current">Contraseña actual</label>
                                                <input type="password" class="form-control @error('current') is-invalid @enderror" name="current" required placeholder="********">

                                                @error('current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-2">
                                                <label for="new">Nueva Contraseña</label>
                                                <input type="password" class="form-control @error('new') is-invalid @enderror" name="new" required placeholder="********">

                                                @error('new')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class='form-group mt-2'>
                                                <button class="btn-input w-100">Modificar contraseña</button>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection