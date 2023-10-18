<div class="row">
    <div class="col-sm-3">
        <div class="image-container">
            <input type="hidden"
                   id="fields{{ !empty($parent) ? str_replace(['[', ']'], '', $parent).(isset($iterator) ? $iterator : 0) : '' }}{{ $field->slug }}"
                   name="fields[all]{{ !empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '' }}[{{ $field->slug }}]"
                   value="{!! isset($field->value) ? $field->value['id'] : '' !!}"
                   data-prefix="fields[all]"
                   data-name="{{ $field->slug }}"
            />
            @if(!empty($field->value))
                <div>
                    <div>
                        <i class="remove-image">-</i>
                        <img src="{{ !empty($field->value['image']) ? $field->value['image']->url() : '/uploads/no_image.jpg' }}" />
                    </div>
                </div>
                <div class="upload_image_button" data-type="single" data-extensions="image" style="display: none;">
                    <div class="add-btn"></div>
                </div>
            @else
                <div class="upload_image_button" data-type="single" data-extensions="image">
                    <div class="add-btn"></div>
                </div>
            @endif
        </div>
    </div>
</div>