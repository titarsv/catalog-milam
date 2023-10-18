@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог товаров
@endsection
@section('content')

    <h1>Добавление товара</h1>

    @if(session('message-error'))
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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображения товара</label>
                                <div class="form-element col-sm-10">
                                    @include('admin.layouts.form.gallery', [
                                     'key' => 'gallery',
                                     'gallery' => null
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Документы / Сертификаты</label>
                                <div class="form-element col-sm-10">
                                    @include('admin.layouts.form.gallery', [
                                     'key' => 'documents',
                                     'gallery' => null
                                    ])
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.form.editor', [
                         'label' => 'Описание товара',
                         'key' => 'description'
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Инструкция по применению',
                         'key' => 'instructions'
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Меры безопасности',
                         'key' => 'security'
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Состав',
                         'key' => 'compound'
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'Срок годности',
                         'key' => 'shelf_life'
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'Условия хранения',
                         'key' => 'storage_conditions'
                        ])
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Артикул',--}}
                         {{--'key' => 'sku',--}}
                         {{--'required' => false,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'GTIN',--}}
                         {{--'key' => 'gtin',--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Базовая цена',--}}
                         {{--'key' => 'price',--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Акционная цена</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<div class="input-group group">--}}
                                        {{--<span class="input-group-addon">--}}
                                            {{--<input type="checkbox" aria-label="Checkbox for following text input" value="1" name="sale"{{ old('sale') ? ' checked' : '' }}>--}}
                                        {{--</span>--}}
                                        {{--<div class="schedule">--}}
                                            {{--<div class="input-group">--}}
                                                {{--<label for="sale_price" class="input-group-addon">Цена со скидкой</label>--}}
                                                {{--<input type="text" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') ? old('sale_price') : '' }}">--}}
                                            {{--</div>--}}
                                            {{--<div class="input-group" style="width: 172px;">--}}
                                                {{--<label for="sale_from" class="input-group-addon">С</label>--}}
                                                {{--<input type="text" id="sale_from" class="form-control from" name="sale_from" value="" style="width: 138px;">--}}
                                            {{--</div>--}}
                                            {{--<div class="input-group" style="width: 172px;">--}}
                                                {{--<label for="sale_to" class="input-group-addon">По</label>--}}
                                                {{--<input type="text" id="sale_to" class="form-control to" name="sale_to" value="" style="width: 138px;">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        @include('admin.layouts.form.select', [
                         'label' => 'Отображение на сайте',
                         'key' => 'visible',
                         'required' => true,
                         'options' => [(object)['id' => 0, 'name' => 'Скрыть'], (object)['id' => 1, 'name' => 'Отображать']],
                         'selected' => [1],
                         'languages' => null
                        ])
                        {{--@include('admin.layouts.form.select', [--}}
                         {{--'label' => 'Наличие',--}}
                         {{--'key' => 'stock',--}}
                         {{--'required' => true,--}}
                         {{--'options' => [(object)['id' => '1', 'name' => 'В наличии'], (object)['id' => '-2', 'name' => 'Нет в наличии'], (object)['id' => '0', 'name' => 'Ожидается'], (object)['id' => '-1', 'name' => 'Под заказ']],--}}
                         {{--'selected' => [old('visible')]--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Остаток',--}}
                         {{--'key' => 'stock',--}}
                         {{--'required' => true,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Приоритет сортировки',--}}
                         {{--'key' => 'sort_priority',--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связи</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.select', [
                         'label' => 'Категория товара',
                         'key' => 'product_category_id',
                         'options' => $categories,
                         'multiple' => true,
                         'selected' => old('parent_id') ? old('parent_id') : []
                        ])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Атрибуты товара</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="table table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="success">
                                                <td align="center">Выберите атрибут</td>
                                                <td align="center">Выберите значение атрибута</td>
                                                <td align="center">Действия</td>
                                            </tr>
                                        </thead>
                                        <tbody id="product-attributes">
                                            @if(old('product_attributes') !== null)
                                                @if(session('attributes_error'))
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="warning" role="alert">{!! session('attributes_error') !!}</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @foreach(old('product_attributes') as $key => $attr)
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" onchange="getAttributeValues($(this).val(), '{!! $key !!}')">
                                                                @foreach($attributes as $attribute)
                                                                    <option value="{!! $attribute->id !!}"
                                                                        @if ($attribute->id == $attr['id'])
                                                                            selected
                                                                        @endif
                                                                    >{!! $attribute->name !!}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td align="center" id="attribute-{!! $key !!}-values">
                                                            <input type="hidden" name="product_attributes[{!! $key !!}][id]" value="{!! $attr['id'] !!}"/>
                                                            <select class="form-control" name="product_attributes[{!! $key !!}][value]">';
                                                                @foreach($attributes as $attribute)
                                                                    @if($attribute->id == $attr['id'])
                                                                        @foreach($attribute->values as $value)
                                                                            <option value="{!! $value->id !!}"
                                                                                @if ($value->id == $attr['value'])
                                                                                    selected
                                                                                @endif
                                                                            >{!! $value->name !!}</option>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td align="center">
                                                            <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <input type="hidden" value="{!! $key !!}" id="attributes-iterator" />
                                            @else
                                                <input type="hidden" value="0" id="attributes-iterator" />
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td align="center">
                                                    <button type="button" id="add-attribute" onclick="getAttributes();" class="btn btn-primary">Добавить</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h4>Вариации товара</h4>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<div class="form-element col-sm-12">--}}
                                    {{--<button style="float: right;" type="button" id="add-variation" onclick="addVariation($(this));" class="btn btn-primary">Добавить вариацию</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                @include('admin.layouts.seo')
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="/css/larchik/jquery.datetimepicker.min.css" />
    <style>
        .schedule{
            display: flex;
        }
        .schedule > .input-group{
            margin-bottom: 0;
        }
        .save-panel{
            position: fixed;
            bottom: 0;
            width: calc(83vw - 15px);
            right: 0;
            margin: 0 !important;
            padding-left: 25px;
            padding-right: 25px;
            z-index: 10;
            opacity: 0.75;
            transition: opacity .2s ease-in;
        }
        .save-panel:hover{
            opacity: 1;
        }
        .save-panel .panel-body{
            padding: 10px 15px;
        }
        .image-container > div > div > span{
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            background-color: #2d3e50;
            color: #fff;
            z-index: 5;
        }
    </style>
    <script src="/js/larchik/jquery.datetimepicker.full.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            jQuery.datetimepicker.setLocale('ru');
            $('.from, .to').datetimepicker({
                datepicker:true,
                step:30
            });
        });
    </script>

    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
