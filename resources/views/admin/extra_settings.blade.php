@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Настройки магазина
@endsection
@section('content')

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Настройки доставки и оплаты</h1>
            </div>
        </div>
    </div>

    @if (session('message-success'))
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
                        <h4>Доступные методы доставки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Методы доставки</label>
                                <div class="form-element col-sm-10">
                                    <select name="delivery_methods[]" class="form-control chosen-select" multiple>
                                        @foreach($settings->delivery_methods as $method => $status)
                                            <option value="{{ $method }}"
                                                    @if($status)
                                                    selected
                                                    @endif
                                            >{{ $delivery_names[$method] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    @if($user->hasAccess(['settings.update']))
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Доступные методы оплаты</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Методы оплаты</label>
                                <div class="form-element col-sm-10">
                                    <select name="payment_methods[]" class="form-control chosen-select" multiple>
                                        @foreach($settings->payment_methods as $method => $status)
                                            <option value="{{ $method }}"
                                                    @if($status)
                                                    selected
                                                    @endif
                                            >{{ $payment_names[$method] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    @if($user->hasAccess(['settings.update']))
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки API Новая Почта</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Ключ API</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="newpost_api_key" value="{!! old('newpost_api_key', isset($settings->newpost_api_key) ? $settings->newpost_api_key : '') !!}" />
                                    @if($errors->has('newpost_api_key'))
                                        <p class="warning" role="alert">{!! $errors->first('newpost_api_key',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления областей Украины</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_regions_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        @foreach($update_period as $period)
                                            <option value="{!! $period['value'] !!}"
                                                    @if(isset($settings->newpost_regions_update_period) && $period['value'] == $settings->newpost_regions_update_period))
                                                    selected
                                                    @endif
                                            >{!! $period['period'] !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления областей Украины</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="{!! !empty($settings->newpost_regions_last_update) ? date('d.m.Y', $settings->newpost_regions_last_update) . ' г' : 'Нет данных, необходимо обновить!' !!}" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления городов Украины</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_cities_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        @foreach($update_period as $period)
                                            <option value="{!! $period['value'] !!}"
                                                    @if(isset($settings->newpost_cities_update_period) && $period['value'] == $settings->newpost_cities_update_period))
                                                    selected
                                                    @endif
                                            >{!! $period['period'] !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления городов Украины</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="{!! !empty($settings->newpost_cities_last_update) ? date('d.m.Y', $settings->newpost_cities_last_update) . ' г' : 'Нет данных, необходимо обновить!' !!}" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления отделений НП</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_warehouses_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        @foreach($update_period as $period)
                                            <option value="{!! $period['value'] !!}"
                                                    @if(isset($settings->newpost_warehouses_update_period) && $period['value'] == $settings->newpost_warehouses_update_period))
                                                    selected
                                                    @endif
                                            >{!! $period['period'] !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления отделений НП</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="{!! !empty($settings->newpost_warehouses_last_update) ? date('d.m.Y', $settings->newpost_warehouses_last_update) . ' г' : 'Нет данных, необходимо обновить!' !!}" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отправитель</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_sender_id" class="form-control">
                                        @foreach($np_senders as $sender)
                                            <option value="{{ $sender['Ref'] }}"{{ !empty($settings->newpost_sender_id) && $sender['Ref'] == $settings->newpost_sender_id }}>{{ $sender['Description'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отделение отправки</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_warehouse_sender_id" class="form-control">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->warehouse_id }}"{{ !empty($settings->newpost_warehouse_sender_id) && $warehouse->warehouse_id == $settings->newpost_warehouse_sender_id }}>{{ $warehouse->address_ru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if($user->hasAccess(['settings.update']))
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <a href="/admin/delivery-and-payment/newpost-update" class="btn btn-primary">Обновить</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    @if($user->hasAccess(['settings.update']))
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                    @endif
                                    <a href="/admin" class="btn btn-primary">На главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки WayForPay</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Аккаунт</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="wayforpay_account" value="{!! old('wayforpay_account', !empty($settings->wayforpay_account) ? $settings->wayforpay_account : '') !!}" />
                                    @if($errors->has('wayforpay_account'))
                                        <p class="warning" role="alert">{!! $errors->first('wayforpay_account',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Токен</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="wayforpay_secret" value="{!! old('wayforpay_secret', !empty($settings->wayforpay_secret) ? $settings->wayforpay_secret : '') !!}" />
                                    @if($errors->has('wayforpay_secret'))
                                        <p class="warning" role="alert">{!! $errors->first('wayforpay_secret',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тестовый режим</label>
                                <div class="form-element col-sm-10">
                                    <select name="wayforpay_sandbox" class="form-control">
                                        @if(old('wayforpay_sandbox') || !empty($settings->wayforpay_sandbox))
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        @elseif(!old('wayforpay_sandbox') || empty($settings->wayforpay_sandbox))
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    @if($user->hasAccess(['settings.update']))
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    @endif
                                    <a href="/admin" class="btn btn-primary">На главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{--<div class="form">--}}
        {{--<form method="post">--}}
            {{--{!! csrf_field() !!}--}}
            {{--<div class="panel-group">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4>Настройки API LiqPay</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right control-label">Публичный ключ API</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<input type="text" class="form-control" name="liqpay_api_public_key" value="{!! old('liqpay_api_public_key', $settings->liqpay_api_public_key) !!}" />--}}
                                    {{--@if($errors->has('liqpay_api_public_key'))--}}
                                        {{--<p class="warning" role="alert">{!! $errors->first('liqpay_api_public_key',':message') !!}</p>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right control-label">Приватный ключ API</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<input type="text" class="form-control" name="liqpay_api_private_key" value="{!! old('liqpay_api_private_key', $settings->liqpay_api_private_key) !!}" />--}}
                                    {{--@if($errors->has('liqpay_api_private_key'))--}}
                                        {{--<p class="warning" role="alert">{!! $errors->first('liqpay_api_private_key',':message') !!}</p>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right control-label">Валюта платежа</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<select name="liqpay_api_currency" class="form-control">--}}
                                        {{--<option value="0">Не выбрано</option>--}}
                                        {{--@foreach($currencies as $currency)--}}
                                            {{--<option value="{!! $currency !!}"--}}
                                                    {{--@if ($currency == $settings->liqpay_api_currency)--}}
                                                    {{--selected--}}
                                                    {{--@endif--}}
                                            {{-->{!! $currency !!}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right control-label">Тестовый режим</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<select name="liqpay_api_sandbox" class="form-control">--}}
                                        {{--@if(old('liqpay_api_sandbox') || $settings->liqpay_api_sandbox)--}}
                                            {{--<option value="1" selected>Включить</option>--}}
                                            {{--<option value="0">Выключить</option>--}}
                                        {{--@elseif(!old('liqpay_api_sandbox') || !$settings->liqpay_api_sandbox)--}}
                                            {{--<option value="1">Включить</option>--}}
                                            {{--<option value="0" selected>Выключить</option>--}}
                                        {{--@endif--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-10 col-sm-push-2 text-left">--}}
                                    {{--@if($user->hasAccess(['settings.update']))--}}
                                    {{--<button type="submit" class="btn btn-primary">Сохранить</button>--}}
                                    {{--@endif--}}
                                    {{--<a href="/admin" class="btn btn-primary">На главную</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</form>--}}
    {{--</div>--}}
@endsection
