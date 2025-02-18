<div class="modal fade" id="historial-paquete-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Historial de paquetes {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                @php $history = \App\Models\PackageUserHistory::where('user_id', $u->id)->orderBy('id', 'desc')->get(); @endphp

                @if(count($history)>0)
                    @foreach ($history as $h)
                    <p class="w-100 text-center text-dark">
                        <b>{{ $h->package->name }} </b>
                        activado el {{ \Carbon\Carbon::parse($h->created_at)->format('d/m/Y') }} 
                        durante {{ $h->package->days }} días.
                    </p> 
                    <br>
                    @endforeach
                @else
                    <p class="w-100 text-center text-dark">No hay registros.</p>
                @endif

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->