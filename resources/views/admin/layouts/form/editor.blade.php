@php
    if(isset($locale) && (empty($languages) || count($languages) < 2)){
        $key .= '_'.$locale;
    }
    $id = str_replace(['[', ']'], '', $key);
@endphp
<div class="form-group{{ !empty($languages) ? ' js_langs' : '' }}">
    @if(!empty($languages))
        @foreach($languages as $lang_key => $lang_name)
            <div class="row js_lang lng_{{ $lang_key }}{{ $main_lang == $lang_key ? ' active_lang' : '' }}">
                <label class="col-sm-2 text-right{{ !empty($required) ? ' control-label' : '' }}">{{ $label }}</label>
                <div class="form-element col-sm-10">
                    <div id="wp-{{ $id }}_{{ $lang_key }}-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                        <div id="wp-{{ $id }}_{{ $lang_key }}-editor-tools" class="wp-editor-tools hide-if-no-js">
                            <div id="wp-{{ $id }}_{{ $lang_key }}-media-buttons" class="wp-media-buttons">
                                <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="{{ $id }}_{{ $lang_key }}"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
                            </div>
                            <div class="wp-editor-tabs">
                                <button type="button" id="{{ $id }}_{{ $lang_key }}-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="{{ $id }}_{{ $lang_key }}">Визуально</button>
                                <button type="button" id="{{ $id }}_{{ $lang_key }}-html" class="wp-switch-editor switch-html" data-wp-editor-id="{{ $id }}_{{ $lang_key }}">Текст</button>
                            </div>
                        </div>
                        <div id="wp-{{ $id }}_{{ $lang_key }}-editor-container" class="wp-editor-container">
                            <div id="qt_{{ $id }}_{{ $lang_key }}_toolbar" class="quicktags-toolbar"></div>
                            <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="{{ $key }}_{{ $lang_key }}" id="{{ $id }}_{{ $lang_key }}" placeholder="{{ $lang_name }}">{{ old($key.'_'.$lang_key) ? old($key.'_'.$lang_key) : (isset($item) ? $item->localize($lang_key, $key) : '') }}</textarea>
                        </div>
                    </div>
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
                <div id="wp-{{ $id }}-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                    <div id="wp-{{ $id }}-editor-tools" class="wp-editor-tools hide-if-no-js">
                        <div id="wp-{{ $id }}-media-buttons" class="wp-media-buttons">
                            <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="{{ $id }}"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
                        </div>
                        <div class="wp-editor-tabs">
                            <button type="button" id="{{ $id }}-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="{{ $id }}">Визуально</button>
                            <button type="button" id="{{ $id }}-html" class="wp-switch-editor switch-html" data-wp-editor-id="{{ $id }}">Текст</button>
                        </div>
                    </div>
                    <div id="wp-{{ $id }}-editor-container" class="wp-editor-container">
                        <div id="qt_{{ $id }}_toolbar" class="quicktags-toolbar"></div>
                        <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="{{ $key }}" id="{{ $id }}">
                                    {{ old($key) ? old($key) : (isset($locale) ? (isset($item) ? $item->localize($locale, isset($locale) ? substr($key, 0, -3) : $key) : '') : (isset($item) && !empty($item->$key) ? $item->$key : '')) }}
                                </textarea>
                    </div>
                </div>
                @if($errors->has($key))
                    <p class="warning" role="alert">{{ $errors->first($key,':message') }}</p>
                @endif
            </div>
        </div>
    @endif
</div>