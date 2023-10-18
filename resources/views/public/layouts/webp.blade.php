@if(!empty($webp))
<picture class="{{ $lazy == 'editor' ? 'editor-image' : '' }}{{ !empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : '' }}"><source
    @if($lazy == 'slider')
    data-lazy="{{ $webp }}"
    @elseif($lazy == 'static')
    data-original="{{ $webp }}" srcset="/images/larchik/pixel.webp"
    @elseif($lazy == 'base64')
    @if(is_file(public_path(str_replace(env('APP_URL'), '', $webp))))
    srcset="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path(str_replace(env('APP_URL'), '', $webp)))) }}"
    @else
    srcset="{{ $webp }}"
    @endif
    @else
    srcset="{{ $webp }}"
    @endif
    type="image/webp" class="lazy-web"><source
    @if($lazy == 'slider')
    data-lazy="{{ $original }}"
    @elseif($lazy == 'static')
    data-original="{{ $original }}" srcset="/images/larchik/pixel.{{ empty(trim($original_mime)) ? 'jpg' : str_replace('image/', '', $original_mime) }}"
    @else
    srcset="{{ $original }}"
    @endif
    type="{{ empty(trim($original_mime)) ? 'image/jpeg' : $original_mime }}" class="lazy-web"><img
    @if($lazy == 'slider')
    data-lazy="{{ $original }}"
    src="/images/larchik/pixel.jpg"
    @elseif($lazy == 'static' || $lazy == 'editor')
    src="/images/larchik/pixel.jpg"
    @else
    src="{{ $original }}"
    @endif
    @foreach($attributes as $key => $attr) @if($key != 'picture_class') {{ $key }}="{{ $attr }}" @endif @endforeach class="lazy">
</picture>
@else
    @if(!empty($original))
        <picture class="{{ $lazy == 'editor' ? 'editor-image' : '' }}{{ !empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : '' }}">
            <img src="{{ $original }}" @foreach($attributes as $key => $attr) @if($key != 'picture_class') {{ $key }}="{{ $attr }}" @endif @endforeach>
        </picture>
    @else
        <picture class="{{ $lazy == 'editor' ? 'editor-image' : '' }}{{ !empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : '' }}">
            <img src="/images/larchik/no_image.jpg" @foreach($attributes as $key => $attr) @if($key != 'picture_class') {{ $key }}="{{ $attr }}" @endif @endforeach>
        </picture>
    @endif
@endif
