@extends('layouts.admin')

@section('title') Imagenes de {{ $name }} - ({{ $filter }}) @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Imagenes de <b>{{ $name }}</b> ({{ $filter }})</h5>

            <div class="mt-3" style="width:100%; display:flex; flex-direction:row; justify-content:center;">
                @php $u = \App\Models\User::find($id); @endphp
                <a style="color: #000; text-align:center; border: 1px solid #000; padding:10px;" href="#" data-toggle="modal" data-target="#subir-fotos-{{$u->id}}">Subir fotos</a>
                <a class="ml-2" style="color: #000; text-align:center; border: 1px solid #000; padding:10px;" href="{{ route('admin.images.getFilter', ['id'=> $id, 'name' => $name, 'filter' => 'aprobadas']) }}">Aprobadas</a>
                <a class="ml-2" style="color: #000; text-align:center; border: 1px solid #000; padding:10px;" href="{{ route('admin.images.getFilter', ['id'=> $id, 'name' => $name, 'filter' => 'desaprobadas']) }}">Rechazadas</a>
                <a class="ml-2" style="color: #000; text-align:center; border: 1px solid #000; padding:10px;"  href="{{ route('admin.images.getFilter', ['id'=> $id, 'name' => $name, 'filter' => 'pendientes']) }}">Pendientes</a>
                <a class="ml-2" style="color: #000; text-align:center; border: 1px solid #000; padding:10px;"  href="{{ route('admin.images.getFilter', ['id'=> $id, 'name' => $name, 'filter' => 'visibles']) }}">Visibles</a>
                <a class="ml-2" style="color: #000; text-align:center; border: 1px solid #000; padding:10px;"  href="{{ route('admin.images.getFilter', ['id'=> $id, 'name' => $name, 'filter' => 'ocultas']) }}">Ocultas</a>
            </div>

            @include('modals.admin.fotos.modal_subir_fotos')


            <div class="card-body">
                @if(count($images) > 0)
                    <p class="w-100 text-center">
                        <a style="color:green; font-size:20px;" href="{{ route('admin.images.approveall', ['user' => $u->id]) }}">
                            Aprobar todas
                        </a>
                    </p>

                    <p class="w-100 text-center">
                        <a style="color:red; font-size:20px;" href="{{ route('admin.images.unapproveall', ['user' => $u->id]) }}">
                            Desaprobar todas
                        </a>
                    </p>

                    <p class="w-100 text-center">
                        <a style="color:red; font-size:20px;" href="{{ route('admin.images.approveall', ['user' => $u->id]) }}">
                            Borrar todas
                        </a>
                    </p>

                    <p class="w-100 text-center">
                        <a style="color:black; font-size:20px;" href="{{ route('admin.images.visibleall', ['user' => $u->id]) }}">
                            Aplicar visible a todas
                        </a>
                    </p>

                    <p class="w-100 text-center">
                        <a style="color:black; font-size:20px;" href="{{ route('admin.images.invisibleall', ['user' => $u->id]) }}">
                            Aplicar oculto a todas
                        </a>
                    </p>
                    <div style="max-width: 100%; display:flex; flex-direction:row; justify-content:space-around; align-items:center; flex-wrap: wrap;">
                        @php $portada = \App\Models\Image::where('user_id', $id)->whereNotNull('frontimage')->first(); @endphp
                        @foreach ($images as $i=>$image)
                        
                            @php
                                $mimeType = \Storage::disk('images')->mimeType($image->route);
                                list($width, $height) = getimagesize(\Storage::disk('images')->path($image->route));
                            @endphp
                            
                            <div @if($i > 0) class="mt-4" @endif style="max-width: 100%; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                
                                @if ($mimeType && strpos($mimeType, 'image/') === 0)
                                    <a href="{{ asset('storage/images/'.$image->route) }}" target="_blank">
                                        <img class="p-2" src="{{ route('admin.images.get', ['filename' => $image->route]) }}" width="200"/>
                                    </a>
                                @elseif ($mimeType && strpos($mimeType, 'video/') === 0)
                                    <div ondblclick="openVideo('{{ asset('storage/images/' . $image->route) }}')" style="cursor: pointer;">
                                        <video crossorigin="anonymous" controls style="height: 200px;">
                                            <source src="{{ route('admin.images.get', ['filename' => $image->route]) }}" type="{{ $mimeType }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    @if(!is_null($image->route_gif))
                                    <a href="{{ asset('storage/videogif/'.$image->route_gif) }}" target="_blank">
                                        <img class="p-2" src="{{ route('admin.images.get_gif', ['filename' => $image->route_gif]) }}" width="200"/>
                                    </a>
                                    @endif
                                @endif
                                <div style="max-width: 200px; display:flex; flex-direction:row; justify-content:center; align-items:center;">
                                    @if($filter === 'pendientes' || $filter === 'desaprobadas')
                                    <a title="Aprobar imagen {{$image->route}}" href="{{ route('admin.images.approve', ['image' => $image->id]) }}" class="p-2" style="color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; margin-right: 20px; background: green;">
                                        <i class="fa-solid fa-check"></i>
                                    </a>
                                    @endif
                                    
                                    @if($filter === 'aprobadas')
                                    <a title="Rechazar imagen {{$image->route}}" href="{{ route('admin.images.unapprove', ['image' => $image->id]) }}" class="p-2" style="color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: red;">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                    @endif
                                    <a title="Borrar imagen {{$image->route}}" href="{{ route('admin.images.delete', ['image' => $image->id]) }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: red;">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>

                                    @if(is_null($image->visible))
                                    <a title="Visualizar {{$image->route}}" href="{{ route('admin.images.visible', ['image' => $image->id]) }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: #111;">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    @endif

                                    @if(!is_null($image->visible))
                                    <a title="Ocultar {{$image->route}}" href="{{ route('admin.images.invisible', ['image' => $image->id]) }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: #aaa;">
                                        <i class="fa-regular fa-eye-slash"></i>
                                    </a>
                                    @endif

                                    @if($image->frontimage === 1)
                                    <a title="Foto asignada a portada {{$image->route}}" href="javascript:void(0)" class="p-2" style="cursor:default;margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: #111;">
                                        <i class="fa-regular fa-image"></i>
                                    </a>
                                    @endif

                                    @if($image->status === 'approved' && !is_null($image->visible) && is_null($image->frontimage) && !is_null($height) && $height > $width)
                                    <a title="Asignar como portada {{$image->route}}" href="{{ route('admin.images.setfront', ['image' => $image->id]) }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: #aaa;">
                                        <i class="fa-regular fa-image"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    @if($filter === 'pendientes')
                        <p class="w-100 text-center">No hay fotos pendientes por aprobar.</p>
                    @endif

                    @if($filter === 'aprobadas')
                        <p class="w-100 text-center">No hay fotos aprobadas.</p>
                    @endif

                    @if($filter === 'desaprobadas')
                        <p class="w-100 text-center">No hay fotos desaprobadas.</p>
                    @endif

                    @if($filter === 'visibles')
                        <p class="w-100 text-center">No hay fotos visibles.</p>
                    @endif

                    @if($filter === 'visibles')
                        <p class="w-100 text-center">No hay fotos ocultas.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const inputFile = document.querySelector('.image_upload');

    inputFile.addEventListener('change', (event) => {
        let files = event.target.files;
        const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB in bytes
        const maxFiles = 5;

        // Filter files based on size limit
        const validFiles = Array.from(files).filter(file => file.size <= maxSizeInBytes);

        // Notify user about files that exceed size limit
        if (files.length !== validFiles.length) {
            const invalidFiles = Array.from(files).filter(file => file.size > maxSizeInBytes);
            const invalidFileNames = invalidFiles.map(file => file.name).join(', ');
            alert('Estos ficheros exceden el límite de 2MB y han sido borrados: ' + invalidFileNames);
        }

        // Further filter to ensure maximum 5 files
        let finalFiles = validFiles.slice(0, maxFiles);

        // Notify user if some files were removed due to exceeding the max file count
        if (validFiles.length > maxFiles) {
            const removedFiles = validFiles.slice(maxFiles).map(file => file.name).join(', ');
            alert('Solo se permiten un máximo de 5 archivos. Estos han sido removidos: ' + removedFiles);
        }

        // Create a new DataTransfer object to hold the valid files
        const dataTransfer = new DataTransfer();
        finalFiles.forEach(file => dataTransfer.items.add(file));

        // Assign valid files back to the input element
        inputFile.files = dataTransfer.files;
    });
</script>


@endsection