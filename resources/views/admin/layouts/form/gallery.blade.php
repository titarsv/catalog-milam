<div class="gallery-container">
    @if(!is_null($gallery))
        @foreach($gallery as $image)
            @if(is_object($image) && !empty($image->image))
                <div class="col-sm-3">
                    <div>
                        <i class="remove-gallery-image">-</i>
                        <i class="fa fa-search-plus js_zoom_image"></i>
                        <input name="{{ $key }}[]" value="{{ $image->file_id }}" type="hidden">
                        <img src="{{ $image->image->type == 'video' ? '/images/larchik/video.png' : $image->url() }}">
                    </div>
                </div>
            @endif
        @endforeach
    @endif
    <div class="col-sm-3 add-gallery-image upload_image_button" data-type="multiple" data-name="{{ $key }}">
        <div class="add-btn"></div>
    </div>
</div>