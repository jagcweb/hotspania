<div class="modal fade" id="cambiar-contraseña-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Modificar contraseña {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.users.update_password', ['id' => $u->id])}}">
                    @csrf
                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" class="form-control" minlength="8" name="password" placeholder="*****************" required/>
                    </div>


                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" style="line-height: 10px;" value="Cambiar contraseña"/>

                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->