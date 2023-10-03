<div class="modal fade" id="ban-user-{{$us->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">
                    @if(is_null($us->banned))
                        Banear Usuario {{$us->name}} {{$us->surname}}
                    @else
                        Desbanear Usuario {{$us->name}} {{$us->surname}}
                    @endif
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                @if(is_null($us->banned))
                    <p class="text-danger text-center w-100">Al banear al usuario no podrá acceder a su cuenta.</p>
                @else
                    <p class="text-danger text-center w-100">Al desbanear al usuario podrá acceder a su cuenta con normalidad.</p>
                @endif

                <div class='form-group mt-2'>
                    <button class="btn-input w-100">
                        <a class="text-dark" href="{{route('admin.user.ban', ['id' => $us->id])}}">
                            @if(is_null($us->banned))
                                Banear
                            @else
                                Desbanear
                            @endif
                        </a>
                    </button>
                </div>  
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

