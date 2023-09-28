<div class="modal fade" id="update-order-{{$order->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar estado pedido {{$order->reference}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <form method="POST" action="{{route('admin.order.update', ['id' => $order->id])}}" enctype='multipart/form-data'>
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <select class="form-control" name="status" required>
                            <option value="{{$order->status}}" selected hidden>
                                @switch($order->status)
                                    @case(0)
                                        Pendiente
                                    @break
                                    
                                    @case(1)
                                        Finalizado
                                    @break

                                    @case(2)
                                        Pendiente envío
                                    @break

                                    @case(3)
                                        En transporte
                                    @break

                                    @case(4)
                                        Cancelado
                                    @break
                                @endswitch
                            </option>

                            @for ($i = 0; $i <= 4; $i++)
                                <option value="{{$i}}">
                                    @switch($i)
                                        @case(0)
                                            Pendiente
                                        @break
                                        
                                        @case(1)
                                            Finalizado
                                        @break

                                        @case(2)
                                            Pendiente envío
                                        @break

                                        @case(3)
                                            En transporte
                                        @break

                                        @case(4)
                                            Cancelado
                                        @break
                                    @endswitch
                                </option>
                            @endfor
                        </select>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class='form-group mt-2'>
                        <button class="btn-input w-100">Modificar</button>
                    </div>  
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

