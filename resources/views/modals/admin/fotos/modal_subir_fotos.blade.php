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
                        <input type="text" name="user_id" value="{{$u->id}}" hidden/>
                    </div>
                    <button type="submit" id="upload-btn" class="btn btn-primary" onclick="startLoading()">Subir imágenes</button>
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
    function startLoading() {
        const button = document.getElementById("upload-btn");
        const loader = document.createElement("div");
        loader.classList.add("loader-btn");
        button.appendChild(loader); // Añadimos el loader al botón

        loader.style.display = 'inline-block'; // Mostramos el loader
        button.disabled = true; // Deshabilitamos el botón para evitar clics múltiples
    }
</script>
<script>
    const uploadButton = document.getElementById('upload-btn');
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('image');

    uploadButton.addEventListener('click', async () => {
        if (fileInput.files.length === 0) {
            alert('Selecciona al menos un archivo antes de enviar.');
            return;
        }

        // Deshabilitar botón y mostrar mensaje
        uploadButton.disabled = true;
        uploadButton.innerText = 'Subiendo archivos, por favor espere...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                alert('Archivos subidos exitosamente, se recargará automáticamente la página.');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error al subir los archivos. Inténtalo de nuevo.');
                console.error(await response.text());
            }
        } catch (error) {
            console.error('Error en la subida:', error);
            alert('Ocurrió un error al procesar la solicitud.');
        } finally {
            uploadButton.disabled = false;
            uploadButton.innerText = 'Subir Archivos';
        }
    });
</script>