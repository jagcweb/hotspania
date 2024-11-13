<div class="modal fade parentmodal" id="update-city-{{$c->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar ciudad {{ $c->name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.utilities.cities_update') }}">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{ $c->id }}">
                        <label for="city" class="form-label">Nombre de la ciudad</label>
                        <input type="text" class="form-control" id="city" aria-describedby="city" name="name" placeholder="asdf..." value="{{ $c->name }}">
                    </div>
                        <button type="submit" class="btn">Actualizar</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->