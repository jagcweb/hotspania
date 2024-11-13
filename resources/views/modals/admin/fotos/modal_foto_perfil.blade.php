<div class="modal fade" id="perfil-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Foto perfil {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.images.uploadProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="image">Imagen de perfil</label>
                        <input type="file" class="form-control" id="image" name="image" accept=".jpeg,.png,.jpg,.gif,.webp">
                        <input type="text" name="user_id" value="{{$u->id}}" hidden/>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir imagen</button>
                </form>
                @if(!is_null($u->profile_image))
                <p>Imagen actual:</p>
                <img src="{{ route('admin.images.get', ['filename' => $u->profile_image]) }}" alt="Foto actual" class="img-fluid" width="200" />
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->