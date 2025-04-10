<div class="modal fade" id="asignar-paquete-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Asignar paquete</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{ str_contains(request()->fullUrl(), 'account/edit') ? route('account.assign_package') : route('admin.utilities.assign_package') }}">
                    @csrf
                    
                    @php 
                        $packages = \App\Models\Package::where('status', 'activo')->orderBy('id', 'asc')->get();
                        $activePackages = \App\Models\PackageUser::where('user_id', $u->id)
                            ->where('end_date', '>=', now())
                            ->orderBy('start_date', 'asc')
                            ->get();
                    @endphp

                    <div class="mb-3 form-group">
                        <label for="package_id" class="form-label">Paquetes</label>
                        <select class="form-control custom-select" id="package_id" name="package_id" required>
                            <option selected hidden disabled>Selecciona un paquete...</option>
                            @foreach ($packages as $pac)
                                <option value="{{ $pac->id }}">{{ $pac->name }} - {{ $pac->days }} días</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="text" class="form-control" name="user_id" hidden required value="{{ $u->id }}"/>

                    @if($activePackages->count() > 0)
                        <hr>
                        <h6 class="text-center" style="font-size: 20px;">Paquetes activos y programados:</h6>
                        @foreach($activePackages as $pactive)
                            <p class="w-100 text-center text-dark" style="font-size: 16px;">
                                {{ $pactive->package->name }}: 
                                {{ Carbon\Carbon::parse($pactive->start_date)->format('d/m/Y') }} 
                                al {{ Carbon\Carbon::parse($pactive->end_date)->format('d/m/Y') }}
                            </p>
                        @endforeach
                        <br>
                    @endif

                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" 
                               style="line-height: 10px;" value="Asignar nuevo paquete"/>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->