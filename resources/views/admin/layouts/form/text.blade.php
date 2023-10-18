<div class="form-group{{ !empty($languages) ? ' js_langs' : '' }}">
    @if(!empty($languages))
        @foreach($languages as $lang_key => $lang_name)
            <div class="row js_lang lng_{{ $lang_key }}{{ $main_lang == $lang_key ? ' active_lang' : '' }}">
                <label class="col-sm-2 text-right{{ !empty($required) ? ' control-label' : '' }}">{{ $label }}</label>
                <div class="form-element col-sm-10">
                    <textarea class="form-control" rows="6" autocomplete="off" name="{{ $key }}_{{ $lang_key }}" placeholder="{{ $lang_name }}"{{ !empty($required) ? ' required' : '' }}>{{ old($key.'_'.$lang_key) ? old($key.'_'.$lang_key) : (isset($item) ? $item->localize($lang_key, $key) : '') }}</textarea>
                    @if($errors->has($key.'_'.$lang_key))
                        <p class="warning" role="alert">{{ $errors->first($key.'_'.$lang_key,':message') }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="row">
            <label class="col-sm-2 text-right{{ !empty($required) ? ' control-label' : '' }}">{{ $label }}</label>
            <div class="form-element col-sm-10">
                <textarea class="form-control" rows="6" autocomplete="off" name="{{ $key }}{{ isset($locale) ? '_'.$locale : '' }}"{{ !empty($required) ? ' required' : '' }}>{{ old($key) ? old($key) : (isset($locale) ? (isset($item) ? $item->localize($locale, $key) : '') : (isset($item) && !empty($item->$key) ? $item->$key : '')) }}</textarea>
                @if($errors->has($key))
                    <p class="warning" role="alert">{{ $errors->first($key,':message') }}</p>
                @endif
            </div>
        </div>
    @endif
</div>