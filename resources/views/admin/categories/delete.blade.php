<div class="modal fade" id="delete-category-{{$cat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Borrar Categoría {{$cat->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">

                <p class="text-danger text-center w-100">Al borrar la categoría también se borrarán todas las sub-categorías y todos los productos adheridos a la misma.</p>

                <div class='form-group mt-2'>
                    <button class="btn-input w-100">
                        <a class="text-dark" href="{{route('admin.category.delete', ['id' => $cat->id])}}">Borrar</a>
                    </button>
                </div>  
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

