@php
    // Al inicio del archivo, antes del foreach
    $availableColors = session('availableColors', range(1, 15));
    if (empty($availableColors)) {
        $availableColors = range(1, 15);
    }
@endphp

@foreach ($users as $i=>$user)
    @if(count($user->images) > 0)
        @php
            if (request()->has('fotos')) {
                // Si la URL contiene ?fotos, seleccionar la imagen con más puntos
                $image = \App\Models\Image::where('user_id', $user->id)
                    ->whereNotNull('frontimage')
                    ->whereNotNull('route_frontimage')
                    ->selectRaw('*, (visits * 0.2 + (SELECT COUNT(*) FROM image_likes WHERE image_id = images.id) * 0.5) as total_points')
                    ->orderBy('total_points', 'desc')
                    ->first();
            } else {
                // Comportamiento normal: primera imagen disponible
                $image = \App\Models\Image::where('user_id', $user->id)
                    ->whereNotNull('frontimage')
                    ->whereNotNull('route_frontimage')
                    ->first();
            }
            
            if (!is_object($image)) {
                $image = \App\Models\Image::where('user_id', $user->id)->orderBy('id', 'asc')->first();
            }

            $isAvailable = false;
            if ($user->available_until !== null) {
                $now = \Carbon\Carbon::now('Europe/Madrid');
                $endTime = \Carbon\Carbon::parse($user->available_until)->setTimezone('Europe/Madrid');
                $isAvailable = $now->lt($endTime);
            }

            // Seleccionar color aleatorio sin repetición
            if (empty($availableColors)) {
                $availableColors = range(1, 15);
            }
            $randomIndex = array_rand($availableColors);
            $colorClass = 'flame-color-' . $availableColors[$randomIndex];
            unset($availableColors[$randomIndex]);
            
            // Guardar los colores restantes en la sesión
            session(['availableColors' => array_values($availableColors)]);

            // Obtener todas las imágenes del usuario y sumar sus views
            $totalViews = \App\Models\Image::where('user_id', $user->id)->sum('visits');

            // Obtener el total de likes de todas las imágenes del usuario
            $totalLikes = \App\Models\ImageLike::whereIn('image_id', function($query) use ($user) {
                $query->select('id')
                      ->from('images')
                      ->where('user_id', $user->id);
            })->count();

            // Calcular puntos totales (0.2 por vista y 0.5 por like)
            $totalPoints = floor($totalViews * 0.2 + $totalLikes * 0.5);

        @endphp



        <a href="{{ route('account.get', ['nickname' => $user->nickname]) }}">
            <div class="gallery-item image-hover-zoom" tabindex="0" data-user-id="{{ $user->id }}">
                @if($isAvailable && request()->get('filter') !== 'disponibles')
                    <img src="{{ asset('images/llamas.gif') }}" class="flame-border {{ $colorClass }}" alt="Online">
                @endif
                <img src="{{ route('home.imageget', ['filename' => $image->route_frontimage ?? $image->route]) }}"
                    class="gallery-image" alt="">
                <div class="franja">
                    <p>{{ Str::limit($user->nickname, 11) }}</p>
                </div>
                <div class="gallery-item-info">
                    <ul>
                        <li class="gallery-item-likes">
                            <span class="visually-hidden">Views:</span>
                            <i class="fas fa-eye" aria-hidden="true"></i> {{$totalViews}}
                        </li>
                        <li class="gallery-item-likes">
                            <span class="visually-hidden">Likes:</span>
                            <i class="fas fa-heart" aria-hidden="true"></i> {{$totalLikes}}
                        </li>
                        <li class="gallery-item-points">
                            <span class="visually-hidden">Points:</span>
                            <i class="fas fa-bullseye" aria-hidden="true"></i> {{$totalPoints}}
                        </li>
                    </ul>
                </div>
            </div>
        </a>
    @endif
@endforeach

<style>
.flame-border {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    pointer-events: none; /* Permite que los clicks pasen a través de la imagen */
}

/* Variaciones de color usando filtros CSS */
.flame-color-1 { filter: hue-rotate(0deg) saturate(100%) brightness(100%); }     /* Original */
.flame-color-2 { filter: hue-rotate(30deg) saturate(150%) brightness(110%); }    /* Naranja cálido */
.flame-color-3 { filter: hue-rotate(60deg) saturate(140%) brightness(120%); }    /* Amarillo */
.flame-color-4 { filter: hue-rotate(120deg) saturate(150%) brightness(90%); }    /* Verde */
.flame-color-5 { filter: hue-rotate(180deg) saturate(130%) brightness(100%); }   /* Turquesa */
.flame-color-6 { filter: hue-rotate(240deg) saturate(160%) brightness(110%); }   /* Azul */
.flame-color-7 { filter: hue-rotate(270deg) saturate(140%) brightness(100%); }   /* Púrpura */
.flame-color-8 { filter: hue-rotate(300deg) saturate(150%) brightness(120%); }   /* Rosa */
.flame-color-9 { filter: hue-rotate(330deg) saturate(170%) brightness(90%); }    /* Magenta */
.flame-color-10 { filter: hue-rotate(15deg) saturate(200%) brightness(130%); }   /* Naranja brillante */
.flame-color-11 { filter: hue-rotate(90deg) saturate(120%) brightness(140%); }   /* Verde lima */
.flame-color-12 { filter: hue-rotate(150deg) saturate(180%) brightness(80%); }   /* Verde azulado */
.flame-color-13 { filter: hue-rotate(200deg) saturate(160%) brightness(110%); }  /* Azul celeste */
.flame-color-14 { filter: hue-rotate(290deg) saturate(130%) brightness(120%); }  /* Violeta */
.flame-color-15 { filter: hue-rotate(320deg) saturate(190%) brightness(100%); }  /* Rosa intenso */
</style>
