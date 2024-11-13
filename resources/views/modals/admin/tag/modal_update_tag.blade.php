<div class="modal fade parentmodal" id="update-tag-{{$t->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar tag {{ $t->name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.utilities.tags_update') }}">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{ $t->id }}">
                        <label for="tag" class="form-label">Nombre del tag</label>
                        <input type="text" class="form-control" id="tag" aria-describedby="tag" name="name" placeholder="asdf..." value="{{ $t->name }}">
                    </div>
                        <button type="submit" class="btn">Actualizar</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->