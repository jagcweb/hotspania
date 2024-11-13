<div class="modal fade parentmodal" id="update-package-{{$p->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Actualizar paquete {{ $p->name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.utilities.packages_update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}">
                    <div class="form-group">
                        <label for="package" class="form-label">Nombre del paquete</label>
                        <input type="text" class="form-control" id="package" aria-describedby="package" name="name" placeholder="asdf..." value="{{ $p->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="type" required>
                            <option selected hidden>{{ucfirst(str_replace("_", " ", $p->type))}}</option>
                            <option value="ficha_general">Ficha general</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="price" required value="{{ $p->price }}">
                    </div>
                    <div class="mb-3">
                        <label for="dias" class="form-label">Días</label>
                        <input type="number" class="form-control" id="dias" name="days" required value="{{ $p->days }}">
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="status" required>
                            <option selected hidden>{{ucfirst($p->status)}}</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                        <button type="submit" class="btn">Actualizar</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->