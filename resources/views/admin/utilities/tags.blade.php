@extends('layouts.admin')

@section('title') Tags @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Tags</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <form method="POST" action="{{ route('admin.utilities.tags_save') }}">
                            @csrf
                            <div class="form-group">
                                <label for="tag" class="form-label">Nombre del tag</label>
                                <input type="text" class="form-control" id="tag" aria-describedby="tag" name="name" placeholder="asdf...">
                            </div>
                              <button type="submit" class="btn">Guardar</button>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <table class="table mt-4">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $t)
                                    <tr>
                                        <td>{{$t->id}}</td>
                                        <td>{{$t->name}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#update-tag-{{$t->id}}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="{{ route('admin.utilities.tags_delete', ['id' => $t->id]) }}" style="font-size: 18px; color:black;">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('modals.admin.tag.modal_update_tag')
                                @endforeach
                            </tbody>
                        </table>
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