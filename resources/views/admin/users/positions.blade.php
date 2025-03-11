@extends('layouts.admin')

@section('title') Ordenar Fichas @endsection

@section('content')
@include('partial_msg')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="w-100 text-center mt-2">Ordenar Fichas en <b>{{ ucfirst(\Cookie::get('city')) ?? 'Barcelona' }}</b></h5>
            <div class="card-body">
                <div class="row sortable-grid" id="sortable-grid">
                    @foreach($users as $u)
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
                            <div class="col-md-3 mb-4 sortable-item" data-id="{{ $u->id }}">
                                <div class="image-container" data-user-id="{{ $u->id }}">
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
    </div>
</div>

<div id="loading-overlay" style="display: none">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<style>
    .image-container {
        position: relative;
        flex: 1 0 calc(25%);
        margin: 0;
        color: #fff;
        cursor: pointer;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #000;
        max-width: 180px;
    }

    .col-md-3 {
        display: flex;
        padding: 0;
        margin: 0;
        max-width: 180px;
    }

    .row {
        margin: 0;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 0;
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
        font-size: 24px;
        margin: 10px 0;
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

    .sortable-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .sortable-chosen {
        transform: scale(0.95);
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    const $grid = $('#sortable-grid');
    let isProcessing = false;

    let sortable = new Sortable($grid[0], {
        animation: 150,
        handle: '.image-container',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        disabled: false,
        
        onStart: function(evt) {
            if (isProcessing) return false;
            $('.image-container').css('pointer-events', 'none');
            evt.item.classList.add('dragging');
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
            
            sortable.option("disabled", true);
            
            const items = $.map($grid.children(), function(el, index) {
                return {
                    id: $(el).data('id'),
                    position: index
                };
            });

            $('.position-badge').each(function(index) {
                $(this).text(index + 1);
            });

            try {
                const response = await $.ajax({
                    url: '{{ route("admin.users.updatePositions") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({ positions: items }),
                    contentType: 'application/json'
                });
                
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Mostrar mensaje de éxito
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
                // Mostrar mensaje de error
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
                sortable.option("disabled", false);
                isProcessing = false;
            }
        }
    });

    $grid.on('mousedown', '.image-container', function() {
        $(this).find('.overlay').hide();
    });

    $(document).on('mouseup', function() {
        $('.overlay').show();
    });
});
</script>

@endsection