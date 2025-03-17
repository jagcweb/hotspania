@foreach ($users as $i=>$user)
    @if(count($user->images) > 0)
        @php
            $image = \App\Models\Image::where('user_id', $user->id)
                ->whereNotNull('frontimage')
                ->whereNotNull('route_frontimage')
                ->first();
            
            if (!is_object($image)) {
                $image = \App\Models\Image::where('user_id', $user->id)->orderBy('id', 'asc')->first();
            }

            $isAvailable = false;
            if ($user->available_until !== null) {
                $now = \Carbon\Carbon::now('Europe/Madrid');
                $availableUntil = \Carbon\Carbon::parse($user->available_until)->setTimezone('Europe/Madrid');
                $endTime = $availableUntil->addHours($user->available_time);
                $isAvailable = $now->lt($endTime);
            }
        @endphp
        
        <a href="{{ route('account.get', ['nickname' => $user->nickname]) }}">
            <div class="gallery-item image-hover-zoom" tabindex="0" data-user-id="{{ $user->id }}">
                @if($isAvailable)
                    <div class="availability-indicator"></div>
                @endif
                <img src="{{ route('home.imageget', ['filename' => $image->route_frontimage ?? $image->route]) }}"
                    class="gallery-image" alt="">
                <div class="franja">
                    <p>{{ Str::limit($user->nickname, 10) }}</p>
                </div>
                <div class="gallery-item-info">
                    <ul>
                        <li class="gallery-item-likes">
                            <span class="visually-hidden">Likes:</span>
                            <i class="fas fa-eye" aria-hidden="true"></i> {{56 * ($i+2)}}
                        </li>
                    </ul>
                </div>
            </div>
        </a>
    @endif
@endforeach
