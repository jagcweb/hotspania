<div class="modal fade parentmodal" id="fotos-{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Menú fotos de {{ $u->full_name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <ul>
                    <li><a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'aprobadas']) }}" target="_blank">Fotos Aprobadas</a></li>
                    <li><a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'desaprobadas']) }}" target="_blank">Fotos Rechazadas</a></li>
                    <li><a href="#" data="subir-fotos-{{$u->id}}">Subir Fotos</a></li>
                    <li><a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'pendientes']) }}" target="_blank">Aprobar Fotos</a></li>
                    {{-- 
                    <li><a href="#" data="anuncios-fotos-{{$u->id}}">Fotos del Anuncio</a></li>
                    <li><a href="#" data="perfil-fotos-{{$u->id}}">Foto de perfil</a></li>
                    --}}
                    <li><a href="{{ route('admin.images.getFilter', ['id'=> $u->id, 'name' => $u->full_name, 'filter' => 'todas']) }}" target="_blank">Eliminar fotos</a></li>
                </ul>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@include('modals.admin.fotos.modal_subir_fotos')
{{-- 

@include('modals.admin.fotos.modal_aprobar_fotos')
@include('modals.admin.fotos.modal_foto_perfil')
@include('modals.admin.fotos.modal_fotos_anuncio')
@include('modals.admin.fotos.modal_fotos_aprobadas')
@include('modals.admin.fotos.modal_fotos_no_aprobadas')
@include('modals.admin.fotos.modal_fotos_eliminadas')

--}}
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