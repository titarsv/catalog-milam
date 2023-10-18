@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    {{ $export->name }}
@endsection
@section('content')
    <h1>{{ $export->name }}</h1>

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
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Название</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" data-translit="input" class="form-control" name="name" value="{!! old('name') ? old('name') : $export->name !!}" />
                                    @if($errors->has('name'))
                                        <p class="warning" role="alert">{!! $errors->first('name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тип</label>
                                <div class="form-element col-sm-10">
                                    <select name="type" class="form-control">
                                        @foreach(['csv', 'xls', 'xml', 'rss', 'json'] as $type)
                                            <option value="{{ $type }}"
                                                    @if ($type == (old('type') ? old('type') : $export->type))
                                                    selected
                                                    @endif
                                            >{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Url</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="url" value="{!! old('url') ? old('url') : $export->url !!}" />
                                    @if($errors->has('url'))
                                        <p class="warning" role="alert">{!! $errors->first('url',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Экспортируемые поля</h4>
                    </div>
                    <div class="panel-body" id="export_fields">
                        <div class="form-group">
                            <div class="row">
                                <div class="form-element col-sm-3">
                                    <label for="">Название</label>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label for="">Поле</label>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label for="">Модификаторы</label>
                                </div>
                            </div>
                        </div>
                        @foreach($export->structure as $fi => $field)
                            <div class="form-group">
                                <div class="row export_field" data-id="{{ $fi }}">
                                    <div class="form-element col-sm-3">
                                        <input type="text" class="form-control" name="fields[{{ $fi }}][name]" placeholder="Название" value="{!! old('fields['.$fi.'][name]') ? old('fields['.$fi.'][name]') : $field->name !!}" autocomplete="off" />
                                    </div>
                                    <div class="form-element col-sm-3">
                                        <select class="form-control field_type" name="fields[{{ $fi }}][field][type]" autocomplete="off">
                                            @foreach($export->getFieldTypes() as $field_type => $name)
                                                <option value="{{ $field_type }}"{{ $field->field->type == $field_type ? ' selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @if(!empty($field->field->custom))
                                            <input type="text" class="form-control" name="fields[{{ $fi }}][field][custom]" placeholder="Введите своё значение" value="{{ $field->field->custom }}">
                                        @elseif(!empty($field->field->attribute))
                                            <select class="form-control" name="fields[{{ $fi }}][field][attribute]">
                                                @foreach($all_attributes as $attr)
                                                    <option value="{{ $attr['id'] }}"{{ $attr['id'] == $field->field->attribute ? ' selected' : '' }}>{{ $attr['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="form-element col-sm-4 modifications">
                                        @foreach($field->modifications as $i => $field_modification)
                                            @if(isset($field_modification->type))
                                                <div class="modification" data-id="{{ $i }}">
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <select class="form-control" name="fields[{{ $fi }}][modifications][{{ $i }}][type]" autocomplete="off">
                                                                @foreach($export->getModifications() as $key => $modification)
                                                                    <option value="{{ $key }}"{{ $field_modification->type == $key ? ' selected' : '' }}>{{ $modification }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if(isset($field_modification->value))
                                                                <input type="text" class="form-control" name="fields[{{ $fi }}][modifications][{{ $i }}][value]" placeholder="Введите значение" value="{{ $field_modification->value }}">
                                                            @elseif(isset($field_modification->from) && isset($field_modification->to))
                                                                <input type="text" class="form-control" name="fields[{{ $fi }}][modifications][{{ $i }}][from]" placeholder="Что заменить" value="{{ $field_modification->from }}">
                                                                <input type="text" class="form-control" name="fields[{{ $fi }}][modifications][{{ $i }}][to]" placeholder="Чем заменить" value="{{ $field_modification->to }}">
                                                            @endif
                                                        </div>
                                                        <div class="col-xs-3">
                                                            @if($i == 0)
                                                                <button type="button" class="btn btn-primary add_export_field_modification">
                                                                    <i class="glyphicon glyphicon-plus"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-primary remove_export_field_modification">
                                                                    <i class="glyphicon glyphicon-minus"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="modification" data-id="0">
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <select class="form-control" name="fields[{{ $fi }}][modifications][0][type]">
                                                                <option value=""></option>
                                                                <option value="replace_all">Замена всего содержимого</option>
                                                                <option value="replace_part">Замена части содержимого</option>
                                                                <option value="add_prefix">Добавление перед</option>
                                                                <option value="add_suffix">Добавление после</option>
                                                                <option value="add_num">Увеличение на</option>
                                                                <option value="multiple">Увеличение в</option>
                                                                <option value="translit">Транслит</option>
                                                                <option value="strip_tags">Удалить HTML</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <button type="button" class="btn btn-primary add_export_field_modification">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="form-element col-sm-1 text-right">

                                    </div>
                                    <div class="form-element col-sm-1 text-right">
                                        <button type="button" class="btn btn-danger remove_export_field">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="add_export_field">Добавить поле</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Фильтр товаров</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="filter-popup">
                                    <div id="filter-form">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                @foreach($export->filters as $ig => $filter_group)
                                                    <div class="form-group" data-group-id="{{ $ig }}">
                                                        @foreach($filter_group as $if => $filter)
                                                            <div class="row condition" data-id="{{ $if }}">
                                                                <div class="form-element {{ $filter->criterion == 'status' ? 'col-sm-6' : 'col-sm-4' }}">
                                                                    <label class="text-right">Критерий фильтрации:</label>
                                                                    <select name="filter[{{ $ig }}][{{ $if }}][criterion]" class="form-control criterion" autocomplete="off">
                                                                        @foreach([""=>"","category"=>"Категория","attribute"=>"Атрибут","status"=>"Наличие","price"=>"Цена"] as $key => $val)
                                                                            <option value="{{ $key }}"{{ $key == $filter->criterion ? ' selected' : '' }}>{{ $val }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @if($filter->criterion == 'category')
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][value]" class="form-control criterion">
                                                                            @foreach($categories as $category)
                                                                                <option value="{{ $category->id }}"{{ $filter->value == $category->id ? ' selected' : '' }}>{{ $category->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][condition]" class="form-control criterion">
                                                                            <option value="with_child"{{ $filter->condition == 0 ? ' selected' : '' }}>Включая дочерние</option>
                                                                            <option value="without_child"{{ $filter->condition == 0 ? ' selected' : '' }}>Без дочерних</option>
                                                                        </select>
                                                                    </div>
                                                                @elseif($filter->criterion == 'attribute')
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Атрибут:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][attribute]" class="form-control criterion">
                                                                            @foreach($all_attributes as $attribute)
                                                                                <option value="{{ $attribute->id }}"{{ $filter->attribute == $attribute->id ? ' selected' : '' }}>{{ $attribute->name }}</option>
                                                                                @php
                                                                                    if($filter->attribute == $attribute->id){
                                                                                        $current_attribute = $filter->attribute;
                                                                                    }
                                                                                @endphp
                                                                            @endforeach
                                                                            @php
                                                                                if(!isset($current_attribute)){
                                                                                    $current_attribute = $all_attributes[0];
                                                                                }
                                                                            @endphp
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][value]" class="form-control criterion">
                                                                            @foreach($current_attribute['values'] as $value)
                                                                                <option value="{{ $value['id'] }}"{{ $filter->value == $value['id'] ? ' selected' : ''  }}>{{ $value['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @elseif($filter->criterion == 'status')
                                                                    <div class="form-element col-sm-6">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][value]" class="form-control criterion">
                                                                            <option value="0"{{ $filter->value == 0 ? ' selected' : '' }}>Нет в наличии</option>
                                                                            <option value="1"{{ $filter->value == 1 ? ' selected' : '' }}>В наличии</option>
                                                                        </select>
                                                                    </div>
                                                                @elseif($filter->criterion == 'price')
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                        <select name="filter[{{ $ig }}][{{ $if }}][condition]" class="form-control criterion">
                                                                            <option value="="{{ $filter->condition == '=' ? ' selected' : '' }}>Равна</option>
                                                                            <option value=">"{{ $filter->condition == '>' ? ' selected' : '' }}>Больше</option>
                                                                            <option value="<"{{ $filter->condition == '<' ? ' selected' : '' }}>Меньше</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение</label>
                                                                        <input type="number" name="filter[{{ $ig }}][{{ $if }}][value]" step="0.01" class="form-control value" value="{{ $filter->value }}">
                                                                    </div>
                                                                @else
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение</label>

                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                        <div class="row">
                                                            <div class="col-sm-12 text-center buttons">
                                                                <button type="button" class="btn btn-primary add_sub_condition">Добавить условие</button>
                                                                {{--<button type="button" class="btn btn-primary add_sub_condition_group">Добавить группу условий</button>--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                {{--<button type="button" class="btn" id="add_condition">Добавить условие</button>--}}
                                                <button type="button" class="btn btn-primary" id="add_condition_group">Добавить группу условий</button>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                {{--<button type="submit" class="btn btn-primary add_sub_condition">Применить</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Частота обновления</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Частота обновления</label>
                                <div class="form-element col-sm-10">
                                    <select class="form-control field_type" name="schedule" autocomplete="off">
                                        <option value="">не обновлять</option>
                                        @foreach($export->getSchedulesNames() as $schedule => $name)
                                            <option value="{{ $schedule }}"{{ isset($export->schedule->method) && $schedule == $export->schedule->method ? ' selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($user->hasAccess(['export.update']))
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

    <script>
        window.categories = {!! json_encode($categories) !!};
        window.attributes = {!! json_encode($all_attributes) !!};
        window.export = {
            field_types: {!! json_encode($export->getFieldTypes()) !!},
            modifications: {!! json_encode($export->getModifications()) !!}
        };
        jQuery(document).ready(function(){
            $('#add_export_field').click(function(){
                if($('.export_field').length){
                    var field_id = $('.export_field').last().data('id') + 1;
                }else{
                    var field_id = 0;
                }

                $(this).parents('.form-group').before('<div class="form-group">\n' +
                    '    <div class="row export_field" data-id="'+field_id+'">\n' +
                    '        <div class="form-element col-sm-3">\n' +
                    '            <input type="text" class="form-control" name="fields['+field_id+'][name]" placeholder="Название" value="" />\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-3">\n' +
                    '            <select class="form-control field_type" name="fields['+field_id+'][field][type]">\n' +
                    field_type_options() +
                    '            </select>\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-4 modifications">\n' +
                    '            <div class="modification" data-id="0">\n' +
                    '               <div class="row">\n' +
                    '                   <div class="col-xs-9">\n' +
                    '                       <select class="form-control" name="fields['+field_id+'][modifications][0][type]">\n' +
                    modifications_options() +
                    '                       </select>\n' +
                    '                   </div>\n' +
                    '                   <div class="col-xs-3">\n' +
                    '                       <button type="button" class="btn btn-primary add_export_field_modification">\n' +
                    '                           <i class="glyphicon glyphicon-plus"></i>\n' +
                    '                       </button>\n' +
                    '                   </div>\n' +
                    '               </div>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-1 text-right">\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-1 text-right">\n' +
                    '            <button type="button" class="btn btn-danger remove_export_field">\n' +
                    '                <i class="glyphicon glyphicon-trash"></i>\n' +
                    '            </button>\n' +
                    '        </div>\n' +
                    '    </div>\n' +
                    '</div>');
            });
            $('#export_fields').on('click', '.add_export_field_modification', function(){
                let modifications = $(this).parents('.export_field').find('.modifications');
                let field_id = $(this).parents('.export_field').data('id');
                let modification_id = modifications.find('.modification').length;
                modifications.append('<div class="modification" data-id="'+modification_id+'">\n' +
                    '<div class="row">\n' +
                    '<div class="col-xs-9">\n' +
                    '    <select class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][type]">\n' +
                    modifications_options() +
                    '    </select>\n' +
                    '</div>\n' +
                    '<div class="col-xs-3">\n' +
                    '   <button type="button" class="btn btn-primary remove_export_field_modification">\n' +
                    '       <i class="glyphicon glyphicon-minus"></i>\n' +
                    '   </button>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '</div>');
            });
            function field_type_options() {
                let html = '';
                for(var value in window.export.field_types){
                    html += '<option value="'+value+'">'+window.export.field_types[value]+'</option>\n';
                }
                return html;
            }
            function modifications_options() {
                let html = '';
                for(var value in window.export.modifications){
                    html += '<option value="'+value+'">'+window.export.modifications[value]+'</option>\n';
                }
                return html;
            }
            $('#export_fields').on('click', '.remove_export_field_modification', function(){
                $(this).parents('.modification').remove();
            });
            $('#export_fields').on('change', '.field_type', function(){
                let field_id = $(this).parents('.export_field').data('id');
                if($(this).val() == 'custom') {
                    $(this).next().remove();
                    $(this).after('<input type="text" class="form-control" name="fields[' + field_id + '][field][custom]" placeholder="Введите своё значение" value="">');
                }else if($(this).val() == 'product.attribute'){
                    $(this).next().remove();
                    let select = '<select class="form-control" name="fields[' + field_id + '][field][attribute]">';
                    for(let attr in window.attributes) {
                        select += '<option value="'+window.attributes[attr].id+'"'+(attr?'':' selected')+'>'+window.attributes[attr].name+'</option>\n';
                    }
                    select += '</select>';
                    $(this).after(select);
                }else{
                    $(this).next().remove();
                }
            });
            $('#export_fields').on('change', '.modification select', function(){
                let field_id = $(this).parents('.export_field').data('id');
                let modification = $(this).parents('.modification');
                let modification_id = modification.data('id');
                let val = $(this).val();
                modification.find('input').remove();
                if(val == 'replace_all' || val == 'replace_part'){
                    modification.find('.col-xs-9').append('<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][from]" placeholder="Что заменить" value="">' +
                        '<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][to]" placeholder="Чем заменить" value="">');
                }else if(val == 'add_prefix' || val == 'add_suffix' || val == 'add_num' || val == 'multiple'){
                    modification.find('.col-xs-9').append('<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][value]" placeholder="Введите значение" value="">');
                }
            });
            $('#export_fields').on('click', '.remove_export_field', function(){
                $(this).parents('.form-group').remove();
            });
        });
    </script>
@endsection