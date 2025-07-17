<div class="modal fade" id="cambiar-estado-cuenta-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Cambiar estado de cuenta {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.changeStatus', $u->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="status">Estado de cuenta</label>
                        <select name="status" id="status-{{ $u->id }}" class="form-control" onchange="document.getElementById('rejected-reason-{{ $u->id }}').style.display = this.value == 2 ? 'block' : 'none';">
                            <option value="1" {{ $u->active == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ $u->active == 2 ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="form-group" id="rejected-reason-{{ $u->id }}" style="display: {{ $u->active == 2 ? 'block' : 'none' }};">
                        <label for="rejected_reason">Motivo de rechazo</label>
                        <textarea name="rejected_reason" class="form-control">{{ old('rejected_reason', $u->rejected_reason) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->