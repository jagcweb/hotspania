@extends('layouts.admin')

@section('title') Ordenar Fichas @endsection

@section('content')
@include('partial_msg')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <h5 class="w-100 text-center mt-2">Fichas Ordenadas en <b>{{ ucfirst(\Cookie::get('city')) ?? 'Barcelona' }}</b></h5>
            <div class="card-body">
                <div class="row sortable-grid" id="sortable-grid">
                    @foreach($orderedUsers as $u)
                        @if(\Cookie::get('city') != "todas")
                            @php 
                                $city = \App\Models\City::where('name', \Cookie::get('city') ?? 'Barcelona')->first();
                                $city_user = \App\Models\CityUser::where('user_id', $u->id)->where('city_id', $city->id)->first(); 
                            @endphp
                        @else
                            @php $city_user = null; @endphp
                        @endif
                        @if(is_object($city_user) || \Cookie::get('city') == "todas")
                            @php 
                                $frontimage = \App\Models\Image::where('user_id', $u->id)
                                    ->whereNotNull('frontimage')
                                    ->first();
                            @endphp
                            <div class="col-md-1 mb-4 sortable-item" data-id="{{ $u->id }}">
                                <div class="image-container" data-user-id="{{ $u->id }}">
                                    <div class="franja">
                                        <p>{{ Str::limit($u->nickname, 11) }}</p>
                                    </div>
                                    <div class="position-badge">{{ $loop->index + 1 }}</div>
                                    @if(is_object($frontimage))
                                        @if(!is_null($frontimage->route_gif))
                                            <img src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @else
                                            <img src="{{ route('home.imageget', ['filename' => $frontimage->route_frontimage]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/user.jpg') }}" class="img-fluid" alt="Usuario sin imagen">
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="w-100 text-center mt-2">Fichas Sin Ordenar en <b>{{ ucfirst(\Cookie::get('city')) ?? 'Barcelona' }}</b></h5>
            <div class="card-body">
                <div class="row sortable-grid" id="unordered-grid">
                    @foreach($unorderedUsers as $u)
                        @if(\Cookie::get('city') != "todas")
                            @php 
                                $city = \App\Models\City::where('name', \Cookie::get('city') ?? 'Barcelona')->first();
                                $city_user = \App\Models\CityUser::where('user_id', $u->id)->where('city_id', $city->id)->first(); 
                            @endphp
                        @else
                            @php $city_user = null; @endphp
                        @endif
                        @if(is_object($city_user) || \Cookie::get('city') == "todas")
                            @php 
                                $frontimage = \App\Models\Image::where('user_id', $u->id)
                                    ->whereNotNull('frontimage')
                                    ->first();
                            @endphp
                            <div class="col-md-1 mb-4 sortable-item" data-id="{{ $u->id }}">
                                <div class="image-container" data-user-id="{{ $u->id }}">
                                    <div class="franja">
                                        <p>{{ Str::limit($u->nickname, 11) }}</p>
                                    </div>
                                    @if(is_object($frontimage))
                                        @if(!is_null($frontimage->route_gif))
                                            <img src="{{ route('home.gifget', ['filename' => $frontimage->route_gif]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @else
                                            <img src="{{ route('home.imageget', ['filename' => $frontimage->route_frontimage]) }}" class="img-fluid" alt="{{ $u->full_name }}">
                                        @endif
                                    @else
                                        <img src="{{ asset('images/user.jpg') }}" class="img-fluid" alt="Usuario sin imagen">
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loading-overlay" style="display: none">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<style>
    .franja {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: rgba(40, 40, 40, 0.5);
        color: white;
        text-align: center;
        padding: 5px 0;
        height: 30px; /* Reducido de 38px a 30px */
        z-index: 3;
    }

    .franja p {
        margin: 0;
        line-height: 20px;
        font-size: 16px; /* Reducido de 20px a 16px */
        color: white;
    }
    
    .image-container {
        position: relative;
        flex: 1 0 calc(8.33%); /* 100% / 12 columnas */
        margin: 0;
        color: #fff;
        cursor: pointer;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #000;
        max-width: 150px; /* Ajustado para 12 columnas */
    }

    .col-md-1 {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 150px;
        justify-content: center;
    }

    .row, .sortable-grid {
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start; /* Alinear a la izquierda */
        gap: 0; /* Sin espacio entre fichas */
    }

    .card-body {
        padding: 0;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: all 0.3s ease;
    }

    .overlay {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
    }

    .overlay.visible {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .icon {
        color: white;
        font-size: 20px;  /* Reducido de 24px a 20px */
        margin: 8px 0;    /* Reducido de 10px a 8px */
        transition: transform 0.2s ease;
    }

    .icon:hover {
        transform: scale(1.2);
        color: white;
    }

    .image-darkened {
        filter: brightness(0.4);
    }

    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .sortable-drag {
        opacity: 0.5;
    }

    .sortable-item {
        cursor: move;
    }
    
    .position-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        z-index: 3;
    }
    
    .sortable-ghost {
        opacity: 0.4;
    }

    .sortable-chosen {
        transform: scale(0.95);
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    const $orderedGrid = $('#sortable-grid');
    const $unorderedGrid = $('#unordered-grid');
    let isProcessing = false;
    const MAX_ORDERED_ITEMS = 12;

    const commonOptions = {
        animation: 150,
        handle: '.image-container',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        group: {
            name: 'shared',
            put: function(to, from, dragged) {
                // Si es el grid ordenado, verificar el límite
                if (to.el.id === 'sortable-grid') {
                    return to.el.children.length < MAX_ORDERED_ITEMS;
                }
                // Si es el grid desordenado, siempre permitir
                return true;
            }
        },
        disabled: false,
        
        onStart: function(evt) {
            if (isProcessing) return false;
            $('.image-container').css('pointer-events', 'none');
            evt.item.classList.add('dragging');
        },
        
        onAdd: function(evt) {
            if (evt.to === $unorderedGrid[0]) {
                $(evt.item).find('.position-badge').remove();
            } else if (evt.to === $orderedGrid[0]) {
                if (!$(evt.item).find('.position-badge').length) {
                    $(evt.item).find('.image-container').append('<div class="position-badge"></div>');
                }
                // Mostrar mensaje si se alcanzó el límite
                if (evt.to.children.length >= MAX_ORDERED_ITEMS) {
                    $('body').append(`
                        <div style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:#ff9800; color:#fff; text-align:center; font-size:16px;">
                            Has alcanzado el límite de ${MAX_ORDERED_ITEMS} fichas ordenadas
                        </div>
                    `);
                    setTimeout(() => {
                        $('div[style*="background:#ff9800"]').fadeOut(500, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            }
        },
        
        onChange: function(evt) {
            if (!isProcessing) return;
        },
        
        onEnd: async function(evt) {
            if (isProcessing) return;
            
            isProcessing = true;
            $('.image-container').css('pointer-events', 'none');
            evt.item.classList.remove('dragging');
            
            $('#preloader').css({
                'opacity': '0.8',
                'visibility': 'visible',
            }).show();
            
            $('.jumper div').css('background-color', '#F65807');
            
            orderedSortable.option("disabled", true);
            unorderedSortable.option("disabled", true);
            
            // Preparar datos para enviar
            const orderedItems = $.map($orderedGrid.children(), function(el, index) {
                return {
                    id: $(el).data('id'),
                    position: index
                };
            });

            const unorderedItems = $.map($unorderedGrid.children(), function(el) {
                return {
                    id: $(el).data('id'),
                    position: null
                };
            });

            // Actualizar números de posición solo en el grid ordenado
            $orderedGrid.find('.position-badge').each(function(index) {
                $(this).text(index + 1);
            });

            try {
                const response = await $.ajax({
                    url: '{{ route("admin.users.updatePositions") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({ 
                        positions: [...orderedItems, ...unorderedItems]
                    }),
                    contentType: 'application/json'
                });
                
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                $('body').append(`
                    <div style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:green; color:#fff; text-align:center; font-size:16px;">
                        Posiciones actualizadas correctamente
                    </div>
                `);
                
                setTimeout(() => {
                    $('div[style*="background:green"]').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 3000);
                
            } catch (error) {
                $('body').append(`
                    <div style="z-index:999; position:fixed; top:10%; width:100%; display:flex; justify-content:center; align-items:center; min-height:50px; background:red; color:#fff; text-align:center; font-size:16px;">
                        Error al actualizar las posiciones
                    </div>
                `);
                
                setTimeout(() => {
                    $('div[style*="background:red"]').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 3000);
            } finally {
                $("#preloader").animate({
                    'opacity': '0'
                }, 600, function(){
                    setTimeout(function(){
                        $("#preloader").css("visibility", "hidden").fadeOut();
                    }, 300);
                });
                
                $('.jumper div').css('background-color', '#F65807!important');
                $('.image-container').css('pointer-events', 'auto');
                orderedSortable.option("disabled", false);
                unorderedSortable.option("disabled", false);
                isProcessing = false;
            }
        }
    };

    let orderedSortable = new Sortable($orderedGrid[0], commonOptions);
    let unorderedSortable = new Sortable($unorderedGrid[0], commonOptions);

    $orderedGrid.on('mousedown', '.image-container', function() {
        $(this).find('.overlay').hide();
    });

    $(document).on('mouseup', function() {
        $('.overlay').show();
    });
});
</script>

@endsection