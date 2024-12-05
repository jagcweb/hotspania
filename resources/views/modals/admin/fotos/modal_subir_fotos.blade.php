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
    const form = document.getElementById('upload-form');
    const uploadButton = document.getElementById('upload-btn');
    uploadButton.addEventListener('click', () => {
        form.submit();
    });
    /*// Obtener elementos del formulario y el input de archivos
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('image');
    
    // Cargar el worker de FFmpeg
    const { createWorker } = FFmpeg;
    const worker = createWorker({
            logger: (message) => console.log('logger_message', message),
            progress: (p) => console.log('progess',p)
        });

    // Cargar el worker antes de procesar archivos
    (async () => {
        await worker.load();
    })();

    // Procesar imagen con Pica
    async function processImage(file) {
        const img = new Image();
        const reader = new FileReader();
        reader.onload = async (e) => {
            img.src = e.target.result;
            img.onload = async () => {
                const canvas = document.createElement('canvas');
                const width = img.width / 2;  // Redimensionamos la imagen al 50% del tamaño original
                const height = img.height / 2;
                canvas.width = width;
                canvas.height = height;

                // Usamos Pica para redimensionar la imagen
                const pica = new Pica();
                await pica.resize(img, canvas);
                const resizedImage = canvas.toDataURL(file.type);  // Convertimos a Data URL

                // Creamos un nuevo archivo con la imagen redimensionada
                const blob = await fetch(resizedImage).then(res => res.blob());
                const newFile = new File([blob], file.name, { type: file.type });

                // Sustituir el archivo original por el redimensionado
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);
                fileInput.files = dataTransfer.files;  // Establecer el nuevo archivo al input
            };
        };
        reader.readAsDataURL(file);
    }

    // Procesar video con FFmpeg
    async function processVideo(file) {
        const reader = new FileReader();
        reader.onload = async (e) => {
            const videoBlob = e.target.result;
            const inputPath = 'input_video.mp4';
            const outputPath = 'output_video.mp4';

            // Escribir el archivo de video en el sistema de archivos de FFmpeg
            await worker.write(inputPath, videoBlob);

            // Transcodificar video a una resolución más baja (sin recodificación con '-c copy')
            await worker.transcode(inputPath, outputPath, "-c copy -s 1280x720");

            // Leer el archivo procesado
            const { data } = await worker.read(outputPath);

            // Crear un nuevo archivo de video con la transcodificación
            const newBlob = new Blob([data], { type: 'video/mp4' });
            const newFile = new File([newBlob], file.name, { type: file.type });

            // Sustituir el archivo original por el redimensionado
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);
            fileInput.files = dataTransfer.files;  // Establecer el nuevo archivo al input
        };
        reader.readAsArrayBuffer(file);
    }

    // Manejo de eventos para cuando el usuario seleccione archivos
    fileInput.addEventListener('change', async (e) => {
        const files = e.target.files;

        // Iteramos sobre los archivos seleccionados y los procesamos
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                await processImage(file);  // Procesar imágenes
            } else if (file.type.startsWith('video/')) {
                await processVideo(file);  // Procesar videos
            }
        }
        
        // Una vez procesados los archivos, enviamos el formulario
        form.submit();
    });*/
</script>