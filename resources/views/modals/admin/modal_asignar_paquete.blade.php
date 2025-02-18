<div class="modal fade" id="asignar-paquete-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Asignar paquete {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{ route('admin.utilities.assign_package') }}">
                    @csrf
                    
                    @php $packages = \App\Models\Package::where('status', 'activo')->orderBy('id', 'asc')->get(); @endphp

                    <div class="mb-3">
                        <label for="package_id" class="form-label">Paquetes</label>
                        <select class="form-select" id="package_id" name="package_id" required>
                            <option selected hidden disabled>Selecciona un paquete...</option>
                            @foreach ($packages as $pac)
                                <option value="{{ $pac->id }}">{{ $pac->name }} - {{ $pac->days }} días</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="text" class="form-control" name="user_id" hidden required value="{{ $u->id }}"/>

                    

                    @php $pactive = \App\Models\PackageUser::where('user_id', $u->id)->first(); @endphp

                    @if(is_object($pactive))
                        <hr>
                        
                        <p class="w-100 text-center text-dark">
                            Paquete: {{ $pactive->package->name }} 
                            activado el {{ \Carbon\Carbon::parse($pactive->created_at)->format('d/m/Y') }} 
                            durante {{ $pactive->package->days }} días (acaba el {{ \Carbon\Carbon::parse($pactive->created_at)->addDays($pactive->package->days)->format('d/m/Y') }})
                        </p> 

                        @if (\Carbon\Carbon::parse($pactive->created_at)->addDays($pactive->package->days)->toDateString() >= \Carbon\Carbon::today()->toDateString())
                            <hr>
                            <p class="w-100 text-center text-danger">Paquete ya activado. Si añades otro paquete, sustituirás el actual.</p>
                        @endif

                        <br>
                    @endif

                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" style="line-height: 10px;" value="Asignar"/>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->