<div class="modal fade" id="editar-status-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Editar status {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{ route('admin.users.update_status') }}" autocomplete="off">
                    @csrf
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" @if($u->active == 1) checked @endif/>
                        <label class="form-check-label" for="active">
                            ¿Cuenta activa?
                        </label>  
                    </div>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="frozen" name="frozen" value="1" @if($u->frozen == 1) checked @endif/>
                        <label class="form-check-label" for="frozen">
                            ¿Cuenta congelada?
                        </label>  
                    </div>
        
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="visible" name="visible" value="1" @if($u->visible == 1) checked @endif/>
                        <label class="form-check-label" for="visible">
                            ¿Cuenta visible?
                        </label>  
                    </div>

                    <input type="text" hidden name="user_id" value="{{$u->id}}" />

                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" style="line-height: 10px;" value="Actualizar"/>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->