<div class="modal fade" id="aprobar-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Aprobar fotos {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @php $images_pending = \App\Models\Image::where('status', 'pending')->get(); @endphp

                @if(count($images_pending) > 0)
                 <p class="w-100 text-center"><a style="color:green; font-size:20px;" href="{{ route('admin.images.approveall', ['user' => $u->id]) }}">Aprobar todas</a></p>
                   @foreach ($images_pending as $i=>$image)
                    @php
                        $mimeType = \Storage::disk('images')->mimeType($image->route);
                    @endphp
                    <div @if($i > 0) class="mt-4" @endif style="max-width: 100%; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                        
                        @if ($mimeType && strpos($mimeType, 'image/') === 0)
                        <a href="{{ asset('storage/images/'.$image->route) }}" target="_blank">
                            <img class="p-2" src="{{ route('admin.images.get', ['filename' => $image->route]) }}" width="200"/>
                        </a>
                        @elseif ($mimeType && strpos($mimeType, 'video/') === 0)
                        <div ondblclick="openVideo('{{ asset('storage/images/' . $image->route) }}')" style="cursor: pointer;">
                            <video controls style="height: 200px;">
                                <source src="{{ route('admin.images.get', ['filename' => $image->route]) }}" type="{{ $mimeType }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        @endif
                        <div style="max-width: 200px; display:flex; flex-direction:row; justify-content:center; align-items:center;">
                            <a title="Aprobar imagen {{$image->route}}" href="{{ route('admin.images.approve', ['image' => $image->id]) }}" class="p-2" style="color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; margin-right: 20px; background: green;">
                                <i class="fa-solid fa-check"></i>
                            </a>
                            <a title="Rechazar imagen {{$image->route}}" href="{{ route('admin.images.unapprove', ['image' => $image->id]) }}" class="p-2" style="color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: red;">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>
                    </div>
                   @endforeach
                @else
                    <p>No hay fotos pendientes por aprobar.</p>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->