@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Настройки
@endsection
@section('content')

    <h1>Настройки магазина</h1>

    @if(session('message-success'))
        <div class="alert alert-success">
            {{ session('message-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Мета-теги</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Мета-тег Title</label>
                                <div class="form-element col-sm-10">
                                    @if(old('meta_title') !== null)
                                        <input type="text" class="form-control" name="meta_title" value="{!! old('meta_title') !!}" />
                                        @if($errors->has('meta_title'))
                                            <p class="warning" role="alert">{!! $errors->first('meta_title',':message') !!}</p>
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="meta_title" value="{!! $settings->meta_title !!}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Мета-тег Description</label>
                                <div class="form-element col-sm-10">
                                    @if(old('meta_description') !== null)
                                        <textarea name="meta_description" class="form-control" rows="6">{!! old('meta_description') !!}</textarea>
                                        @if($errors->has('meta_description'))
                                            <p class="warning" role="alert">{!! $errors->first('meta_description',':message') !!}</p>
                                        @endif
                                    @else
                                        <textarea name="meta_description" class="form-control" rows="6">{!! $settings->meta_description !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Мета-тег Keywords</label>
                                <div class="form-element col-sm-10">
                                    @if(old('meta_keywords') !== null)
                                        <textarea name="meta_keywords" class="form-control" rows="6">{!! old('meta_keywords') !!}</textarea>
                                        @if($errors->has('meta_description'))
                                            <p class="warning" role="alert">{!! $errors->first('meta_description',':message') !!}</p>
                                        @endif
                                    @else
                                        <textarea name="meta_keywords" class="form-control" rows="6">{!! $settings->meta_keywords !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Сообщение посетителям</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Сообщение</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="5" autocomplete="off" cols="40" name="site_message_ru" placeholder="На русском">{!! old('site_message_ru') ? old('site_message_ru') : (isset($settings->site_message_ru) ? $settings->site_message_ru : '')  !!}</textarea>
                                        </div>
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="5" autocomplete="off" cols="40" name="site_message_ua" placeholder="Українською">{!! old('site_message_ua') ? old('site_message_ua') : (isset($settings->site_message_ua) ? $settings->site_message_ua : '')  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Фоновый текст</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input class="form-control" name="site_message_bg_ru" type="text" value="{!! old('site_message_bg_ru') ? old('site_message_bg_ru') : (isset($settings->site_message_bg_ru) ? $settings->site_message_bg_ru : '')  !!}" placeholder="На русском">
                                        </div>
                                        <div class="col-xs-6">
                                            <input class="form-control" name="site_message_bg_ua" type="text" value="{!! old('site_message_bg_ua') ? old('site_message_bg_ua') : (isset($settings->site_message_bg_ua) ? $settings->site_message_bg_ua : '')  !!}" placeholder="Українською">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Включить</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <select name="site_message_enabled" class="form-control" autocomplete="off">
                                                <option value="1"{{ old('site_message_enabled', isset($settings->site_message_enabled) ? $settings->site_message_enabled : '') ? ' selected' : '' }}>Да</option>
                                                <option value="0"{{ old('site_message_enabled', isset($settings->site_message_enabled) ? $settings->site_message_enabled : '') ? '' : ' selected' }}>Нет</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>SMS шаблоны</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Реквизиты для оплаты</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <textarea class="form-control" rows="5" autocomplete="off" cols="40" name="sms_payment">{!! old('sms_payment') ? old('sms_payment') : (isset($settings->sms_payment) ? $settings->sms_payment : '')  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Транспортная накладная</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <textarea class="form-control" rows="5" autocomplete="off" cols="40" name="sms_delivery">{!! old('sms_delivery') ? old('sms_delivery') : (isset($settings->sms_delivery) ? $settings->sms_delivery : '')  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Промокод</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <textarea class="form-control" rows="5" autocomplete="off" cols="40" name="sms_promo">{!! old('sms_promo') ? old('sms_promo') : (isset($settings->sms_promo) ? $settings->sms_promo : '')  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Текст на главной странице</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Центр страницы</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="20" autocomplete="off" cols="40" name="landing_center_ru" placeholder="На русском">{!! old('landing_center_ru') ? old('landing_center_ru') : $settings->landing_center_ru  !!}</textarea>
                                        </div>
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="20" autocomplete="off" cols="40" name="landing_center_ua" placeholder="Українською">{!! old('landing_center_ua') ? old('landing_center_ua') : $settings->landing_center_ua  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Низ страницы</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div id="wp-landing_bottom_ru-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                                                <div id="wp-landing_bottom_ru-editor-tools" class="wp-editor-tools hide-if-no-js">
                                                    <div id="wp-landing_bottom_ru-media-buttons" class="wp-media-buttons">
                                                        <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="landing_bottom_ru"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
                                                    </div>
                                                    <div class="wp-editor-tabs">
                                                        <button type="button" id="landing_bottom_ru-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="landing_bottom_ru">Визуально</button>
                                                        <button type="button" id="landing_bottom_ru-html" class="wp-switch-editor switch-html" data-wp-editor-id="landing_bottom_ru">Текст</button>
                                                    </div>
                                                </div>
                                                <div id="wp-landing_bottom_ru-editor-container" class="wp-editor-container">
                                                    <div id="qt_landing_bottom_ru_toolbar" class="quicktags-toolbar"></div>
                                                    <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="landing_bottom_ru" id="landing_bottom_ru" placeholder="На русском">{!! old('landing_bottom_ru') ? old('landing_bottom_ru') : $settings->landing_bottom_ru  !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div id="wp-landing_bottom_ua-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                                                <div id="wp-landing_bottom_ua-editor-tools" class="wp-editor-tools hide-if-no-js">
                                                    <div id="wp-landing_bottom_ua-media-buttons" class="wp-media-buttons">
                                                        <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="landing_bottom_ua"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
                                                    </div>
                                                    <div class="wp-editor-tabs">
                                                        <button type="button" id="landing_bottom_ua-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="landing_bottom_ua">Визуально</button>
                                                        <button type="button" id="landing_bottom_ua-html" class="wp-switch-editor switch-html" data-wp-editor-id="landing_bottom_ua">Текст</button>
                                                    </div>
                                                </div>
                                                <div id="wp-landing_bottom_ua-editor-container" class="wp-editor-container">
                                                    <div id="qt_landing_bottom_ua_toolbar" class="quicktags-toolbar"></div>
                                                    <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="landing_bottom_ua" id="landing_bottom_ua" placeholder="Українською">{!! old('landing_bottom_ua') ? old('landing_bottom_ua') : $settings->landing_bottom_ua  !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4>Пользовательское соглашение</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Содержание</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<textarea id="text-area-terms" name="terms" class="form-control" rows="6">{!! old('terms') ? old('terms') : $settings->terms  !!}</textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Информация о доставке</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Содержание</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="20" autocomplete="off" cols="40" name="delivery_information_ru" placeholder="На русском">{!! old('delivery_information_ru') ? old('delivery_information_ru') : $settings->delivery_information_ru  !!}</textarea>
                                        </div>
                                        <div class="col-xs-6">
                                            <textarea class="form-control" rows="20" autocomplete="off" cols="40" name="delivery_information_ua" placeholder="Українською">{!! old('delivery_information_ua') ? old('delivery_information_ua') : $settings->delivery_information_ua  !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Телефоны</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Основной телефон</label>
                                <div class="form-element col-sm-10">
                                    @if(old('main_phone_1') !== null)
                                        <input type="text" class="form-control" name="main_phone_1" value="{!! old('main_phone_1') !!}" />
                                        @if($errors->has('main_phone_1'))
                                            <p class="warning" role="alert">{!! $errors->first('main_phone_1',':message') !!}</p>
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="main_phone_1" value="{!! $settings->main_phone_1 !!}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Дополнительный телефон</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--@if(old('main_phone_2') !== null)--}}
                                        {{--<input type="text" class="form-control" name="main_phone_2" value="{!! old('main_phone_2') !!}" />--}}
                                        {{--@if($errors->has('main_phone_2'))--}}
                                            {{--<p class="warning" role="alert">{!! $errors->first('main_phone_2',':message') !!}</p>--}}
                                        {{--@endif--}}
                                    {{--@else--}}
                                        {{--<input type="text" class="form-control" name="main_phone_2" value="{!! $settings->main_phone_2 !!}" />--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group phones">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дополнительные</label>
                                <div class="form-element col-sm-10">
                                    @if(old('other_phones'))
                                        @foreach(old('other_phones') as $key => $phone)
                                            <div class="input-group">
                                                <input type="text" name="other_phones[]" class="form-control" value="{!! $phone !!}" />
                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                            </div>
                                            @if($errors->has('other_phones.' . $key))
                                                <p class="warning" role="alert">{!! $errors->first('other_phones.' . $key,':message') !!}</p>
                                            @endif
                                        @endforeach
                                        @foreach(old('other_phones') as $key => $phone)
                                            <div class="input-group">
                                                <input type="text" name="other_phones[]" class="form-control" value="{!! $phone !!}" />
                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                            </div>
                                            @if($errors->has('other_phones.' . $key))
                                                <p class="warning" role="alert">{!! $errors->first('other_phones.' . $key,':message') !!}</p>
                                            @endif
                                        @endforeach
                                    @elseif($settings->other_phones !== null)
                                        @foreach($settings->other_phones as $phone)
                                            <div class="input-group">
                                                <input type="text" name="other_phones[]" class="form-control" value="{!! $phone !!}" />
                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                    <button type="button" class="btn btn-primary" id="button-add-telephone">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Почта, на которую будут приходить заказы и заявки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group emails">
                            <div class="row">
                                <label class="col-sm-2 text-right">E-mail</label>
                                <div class="form-element col-sm-10">
                                    @if(old('notify_emails'))
                                        @foreach(old('notify_emails') as $key => $email)
                                            <div class="input-group">
                                                <input type="text" name="notify_emails[]" class="form-control" value="{!! $email !!}" />
                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                            </div>
                                            @if($errors->has('notify_emails.' . $key))
                                                <p class="warning" role="alert">{!! $errors->first('notify_emails.' . $key,':message') !!}</p>
                                            @endif
                                        @endforeach
                                    @elseif($settings->notify_emails !== null && is_array($settings->notify_emails))
                                        @foreach($settings->notify_emails as $email)
                                            <div class="input-group">
                                                <input type="text" name="notify_emails[]" class="form-control" value="{!! $email !!}" />
                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Удалить" onclick="$(this).parent().remove();">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                    <button type="button" class="btn btn-primary" id="button-add-email">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Курсы валют</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Гривен за доллар</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="usd_rate" value="{{ isset($settings->usd_rate) ? $settings->usd_rate : '' }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Гривен за евро</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="eur_rate" value="{{ isset($settings->eur_rate) ? $settings->eur_rate : '' }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->hasAccess(['settings.update']))
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
@endsection
@include('admin.layouts.mce', ['editors' => ['landing_bottom_ru', 'landing_bottom_ua']])
@section('before_footer')
    @include('admin.media.assets')
@endsection