<div class="row{{ !empty($field->langs) ? ' js_langs' : '' }}" style="flex-wrap: wrap">
    @if(!empty($field->langs))
        @foreach($fields as $lang => $lang_fields)
            <div class="col-xs-12 js_lang lng_{{ $lang }}{{ $main_lang == $lang ? ' active_lang' : '' }}">
                @include('admin.layouts.texteditor', [
                 'content' => isset($fields[$lang][$key]->value) ? $fields[$lang][$key]->value : '',
                 'editor_id' => 'fields['.$lang.']'.(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']',
                 'placeholder' => $locales_names[$lang],
                 'data-prefix' => 'fields['.$lang.']',
                 'data-name' => $field->slug
                ])
            </div>
        @endforeach
    @else
        <div class="col-xs-12">
            @include('admin.layouts.texteditor', [
                'content' => isset($field->value) ? $field->value : '',
                'editor_id' => 'fields[all]'.(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']',
                'data-prefix' => 'fields[all]',
                'data-name' => $field->slug
            ])
        </div>
    @endif
</div>