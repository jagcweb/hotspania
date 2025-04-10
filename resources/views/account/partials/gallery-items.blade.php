@foreach ($images as $i=>$image)
    @php
        $mimeType = \Storage::disk(\App\Helpers\StorageHelper::getDisk('images'))->mimeType($image->route);
    @endphp
    @if ($mimeType && strpos($mimeType, 'image/') === 0)
        <div class="gallery-item image-hover-zoom" tabindex="0">
            <img src="{{ route('home.imageget', ['filename' => $image->route]) }}"
                class="gallery-image" alt="" loading="lazy">
            @if(!is_null($image->frontimage))
            <div class="gallery-item-type">
                <span class="visually-hidden">Portada</span>
                <i class="fa-solid fa-star" aria-hidden="true"></i>
            </div>
            @endif
            <div class="gallery-item-info">
                <ul>
                    <li class="gallery-item-likes"><span class="visually-hidden">Vistas:</span><i
                            class="fas fa-eye" aria-hidden="true"></i> {{ $image->visits ?? 0 }}</li>
                    <li class="gallery-item-comments"><span class="visually-hidden">Likes:</span><i
                            class="fas fa-heart" aria-hidden="true"></i> {{ $image->likes ?? 0 }}</li>
                </ul>
            </div>
        </div>
    @endif
@endforeach
