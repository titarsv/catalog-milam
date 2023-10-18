@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Категории
@endsection
@section('content')

    <h1>Добавление категории</h1>

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
                        {{--@include('admin.layouts.form.text', [--}}
                         {{--'label' => 'Описание',--}}
                         {{--'key' => 'description'--}}
                        {{--])--}}
                        @include('admin.layouts.form.select', [
                         'label' => 'Родительская категория',
                         'key' => 'parent_id',
                         'options' => $categories,
                         'selected' => [old('parent_id') ? old('parent_id') : '']
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Выберите изображение</label>
                                <div class="form-element col-sm-3">
                                    @include('admin.layouts.form.image', [
                                     'key' => 'file_id'
                                    ])
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.form.select', [
                         'label' => 'Связанные атрибуты (для фильтрации товаров)',
                         'key' => 'related_attribute_ids',
                         'options' => $attributes,
                         'multiple' => true,
                         'selected' => old('related_attribute_ids') ? old('related_attribute_ids') : []
                        ])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Порядок сортировки',
                         'key' => 'sort_order',
                         'languages' => null
                        ])
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

    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection
