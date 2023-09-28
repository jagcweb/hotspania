@if(Session::has('error'))
    <div style="z-index:9999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:red; color:#fff; text-align:center; font-size:16px;">
        {{ Session::get('error') }}
        @php
            Session::forget('error');
        @endphp
    </div>
@endif

@if(Session::has('exito'))
    <div style="z-index:9999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:green; color:#fff; text-align:center; font-size:16px;">
        {!! Session::get('exito') !!}
        @php
            Session::forget('exito');
        @endphp
    </div>
@endif

@if (count($errors) > 0)
    <div style="z-index:9999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:red; color:#fff; text-align:center; font-size:16px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif