@if(Session::has('error'))
    <div id="error-message" style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:red; color:#fff; text-align:center; font-size:16px;">
        {{ Session::get('error') }}
        @php
            Session::forget('error');
        @endphp
    </div>
@endif

@if(Session::has('exito'))
    <div id="success-message" style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:green; color:#fff; text-align:center; font-size:16px;">
        {!! Session::get('exito') !!}
        @php
            Session::forget('exito');
        @endphp
    </div>
@endif

@if (count($errors) > 0)
    <div id="errors-message" style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:red; color:#fff; text-align:center; font-size:16px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    // Función para ocultar mensajes después de 3 segundos
    setTimeout(function() {
        var errorMsg = document.getElementById('error-message');
        var successMsg = document.getElementById('success-message');
        var errorsMsg = document.getElementById('errors-message');
        
        if(errorMsg) errorMsg.style.display = 'none';
        if(successMsg) successMsg.style.display = 'none';
        if(errorsMsg) errorsMsg.style.display = 'none';
    }, 3000);
</script>