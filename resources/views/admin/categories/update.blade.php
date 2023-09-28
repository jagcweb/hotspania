<div class="modal fade" id="update-category-{{$cat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Modificar Categoría {{$cat->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.category.update', ['id' => $cat->id])}}" enctype='multipart/form-data'>
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $cat->name }}" required>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <input type="name" class="form-control @error('description') is-invalid @enderror" name="description"  value="{{ $cat->description }}" required>

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Imagen de portada</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <p class="mt-2">Imagen actual:</p>
                        <img width="200" src="{{url('/get-image', ['filesystem' => 'categories', 'filename' => $cat->image])}}"/>
                    </div>
                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Modificar</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

