@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    {{ $import->name }}
@endsection
@section('content')
    <h1>{{ $import->name }}</h1>

    @if(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="form">
        <form method="post" class="{{ !empty($import->attachments) ? 'with_attachments' : '' }}">
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
                                    <input type="text" data-translit="input" class="form-control" name="name" value="{!! old('name') ? old('name') : $import->name !!}" />
                                    @if($errors->has('name'))
                                        <p class="warning" role="alert">{!! $errors->first('name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тип имторта</label>
                                <div class="form-element col-sm-10">
                                    <select class="form-control" name="type">
                                        <option value="create"{{ isset($import->settings->type) && $import->settings->type == 'create' ? ' selected' : '' }}>Добавить новые товары</option>
                                        <option value="update"{{ isset($import->settings->type) && $import->settings->type == 'update' ? ' selected' : '' }}>Обновить существующие товары</option>
                                        <option value="update_and_create"{{ isset($import->settings->type) && $import->settings->type == 'update_and_create' ? ' selected' : '' }}>Добавить новые и обновить существующие товары</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Поле для связи</label>
                                <div class="form-element col-sm-10">
                                    <select class="form-control" name="relation">
                                        <option value="product.id"{{ isset($import->settings->relation) && $import->settings->relation == 'product.id' ? ' selected' : '' }}>ID товара</option>
                                        <option value="product.name"{{ isset($import->settings->relation) && $import->settings->relation == 'product.name' ? ' selected' : '' }}>Название товара</option>
                                        {{--<option value="product.url_alias"{{ isset($import->settings->relation) && $import->settings->relation == 'product.url' ? ' selected' : '' }}>URL</option>--}}
                                        <option value="product.sku"{{ isset($import->settings->relation) && $import->settings->relation == 'product.sku' ? ' selected' : '' }}>Артикул</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связи</h4>
                    </div>
                    <div class="panel-body">
                        @php $i=0; @endphp
                        @foreach($structure as $title => $data)
                            <div class="row field">
                                <div class="col-sm-2">
                                    <b>{{ $title }}:</b>
                                </div>
                                <div class="col-sm-4" style="overflow: hidden">
                                    {!! $data->data !!}
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" name="fields[{{ $i }}][title]" value="{{ $title }}">
                                    <select name="fields[{{ $i }}][type]" class="form-control import-field-type">
                                        <option value="">Пропустить</option>
                                        @foreach($fields as $field => $name)
                                            <option value="{{ $field }}"{{ $data->type == $field ? ' selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-form" data-id="{{ $i }}">
                                        @if($data->type == 'product.file_id' || $data->type == 'galleries.file_id')
                                            <label>Тип содержимого:</label>
                                            <select class="form-control" name="fields[{{ $i }}][format]">
                                                <option value="media.name"{{ isset($data->format) && $data->format == 'media.name' ? ' selected' : '' }}>Название файла из медиатеки сайта</option>
                                                @if(!empty($import->attachments))
                                                <option value="archive.name"{{ isset($data->format) && $data->format == 'archive.name' ? ' selected' : '' }}>Название файла из архива</option>
                                                @endif
                                                <option value="link"{{ isset($data->format) && $data->format == 'link' ? ' selected' : '' }}>Ссылка на файл</option>
                                                <option value="media.id"{{ isset($data->format) && $data->format == 'media.id' ? ' selected' : '' }}>ID файла из медиатеки сайта</option>
                                            </select>

                                            @if($data->type == 'galleries.file_id')
                                                <label>Разделитель:</label>
                                                <input class="form-control" type="text" name="fields[{{ $i }}][separator]" value="{{ isset($data->separator) ? $data->separator : '' }}">
                                            @endif

                                            <label>Если файл не найден:</label>
                                            <select class="form-control" name="fields[{{ $i }}][not_found]">
                                                <option value="stop"{{ isset($data->not_found) && $data->not_found == 'stop' ? ' selected' : '' }}>Остановить импорт</option>
                                                <option value="skip"{{ isset($data->not_found) && $data->not_found == 'skip' ? ' selected' : '' }}>Пропустить импорт товара</option>
                                                <option value="ignore"{{ isset($data->not_found) && $data->not_found == 'ignore' ? ' selected' : '' }}>Импортировать без изображения</option>
                                                <option value="remain"{{ isset($data->not_found) && $data->not_found == 'remain' ? ' selected' : '' }}>Оставить старое изображение(при обновлении)</option>
                                            </select>
                                        @elseif($data->type == 'category.id')
                                            <label>Разделитель между категориями:</label>
                                            <input class="form-control" type="text" name="fields[{{ $i }}][separator]" value="{{ isset($data->separator) ? $data->separator : '' }}">

                                            <label>Разделитель вложенности категорий:</label>
                                            <input class="form-control" type="text" name="fields[{{ $i }}][tree_separator]" value="{{ isset($data->tree_separator) ? $data->tree_separator : '' }}">

                                            <label>Если категория не найдена:</label>
                                            <select class="form-control" name="fields[{{ $i }}][not_found]">
                                                <option value="stop"{{ isset($data->not_found) && $data->not_found == 'stop' ? ' selected' : '' }}>Остановить импорт</option>
                                                <option value="skip"{{ isset($data->not_found) && $data->not_found == 'skip' ? ' selected' : '' }}>Пропустить импорт товара</option>
                                                <option value="ignore"{{ isset($data->not_found) && $data->not_found == 'ignore' ? ' selected' : '' }}>Импортировать без категории</option>
                                                <option value="remain"{{ isset($data->not_found) && $data->not_found == 'remain' ? ' selected' : '' }}>Оставить старые категории(при обновлении)</option>
                                                <option value="create"{{ isset($data->not_found) && $data->not_found == 'create' ? ' selected' : '' }}>Создать новую категорию</option>
                                            </select>
                                        @elseif($data->type == 'attribute_values.id')
                                            <label>Тип содержимого:</label>
                                            <select class="form-control attributes-format" name="fields[{{ $i }}][format]">
                                                <option value="values"{{ isset($data->format) && $data->format == 'values' ? ' selected' : '' }}>Варианты одного атрибута</option>
                                                <option value="attributes_and_values"{{ isset($data->format) && $data->format == 'attributes_and_values' ? ' selected' : '' }}>Названия атрибутов и их варианты</option>
                                            </select>

                                            <div class="attribute-fields">
                                                @if(isset($data->format) && $data->format == 'attributes_and_values')
                                                    <label>Разделитель между атрибутами:</label>
                                                    <input class="form-control" type="text" name="fields[{{ $i }}][attributes_separator]" value="{{ isset($data->attributes_separator) ? $data->attributes_separator : '' }}">
                                                    <label>Разделитель между названием атрибута и его вариантами:</label>
                                                    <input class="form-control" type="text" name="fields[{{ $i }}][attribute_values_separator]" value="{{ isset($data->attribute_values_separator) ? $data->attribute_values_separator : '' }}">
                                                @else
                                                    <label>Название атрибута:</label>
                                                    <input class="form-control" type="text" name="fields[{{ $i }}][attribute]" value="{{ isset($data->attribute) ? $data->attribute : '' }}">
                                                @endif
                                            </div>

                                            <label>Разделитель между вариантами атрибутов:</label>
                                            <input class="form-control" type="text" name="fields[{{ $i }}][separator]" value="{{ isset($data->separator) ? $data->separator : '' }}">

                                            <label>Если атрибут не найден:</label>
                                            <select class="form-control" name="fields[{{ $i }}][not_found]">
                                                <option value="stop"{{ isset($data->not_found) && $data->not_found == 'stop' ? ' selected' : '' }}>Остановить импорт</option>
                                                <option value="skip"{{ isset($data->not_found) && $data->not_found == 'skip' ? ' selected' : '' }}>Пропустить импорт товара</option>
                                                <option value="ignore"{{ isset($data->not_found) && $data->not_found == 'ignore' ? ' selected' : '' }}>Импортировать без атрибута</option>
                                                <option value="remain"{{ isset($data->not_found) && $data->not_found == 'remain' ? ' selected' : '' }}>Оставить старые атрибуты(при обновлении)</option>
                                                <option value="create"{{ isset($data->not_found) && $data->not_found == 'create' ? ' selected' : '' }}>Создать новый атрибут</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                @if($import->status == 100)
                                    <button type="button" class="btn btn-primary" id="refresh_import" data-id="{{ $import->id }}">Повторить импорт</button>
                                @else
                                    <button type="button" class="btn btn-primary" id="start_import" data-id="{{ $import->id }}">{{ $import->status == 0 ? 'Начать импорт' : 'Продолжить импорт' }}</button>
                                @endif
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default hidden" id="import_process">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                        0%
                                    </div>
                                </div>

                                <div class="success alert alert-success hidden"></div>

                                <div class="warning alert alert-warning hidden"></div>

                                <div class="errors alert alert-danger hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        jQuery(document).ready(function(){
            $(document).on('change', '.import-field-type', function(){
                var $this = $(this);
                var container = $this.next();
                var id = container.data('id');
                var with_attachments = $this.parents('form').hasClass('with_attachments');
                if($this.val() === 'product.file_id'){
                    container.html('<label>Тип содержимого:</label>' +
                        '<select class="form-control" name="fields['+id+'][format]">' +
                        '<option value="media.name">Название файла из медиатеки сайта</option>' +
                        (with_attachments ? '<option value="archive.name">Название файла из архива</option>' : '') +
                        '<option value="link">Ссылка на файл</option>' +
                        '<option value="media.id">ID файла из медиатеки сайта</option>' +
                        '</select>' +
                        '<label>Если файл не найден:</label>' +
                        '<select class="form-control" name="fields['+id+'][not_found]">' +
                        '<option value="stop">Остановить импорт</option>' +
                        '<option value="skip">Пропустить импорт товара</option>' +
                        '<option value="ignore">Импортировать без изображения</option>' +
                        '<option value="remain">Оставить старое изображение(при обновлении)</option>' +
                        '</select>');
                }else if($this.val() === 'galleries.file_id'){
                    container.html('<label>Тип содержимого:</label>' +
                        '<select class="form-control" name="fields['+id+'][format]">' +
                        '<option value="media.name">Название файла из медиатеки сайта</option>' +
                        (with_attachments ? '<option value="archive.name">Название файла из архива</option>' : '') +
                        '<option value="link">Ссылка на файл</option>' +
                        '<option value="media.id">ID файла из медиатеки сайта</option>' +
                        '</select>' +
                        '<label>Разделитель:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][separator]">' +
                        '<label>Если файл не найден:</label>' +
                        '<select class="form-control" name="fields['+id+'][not_found]">' +
                        '<option value="stop">Остановить импорт</option>' +
                        '<option value="skip">Пропустить импорт товара</option>' +
                        '<option value="ignore">Импортировать без изображения</option>' +
                        '<option value="remain">Оставить старое изображение(при обновлении)</option>' +
                        '</select>');
                }else if($this.val() === 'category.id'){
                    container.html('<label>Разделитель между категориями:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][separator]">' +
                        '<label>Разделитель вложенности категорий:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][tree_separator]">' +
                        '<label>Если категория не найдена:</label>' +
                        '<select class="form-control" name="fields['+id+'][not_found]">' +
                        '<option value="stop">Остановить импорт</option>' +
                        '<option value="skip">Пропустить импорт товара</option>' +
                        '<option value="ignore">Импортировать без категории</option>' +
                        '<option value="remain">Оставить старые категории(при обновлении)</option>' +
                        '<option value="create">Создать новую категорию</option>' +
                        '</select>');
                }else if($this.val() === 'attribute_values.id'){
                    container.html('<label>Тип содержимого:</label>' +
                        '<select class="form-control attributes-format" name="fields['+id+'][format]">' +
                        '<option value="values">Варианты одного атрибута</option>' +
                        '<option value="attributes_and_values">Названия атрибутов и их варианты</option>' +
                        '</select>' +
                        '<div class="attribute-fields">' +
                        '<label>Название атрибута:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][attribute]">' +
                        '</div>' +
                        '<label>Разделитель между вариантами атрибутов:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][separator]">' +
                        '<label>Если атрибут не найден:</label>' +
                        '<select class="form-control" name="fields['+id+'][not_found]">' +
                        '<option value="stop">Остановить импорт</option>' +
                        '<option value="skip">Пропустить импорт товара</option>' +
                        '<option value="ignore">Импортировать без атрибута</option>' +
                        '<option value="remain">Оставить старые атрибуты(при обновлении)</option>' +
                        '<option value="create">Создать новый атрибут</option>' +
                        '</select>');
                }else{
                    container.html('');
                }
            });
            $(document).on('change', '.attributes-format', function(){
                var $this = $(this);
                var container = $this.next();
                var id = $this.parent().data('id');
                if($this.val() === 'attributes_and_values'){
                    container.html('<label>Разделитель между атрибутами:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][attributes_separator]">' +
                        '<label>Разделитель между названием атрибута и его вариантами:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][attribute_values_separator]">');
                }else if($this.val() === 'values'){
                    container.html('<label>Название атрибута:</label>' +
                        '<input class="form-control" type="text" name="fields['+id+'][attribute]">');
                }
            });
            $('#start_import').click(function(){
                $('#import_process').removeClass('hidden');
                nextImportStep($(this).data('id'));
            });
            $('#refresh_import').click(function(){
                $('#import_process').removeClass('hidden');
                var id = $(this).data('id');
                $.post('/admin/products/import/refresh_import/'+id, {}, function () {
                    nextImportStep(id);
                })
            });
            function nextImportStep(id){
                $.post('/admin/products/import/next_import_step/'+id, {}, function(response){
                    $('.progress-bar').css('width', response.progress+'%').text(response.progress+'%');
                    $('.alert-success, .alert-warning, .alert-danger').html('');
                    if(response.statistic.not_imported == 0){
                        $('.alert-success').html('<p class="main">Импортировано <b>'+response.statistic.imported+'</b> из <b>'+response.total+'</b> товаров!</p>').removeClass('hidden');
                    }else{
                        $('.alert-warning').html('<p class="main">Импортировано <b>'+response.statistic.imported+'</b> из <b>'+response.total+'</b> товаров, <b>'+response.statistic.not_imported+'</b> товаров не импортировано из-за ошибок!</p>').removeClass('hidden');
                        $('.alert-success').addClass('hidden');
                    }

                    if(response.statistic.warnings[0]){
                        var html = '';
                        for(var i in response.statistic.warnings){
                            html += '<p>'+response.statistic.warnings[i]+'</p>';
                        }
                        $('.alert-warning').append(html).removeClass('hidden');
                    }

                    if(response.statistic.errors[0]){
                        var html = '';
                        for(var i in response.statistic.errors){
                            html += '<p>'+response.statistic.errors[i]+'</p>';
                        }
                        $('.alert-danger').html(html).removeClass('hidden');
                    }
                    if(response.progress < 100){
                        nextImportStep(id);
                    }
                });
            }
        });
    </script>
@endsection