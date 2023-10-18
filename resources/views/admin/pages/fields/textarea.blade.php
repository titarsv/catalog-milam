<div class="row{{ !empty($field->langs) ? ' js_langs' : '' }}" style="flex-wrap: wrap">
    @if(!empty($field->langs))
        @foreach($fields as $lang => $lang_fields)
            <div class="col-xs-12 js_lang lng_{{ $lang }}{{ $main_lang == $lang ? ' active_lang' : '' }}">
            <textarea class="form-control"
                      name="fields[{{ $lang }}]{{ !empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '' }}[{{ $field->slug }}]"
                      placeholder="{{ $locales_names[$lang] }}"
                      data-prefix="fields[{{ $lang }}]"
                      data-name="{{ $field->slug }}"
            >{!! isset($fields[$lang][$key]->value) ? $fields[$lang][$key]->value : '' !!}</textarea>
            </div>
        @endforeach
    @else
        <div class="col-xs-12">
            <textarea class="form-control"
                      name="fields[all]{{ !empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '' }}[{{ $field->slug }}]"
                      data-prefix="fields[all]"
                      data-name="{{ $field->slug }}"
            >{!! isset($field->value) ? $field->value : '' !!}</textarea>
        </div>
    @endif
</div>