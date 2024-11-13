<div class="modal fade parentmodal" id="update-zone-{{$z->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar zona {{ $z->name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.utilities.zones_update') }}">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{ $z->id }}">
                        <label for="zone" class="form-label">Nombre de la zona</label>
                        <input type="text" class="form-control" id="zone" aria-describedby="zone" name="name" placeholder="asdf..." value="{{ $z->name }}">
                    </div>

                    <div class="form-group">
                        <label for="zone" class="form-label">Ciudad asociada</label>
                        <input type="text" class="form-control" id="zone" aria-describedby="zone" name="name" placeholder="asdf..." value="{{ucfirst($z->city->name)}}" disabled style="background: #ccc!important; cursor:not-allowed;">
                    </div>
                        <button type="submit" class="btn">Actualizar</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->