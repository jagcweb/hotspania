<div class="modal fade" id="hacer-disponible-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Hacer disponible @if(!str_contains(request()->fullUrl(), 'account/edit')) {{ $u->full_name }} @endif</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                @php
                    $canMakeAvailable = true;
                    if ($u->available_until !== null) {
                        $now = \Carbon\Carbon::now('Europe/Madrid');
                        $startTime = $now;
                        $endTime = \Carbon\Carbon::parse($u->available_until)->setTimezone('Europe/Madrid');
                        
                        if ($now->lt($endTime)) {
                            $canMakeAvailable = false;
                            $remainingMinutes = $now->diffInMinutes($endTime);
                        }
                    }
                @endphp

                @if ($canMakeAvailable)
                    <form method="POST" action="{{ str_contains(request()->fullUrl(), 'account/edit') ? route('account.make_available', ['id' => \Crypt::encryptString($u->id)]) : route('admin.users.make_available', ['id' => \Crypt::encryptString($u->id)]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="tiempo">Tiempo</label>
                            <select class="form-control" id="tiempo" name="tiempo">
                                <option value="" hidden selected disabled>Seleccionar un tiempo...</option>
                                <option value="1">1 hora</option>
                                <option value="2">2 horas</option>
                                <option value="3">3 horas</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" 
                            style="line-height: 10px;" value="Guardar"/>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        El usuario aún está disponible por <b>{{ $remainingMinutes }}</b> minutos más.
                        <br>
                        <small>Desde ahora hasta: {{ $endTime->format('H:i') }}</small>
                    </div>

                    <form method="POST" action="{{ str_contains(request()->fullUrl(), 'account/edit') ? route('account.make_available', ['id' => \Crypt::encryptString($u->id)]) : route('admin.users.make_available', ['id' => \Crypt::encryptString($u->id)]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="tiempo">Tiempo</label>
                            <select class="form-control" id="tiempo" name="tiempo">
                                <option value="" hidden selected disabled>Seleccionar un tiempo...</option>
                                <option value="1">1 hora</option>
                                <option value="2">2 horas</option>
                                <option value="3">3 horas</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-sm btn-dark waves-effect waves-dark w-100" 
                            style="line-height: 10px;" value="Guardar"/>
                        </div>
                    </form>

                    <hr>
                    
                    <a class="btn btn-sm btn-dark w-100" style="background:#2a2a2a; color:#fff;" 
                    href="{{ str_contains(request()->fullUrl(), 'account/edit') ? route('account.make_unavailable', ['id' => \Crypt::encryptString($u->id)]) : route('admin.users.make_unavailable', ['id' => \Crypt::encryptString($u->id)]) }}">
                    Apagar disponibilidad</a>

                @endif

                @if ($u->available_until !== null && $endTime->isPast())
                    <div class="alert alert-info mt-3">
                        El tiempo de disponibilidad terminó el {{ $endTime->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
