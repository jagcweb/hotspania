<div class="modal fade" id="eliminar-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Eliminar fotos {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @php $all_images = \App\Models\Image::where('user_id', $u->id)->get(); @endphp

                
                @if(count($all_images) > 0)
                <p class="w-100 text-center"><a style="color:red; font-size:20px;" href="{{ route('admin.images.deleteall', ['user' => $u->id]) }}">Borrar todas</a></p>
                   @foreach ($all_images as $i=>$image)
                    <div @if($i > 0) class="mt-4" @endif style="max-width: 100%; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                        <img class="p-2" src="{{ route('admin.images.get', ['filename' => $image->route]) }}" width="200" height="200"/>
                        <div style="max-width: 200px; display:flex; flex-direction:row; justify-content:center; align-items:center;">
                            @switch($image->status)
                                @case('pending')
                                    <span style="color:orange;">Pendiente</span>
                                @break   
                                @case('approved')
                                    <span style="color:green;">Aprobada</span>
                                @break   

                                @case('unapproved')
                                    <span style="color:red;">No aprobada</span>
                                @break   
                            @endswitch
                            <a title="Borrar imagen {{$image->route}}" href="{{ route('admin.images.delete', ['image' => $image->id]) }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: red;">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                   @endforeach
                @else
                    <p>No hay fotos sin aprobar.</p>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->