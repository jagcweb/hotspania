<div class="modal fade" id="aprobadas-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Fotos Aprobadas {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @php $images_approved = \App\Models\Image::where('user_id', $u->id)->where('status', 'approved')->get(); @endphp

                
                @if(count($images_approved) > 0)
                   @foreach ($images_approved as $i=>$image)
                    <div @if($i > 0) class="mt-4" @endif style="max-width: 100%; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                        <img class="p-2" src="{{ route('admin.images.get', ['filename' => $image->route]) }}" width="200" height="200"/>
                        <div style="max-width: 200px; display:flex; flex-direction:row; justify-content:center; align-items:center;">
                            <a title="Rechazar imagen {{$image->route}}" href="{{ route('admin.images.unapprove', ['image' => $image->id]) }}" class="p-2" style="color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: red;">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>
                    </div>
                   @endforeach
                @else
                    <p>No hay fotos aprobadas.</p>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->