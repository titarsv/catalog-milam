@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Акции
@endsection
@section('content')

    <h1>Создание акции</h1>

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
                         'required' => true
                        ])
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Подзаголовок',--}}
                         {{--'key' => 'subtitle'--}}
                        {{--])--}}
                        @include('admin.layouts.form.editor', [
                         'label' => 'Описание',
                         'key' => 'body'
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Превью</label>
                                <div class="form-element col-sm-2">
                                    @include('admin.layouts.form.image', [
                                     'key' => 'preview_id'
                                    ])
                                </div>
                                {{--<label class="col-sm-2 text-right">Банер основной</label>--}}
                                {{--<div class="form-element col-sm-2">--}}
                                    {{--@include('admin.layouts.form.image', [--}}
                                     {{--'key' => 'file_id'--}}
                                    {{--])--}}
                                {{--</div>--}}
                                {{--<label class="col-sm-2 text-right">Банер мобильный</label>--}}
                                {{--<div class="form-element col-sm-2">--}}
                                    {{--@include('admin.layouts.form.image', [--}}
                                     {{--'key' => 'file_xs_id'--}}
                                    {{--])--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        {{--@include('admin.layouts.form.string', [--}}
                         {{--'label' => 'Цвет подложки',--}}
                         {{--'key' => 'banner_color',--}}
                         {{--'languages' => null--}}
                        {{--])--}}
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время начала</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control from" name="show_from" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время окончания</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control to" name="show_to" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : 0]
                        ])
                    </div>
                </div>
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
