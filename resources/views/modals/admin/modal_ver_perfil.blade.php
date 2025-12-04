<div class="modal fade parentmodal" id="ver-perfil-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Perfil de {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <ul>
                    <li><a href="#" data="tags-{{$u->id}}">Crear Tag</a></li>
                    <li><a href="{{route('admin.users.edit', ['id' => $u->id])}}">Editar Datos</a></li>
                    <li><a href="#" data="fotos-{{$u->id}}">Ver Fotos</a></li>
                    <li><a href="#" data="cambiar-contraseña-{{$u->id}}">Cambiar Contraseña</a></li>
                    <li><a title="Cambiar visibilidad" href="{{ route('account.visible', ['id' => \Crypt::encryptString($u->id)]) }}" data="hacer-cuenta-visible-{{$u->id}}">@if(!is_null($u->visible)) Cuenta: Visible @else Cuenta: NO Visible @endif</a></li>
                </ul>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@include('modals.admin.user.modal_tags')
@include('modals.admin.modal_fotos')

@include('modals.admin.modal_cambiar_contraseña')
<style>
    .modal ul {
      display: flex;
      flex-direction: column;
      align-items: center;
      list-style-type: none;
      width: 100%;
    }
    .modal ul li {
        padding: 6px 0;
    }
    
    .modal ul li a {
        width: 100%;
        position: relative;
        display: block;
        padding: 4px 0;
        text-align: center;
        font-family: Lato, sans-serif;
        color: #585858;
        text-decoration: none;
        text-transform: uppercase;
        transition: 0.5s;
    }
    
    .modal ul li a:after{
        position: absolute;
        content: "";
        top: 100%;
        left: 0;
        width: 100%;
        height: 3px;
        background: #000;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.5s;
    }
    
    .modal ul li a:hover {
        color: #252525;
    }
    
    .modal ul li a:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
    </style>