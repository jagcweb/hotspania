@foreach ($images as $i=>$image)
    @php
        $mimeType = \Storage::disk(\App\Helpers\StorageHelper::getDisk('images'))->mimeType($image->route);
        $hasLike = in_array($image->id, $likedImages ?? []);
    @endphp
    @if ($mimeType && strpos($mimeType, 'image/') === 0)
        <div class="gallery-item image-hover-zoom {{ $hasLike ? 'has-like' : '' }}" tabindex="0" data-id="{{ $image->id }}">
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
                        class="fas fa-heart" aria-hidden="true"></i> {{ \App\Models\ImageLike::where('image_id', $image->id)->count() }}</li>
                </ul>
            </div>
        </div>
    @endif
@endforeach
