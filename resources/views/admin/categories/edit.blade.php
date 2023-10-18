@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Категории
@endsection
@section('content')

    <h1>Редактирование категории</h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'item' => $category,
                         'required' => true
                        ])
                        {{--@include('admin.layouts.form.text', [--}}
	                     {{--'label' => 'Описание',--}}
                         {{--'key' => 'description',--}}
                         {{--'item' => $category--}}
                        {{--])--}}
                        @include('admin.layouts.form.select', [
                         'label' => 'Родительская категория',
                         'key' => 'parent_id',
                         'options' => $categories,
                         'selected' => [old('parent_id') ? old('parent_id') : $category->parent_id]
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Выберите изображение</label>
                                <div class="form-element col-sm-3">
                                    @include('admin.layouts.form.image', [
                                     'key' => 'file_id',
                                     'image' => $category->image
                                    ])
                                </div>
                            </div>
                        </div>
                        @include('admin.layouts.form.select', [
                         'label' => 'Связанные атрибуты (для фильтрации товаров)',
                         'key' => 'related_attribute_ids',
                         'options' => $attributes,
                         'multiple' => true,
                         'selected' => old('related_attribute_ids') ? old('related_attribute_ids') : $related_attributes
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
                         'item' => $category,
                         'languages' => null
                        ])
                        @include('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : $category->status]
                        ])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Товары в категории</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="position: relative">
                            @if($user->hasAccess(['categories.update']))
                                <input type="text" name="search" class="form-control" id="live_search" value="" placeholder="Поиск">
                                <div id="live_search_results"></div>
                            @endif
                            <div id="in_action">
                                @include('admin.categories.products', ['products' => $products, 'category' => $category])
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.layouts.seo')
                @if($user->hasAccess(['categories.update']))
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
            $('.js_change_product_order').change(function(){
                $.post('/admin/categories/update_product_order', {category_id: $(this).data('category'), product_id: $(this).data('product'), order: $(this).val()}, function(){

                })
            });
        });
    </script>

    @include('admin.layouts.mce', ['editors' => $editors])
@endsection
@section('before_footer')
    @include('admin.media.assets')
@endsection