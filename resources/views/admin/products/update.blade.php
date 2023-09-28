<div class="modal fade" id="update-product-{{$pro->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Modificar Producto {{$pro->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.product.update', ['id' => $pro->id])}}" enctype='multipart/form-data'>
                    @csrf

                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <select class="form-control category" name="category" required>
                            <option selected hidden value="{{$pro->category->id}}">{{$pro->category->name}}</option>
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

                    <div class="form-group subcategory_div d-none">
                        <label for="category">Subcategoría</label>
                        <select class="form-control subcategory" name="subcategory">
                            @if(!is_null($pro->subcategory_id))
                                <option selected hidden value="{{$pro->subcategory->id}}">{{$pro->subcategory->name}}</option>
                            @endif
                        </select>

                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $pro->name }}" required placeholder="Diavla Mini">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <input type="name" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $pro->description }}" required placeholder=".....">

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price">Precio</label>
                        <input type="number" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $pro->price }}" required placeholder="15.50">

                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="units">Unidades</label>
                        <input type="number" min="1" class="form-control @error('units') is-invalid @enderror" name="units" value="{{ $pro->units }}" required placeholder="120">

                        @error('units')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="discontinued">Descontinuado (Deshabilitar)</label>
                        <input type="checkbox" class="form-control @error('discontinued') is-invalid @enderror" name="discontinued" value="1" @if(!is_null($pro->discontinued)) checked @endif>

                        @error('discontinued')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="images">Imágen/Imágenes</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" name="images[]" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" multiple>

                        @error('images')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <p class="mt-2">Imagenes actuales:</p>
                    @foreach (json_decode($pro->images, true) as $im)
                        <img width="200" src="{{url('/get-image', ['filesystem' => 'products', 'filename' => $im])}}"/>
                    @endforeach
                    

                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Modificar</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- jQuery -->
<script src="{{ asset('js/jquery-2.1.0.min.js') }}"></script>
<script>
$(document).ready(function() {
    $(".category").on("change", function() {
        const category_id = $(this).val();
        $(".subcategory").empty();
        if(!isNaN(category_id)){
            $.ajax({
                url: '{{url('admin/get-subcategories/')}}' + '/' + category_id,
                method: 'GET',
            }).done(function (res) {
                const subcategories = JSON.parse(res);
                if(subcategories.length > 0){
                    $(".subcategory_div").removeClass('d-none');
                    $(".subcategory").append(`<option disabled selected hidden>Selecciona una subcategoría...</option>`)
                    subcategories.map((sub) => {
                        $(".subcategory").append(`<option value="${sub.id}">${sub.name}</option>`)
                    });
                } else {
                    $(".subcategory_div").addClass('d-none');
                }

            });
        }

    });
});
</script>
