<div class="modal fade" id="update-subcategory-{{$cat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Modificar Subcategoría {{$cat->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.subcategory.update', ['id' => $cat->id])}}">
                    @csrf

                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <select class="form-control" name="category" required>
                            <option selected hidden value="{{$cat->category->id}}">{{$cat->category->name}}</option>
                            @foreach ($categories as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
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
                        <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $cat->name }}" required>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Modificar</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

