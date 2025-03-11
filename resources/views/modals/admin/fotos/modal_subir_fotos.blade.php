<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="subir-fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Subir fotos {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form id="upload-form" action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="image">Imágenes o vídeos</label>
                        <input type="file" class="form-control image_upload" id="image" name="images[]" multiple accept=".jpeg,.png,.jpg,.gif,.webp,.mp4,.mov,.avi,.wmv,.avchd,.webm,.flv">
                        <input type="text" id="user_id" name="user_id" value="{{$u->id}}" hidden/>
                    </div>

                    @if (Request::is('account/edit*'))
                    <button type="submit" id="upload-btn" class="btn" style="background:#f36e00; color:#fff;">Subir imágenes</button>
                    @else
                    <button type="submit" id="upload-btn" class="btn btn-primary">Subir imágenes</button>
                    @endif
                    <div id="progress-container"></div> 
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
    /* Estilo para el botón */
    #upload-btn {
        position: relative;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    /* Loader dentro del botón */
    .loader-btn {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 4px solid #f3f3f3; /* Borde exterior */
        border-top: 4px solid #3498db; /* Borde interior de color azul */
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: none; /* Lo ocultamos inicialmente */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/pica@7.0.0/dist/pica.min.js"></script>
<script src="https://unpkg.com/@ffmpeg/ffmpeg@0.7.0/dist/ffmpeg.min.js"></script>

<script>
    var uploadButton = document.getElementById('upload-btn');
    var form = document.getElementById('upload-form');
    var fileInput = document.getElementById('image');
    var progressContainer = document.getElementById('progress-container');
    var userId = document.getElementById('user_id').value;

    // Obtener el token CSRF del meta tag
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Función que maneja la subida de cada archivo
    async function uploadFile(file, userId, currentIndex, totalFiles) {
        return new Promise((resolve, reject) => {
            var xhr = new XMLHttpRequest();
            var uploadUrl = window.location.pathname.includes("/account/edit") 
            ? "{{ route('account.images.upload') }}" 
            : "{{ route('admin.images.upload') }}";

            xhr.open('POST', uploadUrl);

            // Incluir el token CSRF en los encabezados
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

            // Crear el progress bar para cada archivo
            var progressBarWrapper = document.createElement('div');
            var progressBar = document.createElement('progress');
            progressBar.setAttribute('max', '100');
            progressBar.setAttribute('value', '0');
            progressBarWrapper.innerText = `Subiendo (${currentIndex}/${totalFiles})...`;
            progressBarWrapper.appendChild(progressBar);
            progressContainer.appendChild(progressBarWrapper);

            // Actualizar progreso de la subida
            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    var percent = Math.round((event.loaded / event.total) * 100);
                    progressBar.value = percent;
                }
            };

            // Evento de respuesta cuando la subida finaliza
            xhr.onload = () => {
                if (xhr.status === 200) {
                    console.log('200', xhr.responseText);
                    resolve(xhr.responseText);
                    progressBarWrapper.innerText = '';
                } else {
                    console.log('err', xhr.responseText);
                    reject(new Error(xhr.responseText));
                    progressBarWrapper.innerText = `Error al subir ${file.name}.`;
                }
            };

            // Manejar error de red
            xhr.onerror = () => {
                reject(new Error('Error de red'));
                progressBarWrapper.innerText = `Error al subir ${file.name}.`;
            };

            var formData = new FormData();
            formData.append('images[]', file);  // Solo se sube un archivo por petición
            formData.append('user_id', userId);
            xhr.send(formData);
        });
    }

    // Evento para manejar el clic del botón y subir los archivos
    uploadButton.addEventListener('click', async () => {
    if (fileInput.files.length === 0) {
        alert('Selecciona al menos un archivo antes de enviar.');
        return;
    }

    // Deshabilitar el botón y actualizar el mensaje
    uploadButton.disabled = true;
    uploadButton.innerText = `Subiendo ${fileInput.files.length} archivos, por favor espere...`;

    // Crear o reutilizar el mensaje dinámico
    let messageElement = document.getElementById('upload-message');
    if (!messageElement) {
        messageElement = document.createElement('p');
        messageElement.id = 'upload-message';
        messageElement.style.color = 'green';
        messageElement.style.fontSize = '15px';
        progressContainer.parentNode.insertBefore(messageElement, progressContainer);
    }

    // Iterar sobre los archivos seleccionados y subirlos
    const files = fileInput.files;
    try {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            await uploadFile(file, userId, i + 1, files.length); // Subir cada archivo de forma individual
        }

        // Mensaje de éxito
        
        if(window.location.pathname.includes("/account/edit")) {
            messageElement.innerText = 'Todos los archivos fueron subidos exitosamente como pendiente de aprobación del administrador. Recargando la página...';

            setTimeout(() => {
            window.location.reload();
        }, 2500);
        } else {
            messageElement.innerText = 'Todos los archivos fueron subidos exitosamente. Recargando la página...';

            setTimeout(() => {
            window.location.reload();
        }, 1000);
        }

    } catch (error) {
        // Mensaje de error
        messageElement.innerText = 'Error al subir los archivos. Por favor, inténtalo de nuevo.';
        console.error(error);
    } finally {
        uploadButton.disabled = true;
        uploadButton.innerText = 'Archivos subidos';
    }
});

</script>
