<div class="modal fade" id="update-discount-{{$disc->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar Descuento {{$disc->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.discount.update', ['id' => $disc->id])}}" enctype='multipart/form-data'>
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$disc->name}}" required placeholder="ASDFG2023">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="discount">% Descuento</label>
                        <input type="number" min="1" max="100" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{$disc->discount}}" required placeholder="10">

                        @error('discount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="uses">Usos</label>
                        <input type="number" min="1" class="form-control @error('uses') is-invalid @enderror" name="uses" value="{{$disc->uses}}" required placeholder="120">

                        @error('uses')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expiration_date">Fecha Caducidad</label>
                        <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" name="expiration_date" value="{{\Carbon\Carbon::parse($disc->expiration_date)->format('Y-m-d')}}">

                        @error('expiration_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Actualizar</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->