<div class="modal fade" id="verify-user-{{$us->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">
                    @if(is_null($us->email_verified_at))
                        Verificar Email Usuario {{$us->name}} {{$us->surname}}
                    @else
                        Desverificar Email Usuario {{$us->name}} {{$us->surname}}
                    @endif
                    
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                @if(is_null($us->email_verified_at))
                    <p class="text-danger text-center w-100">Se verificará el email <b>({{ $us->email }})</b> del usuario {{$us->name}} {{$us->surname}}</p>
                @else
                    <p class="text-danger text-center w-100">Se desverificará el email <b>({{ $us->email }})</b> del usuario {{$us->name}} {{$us->surname}}</p>
                @endif

                <div class='form-group mt-2'>
                    <button class="btn-input w-100">
                        <a class="text-dark" href="{{route('admin.user.verify', ['id' => $us->id])}}">
                            @if(is_null($us->email_verified_at))
                                Verificar
                            @else
                                Desverificar
                            @endif
                        </a>
                    </button>
                </div>  
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

