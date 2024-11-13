<div class="modal fade" id="subir-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Subir fotos {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="image">Imágenes o vídeos</label>
                        <input type="file" class="form-control image_upload" id="image" name="images[]" multiple accept=".jpeg,.png,.jpg,.gif,.webp,.mp4,.mov,.avi,.wmv,.avchd,.webm,.flv">
                        <input type="text" name="user_id" value="{{$u->id}}" hidden/>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir imágenes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->