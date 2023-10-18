@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Модули
@endsection
@section('content')

    <h1>{!! $module->name !!}</h1>

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
                        <h4>Настройки модуля</h4>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Статус</label>
                                <div class="form-element col-sm-10">
                                    <select name="status" class="form-control">
                                        @if($module->status)
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        @else
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Слайды</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table slideshow-images">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="success">
                                        <th align="center" class="col-md-2">Изображение</th>
                                        <th align="center" class="col-md-2">Ссылка/Кнопка</th>
                                        <th align="center" class="col-md-2">Порядок/Статус</th>
                                        <th align="center" class="col-md-3">Заголовок/Описание</th>
                                        <th align="center" class="col-md-1">Действия</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-table">
                                    @forelse($slideshow as $key => $slide)
                                        @php $data = $slide->data(); @endphp
                                        <tr>
                                            <td class="col-md-2">
                                                <div class="image-container">
                                                    <input type="hidden" name="slide[{{ $key }}][file_id]" value="{{ $slide->file_id }}">
                                                    <div>
                                                        <div>
                                                            <i class="remove-image">-</i>
                                                            @if(!empty($slide->image))
                                                            <img src="{{ $slide->image->url() }}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="upload_image_button" data-type="single" style="display: none;">
                                                        <div class="add-btn"></div>
                                                    </div>
                                                </div>
                                                {{--<div class="image-container">--}}
                                                    {{--<input type="hidden" name="slide[{{ $key }}][file_xs_id]" value="{{ $slide->file_xs_id }}">--}}
                                                    {{--<div>--}}
                                                        {{--<div>--}}
                                                            {{--<i class="remove-image">-</i>--}}
                                                            {{--@if(!empty($slide->image_xs))--}}
                                                            {{--<img src="{{ $slide->image_xs->url() }}">--}}
                                                            {{--@endif--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="upload_image_button" data-type="single" style="display: none;">--}}
                                                        {{--<div class="add-btn"></div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Ссылка</b>
                                                    <input type="text" name="slide[{!! $key !!}][link]" class="form-control" value="{!! $slide->link !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Текст кнопки</b>
                                                    <input type="text" name="slide[{!! $key !!}][button_text]" class="form-control" value="{!! $data->button_text !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Отображать кнопку</b>
                                                    <select name="slide[{!! $key !!}][enable_link]" class="form-control">
                                                        @if($slide->enable_link)
                                                            <option value="1" selected>Да</option>
                                                            <option value="0">Нет</option>
                                                        @elseif(!$slide->enable_link)
                                                            <option value="1">Да</option>
                                                            <option value="0" selected>Нет</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Порядок сортировки*</b>
                                                    <input type="text" name="slide[{!! $key !!}][sort_order]" class="form-control" value="{!! $slide->sort_order !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Статус</b>
                                                    <select name="slide[{!! $key !!}][status]" class="form-control">
                                                        @if($slide->status)
                                                            <option value="1" selected>Отображать</option>
                                                            <option value="0">Скрыть</option>
                                                        @elseif(!$slide->status)
                                                            <option value="1">Отображать</option>
                                                            <option value="0" selected>Скрыть</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Язык:</b>
                                                    <select name="slide[{!! $key !!}][lang]" class="form-control">
                                                        <option value="ru"{{ isset($data->lang) && $data->lang == 'ru' ? ' selected' : '' }}>Русский</option>
                                                        <option value="ua"{{ isset($data->lang) && $data->lang == 'ua' ? ' selected' : '' }}>Українська</option>
                                                        {{--<option value="en"{{ isset($data->lang) && $data->lang == 'en' ? ' selected' : '' }}>English</option>--}}
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div>
                                                    <b>Заголовок</b>
                                                    <input type="text" name="slide[{!! $key !!}][slide_title]" class="form-control" value="{!! json_decode($slide->slide_data)->slide_title !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.slide_title'))
                                                            {{ $errors->first('slide.' . $key . '.slide_title',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Описание</b>
                                                    <textarea type="text" name="slide[{!! $key !!}][slide_description]" class="form-control">{!! $data->slide_description !!}</textarea>
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.slide_description'))
                                                            {{ $errors->first('slide.' . $key . '.slide_description',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                {{--<br>--}}
                                                {{--<div>--}}
                                                    {{--<b>Цвет подложки</b>--}}
                                                    {{--<input type="text" name="slide[{!! $key !!}][slide_color]" class="form-control" value="{!! isset(json_decode($slide->slide_data)->slide_color) ? json_decode($slide->slide_data)->slide_color : '' !!}" />--}}
                                                    {{--<span style="color: red">--}}
                                                        {{--@if($errors->has('slide.' . $key . '.slide_color'))--}}
                                                            {{--{{ $errors->first('slide.' . $key . '.slide_color',':message')  }}--}}
                                                        {{--@endif--}}
                                                    {{--</span>--}}
                                                {{--</div>--}}
                                            </td>
                                            <td class="col-md-1" align="center">
                                                <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                @if($key == count($slideshow) - 1)
                                                    <input type="hidden" value="{!! $key !!}" id="slideshow-iterator" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty">
                                            <td colspan="5" align="center">Нет добавленных слайдов!</td>
                                        </tr>
                                        <input type="hidden" value="0" id="slideshow-iterator" />
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td align="center"><button type="button" id="button-add-slide" class="btn btn-primary">Добавить слайд</button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @if($user->hasAccess(['modules.update']))
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
        jQuery(document).ready(function($){
            $('#button-add-slide').on('click', function() {
                var iterator = $('#slideshow-iterator');
                var i = iterator.val();
                i++;

                var html = '<tr><td class="col-md-2">';
                html += '<div class="image-container">' +
                    '<input type="hidden" name="slide[' + i + '][file_id]" value="1">' +
                    '<div class="upload_image_button" data-type="single">' +
                    '<div class="add-btn"></div>' +
                    '</div>' +
                    '</div>';
                // html += '<div class="image-container">' +
                //     '<input type="hidden" name="slide[' + i + '][file_xs_id]" value="1">' +
                //     '<div class="upload_image_button" data-type="single">' +
                //     '<div class="add-btn"></div>' +
                //     '</div>' +
                //     '</div>';
                html += '</td><td class="col-md-2"><b>Ссылка</b>';
                html += '<input type="text" name="slide[' + i + '][link]" class="form-control" value="" />';
                html += '</div><br><div><b>Текст кнопки</b>';
                html += '<input type="text" name="slide[' + i + '][button_text]" class="form-control" value="" />';
                html += '</div><br><div><b>Отображать кнопку</b>';
                html += '<select name="slide[' + i + '][enable_link]" class="form-control">';
                html += '<option value="1" selected>Да</option><option value="0">Нет</option>';
                html += '</select></div></td>';
                html += '<td class="col-md-2"><div><b>Порядок сортировки*</b>';
                html += '<input type="text" name="slide[' + i + '][sort_order]" class="form-control" value="" />';
                html += '</div><br><div><b>Статус</b><select name="slide[' + i + '][status]" class="form-control">';
                html += '<option value="1" selected>Отображать</option><option value="0">Скрыть</option>';
                html += '</select></div>';
                html += '<br><div>\n' +
                    '<b>Язык:</b>\n' +
                    '<select name="slide[' + i + '][lang]" class="form-control">\n' +
                    '<option value="ru">Русский</option>\n' +
                    '<option value="ua">Українська</option>\n' +
                    '</select>\n' +
                    '</div>';
                html += '</td>';
                html += '<td class="col-md-2">';
                html += '<div><b>Заголовок</b>';
                html += '<input type="text" name="slide[' + i + '][slide_title]" class="form-control" value="" />';
                html += '<span style="color: red"></span></div><br>';
                html += '<div><b>Описание</b>';
                html += '<textarea name="slide[' + i + '][slide_description]" class="form-control"></textarea>';
                html += '<span style="color: red"></span></div>';
                // html += '<br><div><b>Цвет подложки</b>';
                // html += '<input type="text" name="slide[' + i + '][slide_color]" class="form-control" value="" />';
                // html += '<span style="color: red">';
                // html += '</span></div>';
                html += '</td>';
                html += '<td class="col-md-1" align="center">';
                html += '<button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>';
                html += '</td></tr>';

                if ($('#modules-table tr.empty').length) {
                    $('#modules-table tr.empty').remove();
                }
                $('#modules-table').append(html);
                iterator.val(i);
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
