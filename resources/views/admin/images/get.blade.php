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
                    <div class="mt-3" style="width:100%; display:flex; flex-direction:row; justify-content:center;">
                        <a style="color:green; text-align:center; border: 1px solid green; padding:10px;" href="{{ route('admin.images.approveall', ['user' => $u->id]) }}">
                            Aprobar todas
                        </a>
                        <a class="ml-2" style="color:red; text-align:center; border: 1px solid red; padding:10px;" href="{{ route('admin.images.unapproveall', ['user' => $u->id]) }}">
                            Desaprobar todas
                        </a>
                        <a class="ml-2" style="color:red; text-align:center; border: 1px solid red; padding:10px;" href="{{ route('admin.images.deleteall', ['user' => $u->id]) }}">
                            Borrar todas
                        </a>
                        <a class="ml-2" style="color:black; text-align:center; border: 1px solid #000; padding:10px;" href="{{ route('admin.images.visibleall', ['user' => $u->id]) }}">
                            Aplicar visible a todas
                        </a>
                        <a class="ml-2" style="color:black; text-align:center; border: 1px solid #000; padding:10px;" href="{{ route('admin.images.invisibleall', ['user' => $u->id]) }}">
                            Aplicar oculto a todas
                        </a>
                        <a class="ml-2" style="color:black; text-align:center; border: 1px solid #000; padding:10px;" href="{{ route('admin.images.download', ['id' => $u->id]) }}">
                           Descargar todas
                        </a>
                    </div>
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
                                    @if($nsfwScore >= 0.999)
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
                                            canvas.style.cursor = 'crosshair';
                                            const ctx = canvas.getContext("2d");
                                            const img = new Image();
                                            img.crossOrigin = "Anonymous";
                                            img.src = imagePath;

                                            let isDragging = false;
                                            let isMoving = false;
                                            let startX = 0;
                                            let startY = 0;
                                            let currentX = 0;
                                            let currentY = 0;
                                            let radius = 0;
                                            let blurMenu;
                                            let savedImage;
                                            let originalImageData = null;
                                            let circleSelected = false;
                                            let hue = 0;
                                            let undoStack = []; // Stack para deshacer cambios
                                            const maxUndoSteps = 20; // Máximo número de pasos a guardar

                                            img.onload = function () {
                                                canvas.width = img.width;
                                                canvas.height = img.height;
                                                ctx.drawImage(img, 0, 0);
                                                savedImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                originalImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                // Guardar el estado inicial en el stack de deshacer
                                                saveStateToUndoStack();
                                            };

                                            function saveStateToUndoStack() {
                                                const currentState = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                undoStack.push(currentState);
                                                
                                                // Limitar el tamaño del stack
                                                if (undoStack.length > maxUndoSteps) {
                                                    undoStack.shift();
                                                }
                                            }

                                            function undo() {
                                                if (undoStack.length > 1) {
                                                    // Remover el estado actual
                                                    undoStack.pop();
                                                    // Obtener el estado anterior
                                                    const previousState = undoStack[undoStack.length - 1];
                                                    ctx.putImageData(previousState, 0, 0);
                                                    savedImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                    
                                                    // Ocultar círculo actual
                                                    radius = 0;
                                                    circleSelected = false;
                                                    drawSelectionCircle();
                                                }
                                            }

                                            function getMousePos(e) {
                                                const rect = canvas.getBoundingClientRect();
                                                const scaleX = canvas.width / rect.width;
                                                const scaleY = canvas.height / rect.height;
                                                return {
                                                    x: (e.clientX - rect.left) * scaleX,
                                                    y: (e.clientY - rect.top) * scaleY
                                                };
                                            }

                                            function isInsideCircle(x, y) {
                                                const dx = x - startX;
                                                const dy = y - startY;
                                                return Math.sqrt(dx * dx + dy * dy) <= radius;
                                            }

                                            canvas.addEventListener("mousedown", function (e) {
                                                if (e.button === 0) {
                                                    const pos = getMousePos(e);
                                                    if (radius > 0 && isInsideCircle(pos.x, pos.y)) {
                                                        isMoving = true;
                                                        circleSelected = true;
                                                    } else {
                                                        // Guardar el estado actual antes de crear un nuevo círculo
                                                        savedImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                        startX = pos.x;
                                                        startY = pos.y;
                                                        currentX = pos.x;
                                                        currentY = pos.y;
                                                        isDragging = true;
                                                        circleSelected = false;
                                                        drawSelectionCircle();
                                                    }
                                                }
                                            });

                                            canvas.addEventListener("mousemove", function (e) {
                                                const pos = getMousePos(e);

                                                if (isDragging) {
                                                    currentX = pos.x;
                                                    currentY = pos.y;
                                                    radius = Math.sqrt(Math.pow(currentX - startX, 2) + Math.pow(currentY - startY, 2));
                                                    drawSelectionCircle();
                                                }

                                                if (isMoving) {
                                                    startX = pos.x;
                                                    startY = pos.y;
                                                    drawSelectionCircle();
                                                }
                                            });

                                            canvas.addEventListener("mouseup", function () {
                                                isDragging = false;
                                                isMoving = false;
                                            });

                                            canvas.addEventListener("contextmenu", function (e) {
                                                e.preventDefault();
                                                if (blurMenu) blurMenu.remove();
                                                showBlurMenu(e.pageX, e.pageY);
                                            });

                                            function drawSelectionCircle() {
                                                ctx.putImageData(savedImage, 0, 0);
                                                if (radius > 0) {
                                                    ctx.beginPath();
                                                    ctx.arc(startX, startY, radius, 0, Math.PI * 2);
                                                    ctx.lineWidth = 4;

                                                    if (circleSelected) {
                                                        const gradient = ctx.createLinearGradient(startX - radius, startY - radius, startX + radius, startY + radius);
                                                        for (let i = 0; i <= 1; i += 0.1) {
                                                            gradient.addColorStop(i, `hsl(${(hue + i * 360) % 360}, 100%, 50%)`);
                                                        }
                                                        ctx.strokeStyle = gradient;
                                                    } else {
                                                        ctx.strokeStyle = 'red';
                                                    }

                                                    ctx.stroke();
                                                }
                                            }

                                            function showBlurMenu(x, y) {
                                                blurMenu = document.createElement("div");
                                                blurMenu.style.position = "absolute";
                                                blurMenu.style.left = x + "px";
                                                blurMenu.style.top = y + "px";
                                                blurMenu.style.background = "#fff";
                                                blurMenu.style.border = "1px solid #ccc";
                                                blurMenu.style.padding = "5px";
                                                blurMenu.style.zIndex = 9999;

                                                for (let i = 1; i <= 10; i++) {
                                                    const option = document.createElement("div");
                                                    option.innerText = "Desenfoque nivel " + i;
                                                    option.style.cursor = "pointer";
                                                    option.style.padding = "2px 10px";
                                                    option.onmouseover = () => option.style.background = "#eee";
                                                    option.onmouseout = () => option.style.background = "#fff";

                                                    option.onclick = () => {
                                                        // Primero ocultar el círculo completamente
                                                        const tempRadius = radius;
                                                        const tempStartX = startX;
                                                        const tempStartY = startY;
                                                        
                                                        radius = 0;
                                                        circleSelected = false;
                                                        ctx.putImageData(savedImage, 0, 0); // Restaurar imagen sin círculo
                                                        
                                                        // Ahora aplicar el blur con los valores originales
                                                        applyCircularBlur(tempStartX, tempStartY, tempRadius, i);
                                                        blurMenu.remove();
                                                        
                                                        // Guardar estado después del blur
                                                        saveStateToUndoStack();
                                                        savedImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                    };
                                                    blurMenu.appendChild(option);
                                                }

                                                document.body.appendChild(blurMenu);
                                            }

                                            function applyCircularBlur(cx, cy, r, level) {
                                                // Obtener los datos de la región circular con padding para el blur
                                                const kernelSize = Math.max(3, level * 3); // Aumentar tamaño del kernel
                                                const padding = Math.floor(kernelSize / 2);
                                                const x = Math.max(0, Math.floor(cx - r - padding));
                                                const y = Math.max(0, Math.floor(cy - r - padding));
                                                const width = Math.min(canvas.width - x, Math.ceil(r * 2) + padding * 2);
                                                const height = Math.min(canvas.height - y, Math.ceil(r * 2) + padding * 2);
                                                
                                                const imageData = ctx.getImageData(x, y, width, height);
                                                const data = imageData.data;
                                                const newData = new Uint8ClampedArray(data.length);
                                                
                                                // Crear kernel gaussiano más agresivo
                                                const sigma = level * 1.5; // Aumentar intensidad
                                                const kernel = [];
                                                let totalWeight = 0;
                                                
                                                for (let ky = -padding; ky <= padding; ky++) {
                                                    for (let kx = -padding; kx <= padding; kx++) {
                                                        const distance = Math.sqrt(kx * kx + ky * ky);
                                                        const weight = Math.exp(-(distance * distance) / (2 * sigma * sigma));
                                                        kernel.push(weight);
                                                        totalWeight += weight;
                                                    }
                                                }
                                                
                                                // Normalizar kernel
                                                for (let i = 0; i < kernel.length; i++) {
                                                    kernel[i] /= totalWeight;
                                                }

                                                // Aplicar blur con múltiples pasadas
                                                const passes = Math.max(1, level);
                                                let sourceData = new Uint8ClampedArray(data);
                                                
                                                for (let pass = 0; pass < passes; pass++) {
                                                    newData.fill(0);
                                                    
                                                    for (let py = 0; py < height; py++) {
                                                        for (let px = 0; px < width; px++) {
                                                            // Verificar si el píxel está dentro del círculo
                                                            const globalX = x + px;
                                                            const globalY = y + py;
                                                            const dx = globalX - cx;
                                                            const dy = globalY - cy;
                                                            
                                                            const pixelIndex = (py * width + px) * 4;
                                                            
                                                            if (dx * dx + dy * dy > r * r) {
                                                                // Copiar píxel original si está fuera del círculo
                                                                for (let c = 0; c < 4; c++) {
                                                                    newData[pixelIndex + c] = sourceData[pixelIndex + c];
                                                                }
                                                                continue;
                                                            }

                                                            let red = 0, green = 0, blue = 0, alpha = 0;
                                                            let kernelIndex = 0;

                                                            // Aplicar kernel gaussiano
                                                            for (let ky = -padding; ky <= padding; ky++) {
                                                                for (let kx = -padding; kx <= padding; kx++) {
                                                                    const sampleY = py + ky;
                                                                    const sampleX = px + kx;
                                                                    
                                                                    if (sampleY >= 0 && sampleY < height && sampleX >= 0 && sampleX < width) {
                                                                        const sampleIndex = (sampleY * width + sampleX) * 4;
                                                                        const weight = kernel[kernelIndex];
                                                                        
                                                                        red += sourceData[sampleIndex] * weight;
                                                                        green += sourceData[sampleIndex + 1] * weight;
                                                                        blue += sourceData[sampleIndex + 2] * weight;
                                                                        alpha += sourceData[sampleIndex + 3] * weight;
                                                                    }
                                                                    kernelIndex++;
                                                                }
                                                            }

                                                            newData[pixelIndex] = Math.round(red);
                                                            newData[pixelIndex + 1] = Math.round(green);
                                                            newData[pixelIndex + 2] = Math.round(blue);
                                                            newData[pixelIndex + 3] = Math.round(alpha);
                                                        }
                                                    }
                                                    
                                                    // Usar el resultado como entrada para el siguiente pase
                                                    sourceData.set(newData);
                                                }

                                                // Crear nueva imagen con los datos procesados
                                                const processedImageData = new ImageData(newData, width, height);
                                                
                                                // Aplicar la imagen procesada solo dentro del círculo
                                                ctx.save();
                                                ctx.beginPath();
                                                ctx.arc(cx, cy, r, 0, Math.PI * 2);
                                                ctx.clip();
                                                ctx.putImageData(processedImageData, x, y);
                                                ctx.restore();
                                            }

                                            window.addEventListener("keydown", function (e) {
                                                // CTRL+Z para deshacer
                                                if (e.ctrlKey && e.key === 'z') {
                                                    e.preventDefault();
                                                    undo();
                                                    return;
                                                }
                                                
                                                // Delete/Backspace para restaurar imagen original
                                                if ((e.key === "Delete" || e.key === "Backspace") && circleSelected && originalImageData) {
                                                    ctx.putImageData(originalImageData, 0, 0);
                                                    savedImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                                    
                                                    // Guardar estado después de restaurar
                                                    saveStateToUndoStack();
                                                    
                                                    radius = 0;
                                                    circleSelected = false;
                                                    drawSelectionCircle();
                                                }
                                            });

                                            window.addEventListener("click", function (e) {
                                                const pos = getMousePos(e);
                                                if (!isInsideCircle(pos.x, pos.y)) {
                                                    circleSelected = false;
                                                    drawSelectionCircle();
                                                }
                                                if (blurMenu) blurMenu.remove();
                                            });

                                            function animate() {
                                                if (circleSelected) {
                                                    hue = (hue + 2) % 360;
                                                    drawSelectionCircle();
                                                }
                                                requestAnimationFrame(animate);
                                            }
                                            animate();

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
                                                    location.reload();
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