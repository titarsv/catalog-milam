@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог товаров
@endsection
@section('content')

    <h1>Редактирование товара</h1>

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
            <input type="hidden" name="prev" value="{{ $prev }}">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{ $prev }}" style="float: left;display: block;width: 15px;font-size: 18px;line-height: 34px;color: #f00;text-align: center;margin-right: 10px;"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'required' => true,
                         'item' => $product
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображение товара</label>
                                <div class="form-element col-sm-10">
                                    @include('admin.layouts.form.gallery', [
                                     'key' => 'gallery',
                                     'gallery' => $product->gallery
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
                                     'gallery' => $product->documents
                                    ])
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.form.editor', [
                         'label' => 'Описание товара',
                         'key' => 'description',
                         'item' => $product
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Инструкция по применению',
                         'key' => 'instructions',
                         'item' => $product
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Меры безопасности',
                         'key' => 'security',
                         'item' => $product
                        ])
                        @include('admin.layouts.form.editor', [
                         'label' => 'Состав',
                         'key' => 'compound',
                         'item' => $product
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'Срок годности',
                         'key' => 'shelf_life',
                         'item' => $product
                        ])
                        @include('admin.layouts.form.string', [
                         'label' => 'Условия хранения',
                         'key' => 'storage_conditions',
                         'item' => $product
                        ])
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Артикул',--}}
                         {{--'key' => 'sku',--}}
                         {{--'item' => $product,--}}
                         {{--'required' => true,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'GTIN',--}}
                         {{--'key' => 'gtin',--}}
                         {{--'item' => $product,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Базовая цена',--}}
                         {{--'key' => 'original_price',--}}
                         {{--'item' => $product,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Акционная цена</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<div class="input-group group">--}}
                                        {{--<span class="input-group-addon">--}}
                                            {{--<input type="checkbox" value="1" name="sale"{{ old('sale', $product->sale) ? ' checked' : '' }}>--}}
                                        {{--</span>--}}
                                        {{--<div class="schedule">--}}
                                            {{--<div class="input-group">--}}
                                                {{--<label for="sale_price" class="input-group-addon">Цена со скидкой</label>--}}
                                                {{--<input type="text" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') ? old('sale_price') : $product->sale_price }}">--}}
                                            {{--</div>--}}
                                            {{--<div class="input-group" style="width: 172px;">--}}
                                                {{--<label for="sale_from" class="input-group-addon">С</label>--}}
                                                {{--<input type="text" id="sale_from" class="form-control from" name="sale_from" value="{{ old('sale_from') ? old('sale_from') : $product->sale_from }}" style="width: 138px;">--}}
                                            {{--</div>--}}
                                            {{--<div class="input-group" style="width: 172px;">--}}
                                                {{--<label for="sale_to" class="input-group-addon">По</label>--}}
                                                {{--<input type="text" id="sale_to" class="form-control to" name="sale_to" value="{{ old('sale_to') ? old('sale_to') : $product->sale_to }}" style="width: 138px;">--}}
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
                         'selected' => [old('visible', $product->visible)]
                        ])
                        {{--@include('admin.layouts.form.select', [--}}
                         {{--'label' => 'Наличие',--}}
                         {{--'key' => 'stock',--}}
                         {{--'required' => true,--}}
                         {{--'options' => [(object)['id' => '1', 'name' => 'В наличии'], (object)['id' => '-2', 'name' => 'Нет в наличии'], (object)['id' => '0', 'name' => 'Ожидается'], (object)['id' => '-1', 'name' => 'Под заказ']],--}}
                         {{--'selected' => [old('visible', $product->stock)]--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Остаток',--}}
                         {{--'key' => 'stock',--}}
                         {{--'item' => $product,--}}
                         {{--'required' => true,--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Приоритет сортировки',--}}
                         {{--'key' => 'sort_priority',--}}
                         {{--'item' => $product,--}}
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
                         'label' => 'Категории товара',
                         'key' => 'product_category_id',
                         'multiple' => true,
                         'required' => true,
                         'options' => $categories,
                         'selected' => $added_categories
                        ])
                        @include('admin.layouts.form.select', [
                         'label' => 'Связанные товары',
                         'key' => 'related',
                         'required' => false,
                         'multiple' => true,
                         'options' => $sets,
                         'selected' => (array)old('related', $related)
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
                                            @if($user->hasAccess(['products.update']))
                                            <td align="center">Действия</td>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody id="product-attributes">
                                        @if(old('product_attributes') !== null)
                                            @if(session('attributes_error'))
                                                <tr>
                                                    <td colspan="3">
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
                                                        <select class="form-control" name="product_attributes[{!! $key !!}][value]">
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
                                                    @if($user->hasAccess(['products.update']))
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <input type="hidden" value="{!! $key !!}" id="attributes-iterator" />
                                        @else
                                            @forelse($product_attributes as $key => $attr)
                                                <tr>
                                                    <td>
                                                        <select class="form-control" onchange="getAttributeValues($(this).val(), '{!! $key !!}')">
                                                            @foreach($attributes as $attribute)
                                                                <option value="{!! $attribute->id !!}"
                                                                        @if ($attribute->id == $attr['attribute_id'])
                                                                        selected
                                                                        @endif
                                                                >{!! $attribute->name !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td align="center" id="attribute-{!! $key !!}-values">
                                                        <input type="hidden" name="product_attributes[{!! $key !!}][id]" value="{!! $attr['attribute_id'] !!}"/>
                                                        <select class="form-control" name="product_attributes[{!! $key !!}][value]">
                                                            @foreach($attributes as $attribute)
                                                                @if($attribute->id == $attr['attribute_id'])
                                                                    @foreach($attribute->values as $value)
                                                                        <option value="{!! $value->id !!}"
                                                                                @if ($value->id == $attr['attribute_value_id'])
                                                                                selected
                                                                                @endif
                                                                        >{!! $value->name !!}</option>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    @if($user->hasAccess(['products.update']))
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                    @endif
                                                </tr>

                                                @if($key == count($product->attributes) - 1)
                                                    <input type="hidden" value="{!! $key !!}" id="attributes-iterator" />
                                                @endif
                                            @empty
                                                <input type="hidden" value="0" id="attributes-iterator" />
                                            @endforelse
                                        @endif
                                        </tbody>
                                        @if($user->hasAccess(['products.update']))
                                        <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td align="center">
                                                <button type="button" id="add-attribute" onclick="getAttributes();" class="btn btn-primary">Добавить</button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                        @endif
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
                        {{--@foreach($product->variations as $i => $variation)--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="row">--}}
                                    {{--<label class="col-sm-2 text-right">Цена</label>--}}
                                    {{--<div class="form-element col-sm-4">--}}
                                        {{--<input type="text" class="form-control variation-price" name="variations[{{ $i }}][price]" value="{!! $variation->price !!}" />--}}
                                        {{--@if($errors->has('robots'))--}}
                                            {{--<p class="warning" role="alert">{!! $errors->first('robots',':message') !!}</p>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                    {{--<label class="col-sm-2 text-right">Наличие вариации</label>--}}
                                    {{--<div class="form-element col-sm-4">--}}
                                        {{--<select name="variations[{{ $i }}][stock]" class="form-control">--}}
                                            {{--@if(old('stock') || $variation->stock)--}}
                                                {{--<option value="1" selected>В наличии</option>--}}
                                                {{--<option value="0">Нет в наличии</option>--}}
                                            {{--@elseif(!old('stock') || !$variation->stock)--}}
                                                {{--<option value="1">В наличии</option>--}}
                                                {{--<option value="0" selected>Нет в наличии</option>--}}
                                            {{--@endif--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="table table-responsive">--}}
                                        {{--<table class="table table-hover">--}}
                                            {{--<thead>--}}
                                            {{--<tr class="success">--}}
                                                {{--<td align="center">Выберите атрибут</td>--}}
                                                {{--<td align="center">Выберите значение атрибута</td>--}}
                                                {{--<td align="center">Действия</td>--}}
                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody class="product-attributes">--}}
                                            {{--@forelse($variation->attribute_values as $key => $val)--}}
                                                {{--@php $attr_id = $val->attribute->id @endphp--}}
                                                {{--<tr>--}}
                                                    {{--<td>--}}
                                                        {{--<select class="form-control" onchange="getVariationAttributeValues($(this), {{ $i }})">--}}
                                                            {{--@foreach($attributes as $attribute)--}}
                                                                {{--@if(in_array($attribute->id, [9,12]))--}}
                                                                {{--<option value="{!! $attribute->id !!}"--}}
                                                                        {{--@if ($attribute->id == $attr_id)--}}
                                                                        {{--selected--}}
                                                                        {{--@endif--}}
                                                                {{-->{!! $attribute->name !!}</option>--}}
                                                                {{--@endif--}}
                                                            {{--@endforeach--}}
                                                        {{--</select>--}}
                                                    {{--</td>--}}
                                                    {{--<td align="center">--}}
                                                        {{--<input type="hidden" name="variations[{{ $i }}][id][{!! $key !!}]" value="{!! $val->id !!}" class="variation_value"/>--}}
                                                        {{--<select class="form-control variation_value" name="variations[{{ $i }}][id][{!! $key !!}]">--}}
                                                            {{--@foreach($attributes as $attribute)--}}
                                                                {{--@if($attribute->id == $attr_id)--}}
                                                                    {{--@foreach($attribute->values as $value)--}}
                                                                        {{--<option value="{!! $value->id !!}"--}}
                                                                                {{--@if ($value->id == $val->id)--}}
                                                                                {{--selected--}}
                                                                                {{--@endif--}}
                                                                        {{-->{!! $value->name !!}</option>--}}
                                                                    {{--@endforeach--}}
                                                                {{--@endif--}}
                                                            {{--@endforeach--}}
                                                        {{--</select>--}}
                                                    {{--</td>--}}
                                                    {{--<td align="center">--}}
                                                        {{--@if($user->hasAccess(['products.update']))--}}
                                                        {{--<button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>--}}
                                                        {{--@endif--}}
                                                        {{--@if($key == count($variation->attribute_values) - 1)--}}
                                                            {{--<input type="hidden" value="{!! $key !!}" class="attributes-iterator" />--}}
                                                        {{--@endif--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                            {{--@empty--}}
                                                {{--<tr>--}}
                                                    {{--<td colspan="3" align="center">--}}
                                                        {{--<input type="hidden" value="0" class="attributes-iterator" />--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                            {{--@endforelse--}}
                                            {{--</tbody>--}}
                                            {{--@if($user->hasAccess(['products.update']))--}}
                                            {{--<tfoot>--}}
                                            {{--<tr>--}}
                                                {{--<td colspan="2"></td>--}}
                                                {{--<td align="center">--}}
                                                    {{--<button type="button" onclick="getVariationAttributes($(this), {{ $i }});" class="btn btn-primary add-attribute add-var-attr">Добавить атрибут</button>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--</tfoot>--}}
                                            {{--@endif--}}
                                        {{--</table>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                        {{--@if($user->hasAccess(['products.update']))--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<div class="form-element col-sm-12">--}}
                                    {{--<button style="float: right;" type="button" id="add-variation" onclick="addVariation($(this));" class="btn btn-primary">Добавить вариацию</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
                @include('admin.layouts.seo')
                @if($user->hasAccess(['products.update']))
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
            $('[type="submit"]').click(function(){
                console.log($(this).parents('form'));
                $(this).parents('form').submit();
            });

            jQuery.datetimepicker.setLocale('ru');
            $('.from, .to').datetimepicker({
                datepicker:true,
                step:30
            });

            $('.search-products').each(function(){
                var select = $(this);
                select.chosen({
                    placeholder_text_multiple: "Введите название товара",
                    no_results_text: "Ничего не найдено!"
                });
                var input = select.next().find('input');
                input.autocomplete({
                    source: function (request, response) {
                        $search_param = input.val();
                        var data = {
                            search: $search_param
                        };
                        if ($search_param.length > 3) { //отправлять поисковой запрос к базе, если введено более трёх символов
                            $.post('/admin/products/livesearch', data, function onAjaxSuccess(data) {
                                if (typeof data[0].empty !== 'undefined') {
                                    data = [];
                                }
                                if (data.length != 0) {
                                    select.next().find('ul.chosen-results').find('li').each(function () {
                                        $(this).remove();//отчищаем выпадающий список перед новым поиском
                                    });
                                    select.find('option').not(':selected').each(function () {
                                        $(this).remove(); //отчищаем поля перед новым поисков
                                    });
                                }
                                for (var id in data) {
                                    select.append('<option value="' + data[id].product_id + '">' + data[id].name + '</option>');
                                }
                                select.trigger("chosen:updated");
                                input.val($search_param);
                                anSelected = select.val();
                            });
                        }
                    }
                });
            });
        });
    </script>

    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
