<div class="modal fade" id="create-subcategory" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Crear Subcategoría</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.subcategory.create')}}">
                    @csrf

                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <select class="form-control" name="category" required>
                            <option disabled selected hidden>Selecciona una categoría...</option>
                            @foreach ($categories as $cat)
                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>

                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Cazoleta">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Crear</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

