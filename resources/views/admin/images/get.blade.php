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
                        <a style="color:red; font-size:20px;" href="{{ route('admin.images.deleteall', ['user' => $u->id]) }}">
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
                        @foreach ($images as $i=>$image)
                        
                            @php
                                $mimeType = \Storage::disk(\App\Helpers\StorageHelper::getDisk('images'))->mimeType($image->route);
                                $width = \App\Helpers\StorageHelper::getSize($image, 'images')["width"];
                                $height = \App\Helpers\StorageHelper::getSize($image, 'images')["height"];
                            @endphp
                            
                            <div @if($i > 0) class="mt-4" @endif style="max-width: 100%; display:flex; flex-direction:column; justify-content:center; align-items:center;">

                                @if($image->status == 'pending' && !is_null($image->vision_data) && $image->vision_data != [] && $image->vision_data != null && $image->vision_data != 0)
                                    @php
                                        $visionData = json_decode($image->vision_data, true);
                                        $nsfwScore = $visionData['nsfw'] ?? 0;
                                    @endphp
                                    @if($nsfwScore > 0.9997)
                                        <div style="background-color: red; color: white; padding: 5px; margin: 5px; text-align: center; font-weight: bold; border-radius: 3px;">
                                            NSFW 
                                            <a 
                                                title="Editar imagen {{$image->route}}" 
                                                class="p-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal-{{ $image->id }}"
                                                style="margin-left:5px; color: white; font-size: 14px; border-radius: 3px; display: inline-block; padding: 2px 8px; text-align: center; cursor:pointer; background: #000; text-decoration: none;">
                                                <i class="fa-solid fa-edit"></i> Editar
                                            </a>
                                        </div>

                                        <!-- Modal de edición -->
                                        <div class="modal fade" id="editModal-{{ $image->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editor de Imagen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="color: white;"></button>
                                                </div>
                                                <div class="modal-body text-center p-0" style="height: auto; display: flex; justify-content: center; align-items: center;">
                                                    <canvas id="canvas-{{ $image->id }}" style="border: 1px solid #ccc; max-width: 100%; height: auto; display: block;"></canvas>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button class="btn btn-primary w-100" style="background: #f36e00; color: white; border: none;" onclick="saveBlur('{{ $image->id }}')">Guardar</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                const imageId = "{{ $image->id }}";
                                                const imagePath = "{{ route('admin.images.get', ['filename' => $image->route]) }}";

                                                const canvas = document.getElementById("canvas-" + imageId);
                                                canvas.style.cursor = 'crosshair'; // Cambiar cursor al pasar sobre el canvas
                                                const ctx = canvas.getContext("2d");
                                                let drawing = false;
                                                const blurSize = 20;

                                                const img = new Image();
                                                img.crossOrigin = "Anonymous";
                                                img.src = imagePath;

                                                img.onload = function () {
                                                    canvas.width = img.width;
                                                    canvas.height = img.height;
                                                    ctx.drawImage(img, 0, 0);
                                                };

                                                canvas.addEventListener("mousedown", () => drawing = true);
                                                canvas.addEventListener("mouseup", () => drawing = false);
                                                canvas.addEventListener("mousemove", (e) => {
                                                    if (!drawing) return;
                                                    const rect = canvas.getBoundingClientRect();

                                                    const scaleX = canvas.width / rect.width;
                                                    const scaleY = canvas.height / rect.height;

                                                    const x = (e.clientX - rect.left) * scaleX;
                                                    const y = (e.clientY - rect.top) * scaleY;

                                                    const imageData = ctx.getImageData(x - blurSize/2, y - blurSize/2, blurSize, blurSize);
                                                    let r = 0, g = 0, b = 0;
                                                    for (let i = 0; i < imageData.data.length; i += 4) {
                                                        r += imageData.data[i];
                                                        g += imageData.data[i + 1];
                                                        b += imageData.data[i + 2];
                                                    }
                                                    const pixels = imageData.data.length / 4;
                                                    r /= pixels; g /= pixels; b /= pixels;
                                                    for (let i = 0; i < imageData.data.length; i += 4) {
                                                        imageData.data[i] = r;
                                                        imageData.data[i + 1] = g;
                                                        imageData.data[i + 2] = b;
                                                    }
                                                    ctx.putImageData(imageData, x - blurSize/2, y - blurSize/2);
                                                });
                                            });

                                            function saveBlur(imageId) {
                                                const canvas = document.getElementById("canvas-" + imageId);
                                                const dataURL = canvas.toDataURL("image/jpeg");

                                                const saveBlurUrl = "{{ route('admin.images.saveBlur', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', imageId);

                                                fetch(saveBlurUrl, {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                                    },
                                                    body: JSON.stringify({ image: dataURL })
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    alert("Imagen guardada.");
                                                    // Opcional: recarga la imagen en la página
                                                    // location.reload();
                                                })
                                                .catch(err => {
                                                    console.error(err);
                                                    alert("Error al guardar la imagen.");
                                                });
                                            }
                                        </script>

                                    @endif
                                @endif
                                
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

                                    @if(!is_null($image->vision_data))
                                    <a title="Ver datos Vision IA" href="javascript:void(0);" data-toggle="modal" data-target="#datos-vision-ia-{{ $image->id }}" class="p-2" style="margin-left:10px; color: white; font-size: 22px; border-radius: 9999px; display: block; width: 50px; text-align: center; background: #555;">
                                        <i class="fa-solid fa-database"></i>
                                    </a>
                                    @include('modals.admin.fotos.modal_ver_datos_vision_ia')
                                    @endif
                                </div>
                            </div>
                        @endforeach

                                
                        <div class="mt-4">
                            {{ $images->links('pagination::bootstrap-4') }}
                        </div>
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
        const maxFiles = 10;

        // Filter files based on size limit
        const validFiles = Array.from(files).filter(file => file.size <= maxSizeInBytes);

        // Notify user about files that exceed size limit
        if (files.length !== validFiles.length) {
            const invalidFiles = Array.from(files).filter(file => file.size > maxSizeInBytes);
            const invalidFileNames = invalidFiles.map(file => file.name).join(', ');
            alert('Estos ficheros exceden el límite de 2MB y han sido borrados: ' + invalidFileNames);
        }

        // Further filter to ensure maximum 10 files
        let finalFiles = validFiles.slice(0, maxFiles);

        // Notify user if some files were removed due to exceeding the max file count
        if (validFiles.length > maxFiles) {
            const removedFiles = validFiles.slice(maxFiles).map(file => file.name).join(', ');
            alert('Solo se permiten un máximo de 10 archivos. Estos han sido removidos: ' + removedFiles);
        }

        // Create a new DataTransfer object to hold the valid files
        const dataTransfer = new DataTransfer();
        finalFiles.forEach(file => dataTransfer.items.add(file));

        // Assign valid files back to the input element
        inputFile.files = dataTransfer.files;
    });
</script>


@endsection