@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог товаров
@endsection
@section('content')
    <h1>Список товаров</h1>

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

    <form action="products" method="post" id="settings-form">
        {!! csrf_field() !!}
        <div class="settings row">
            <div class="col-sm-4">
                <div class="input-group">
                    <label for="sort" class="input-group-addon">Сортировка:</label>
                    <select name="sort" id="sort-by" class="form-control input-sm">
                        @foreach($array_sort as $sort => $value)
                            @if($current_sort['value'] == $sort)
                                <option value="{{ $sort }}" selected>{{ $value['name'] }}</option>
                            @else
                                <option value="{{ $sort }}">{{ $value['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <label for="search" class="input-group-addon">Поиск:</label>
                    <input type="text" id="search" name="search" placeholder="Введите текст..." class="form-control input-sm" value="{{ $current_search }}" />
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <label for="show" class="input-group-addon">Отображать:</label>
                    <select name="show" id="show" class="form-control input-sm">
                        @foreach($array_show as $show)
                            @if($current_show == $show)
                                <option value="{{ $show }}" selected>{{ $show }}</option>
                            @else
                                <option value="{{ $show }}">{{ $show }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="btn-group btn-group-xs">
                            <a href="/admin/products" class="btn product-sort-button">
                                Все товары
                            </a>
                            <a href="/admin/products?stock=1" class="btn product-sort-button">
                                В наличии
                            </a>
                            <a href="/admin/products?stock=0" class="btn product-sort-button">
                                Нет в наличии
                            </a>
                            <button type="button" id="current-cat" class="btn dropdown-toggle product-sort-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="dropdown-selected-name">Категория</span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" id="categories_list">
                                @forelse($categories as $category)
                                    <li>
                                        <a type="button" data-sort="category" data-value="{!! $category->id !!}" class="sort-buttons" onclick="filterProducts($(this))">{!! $category->name !!}</a>
                                    </li>
                                @empty
                                    <li><a href="javascript:void(0)">Нет добавленных линеек товара!</a></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-5 text-right">
                        <div class="btn-group btn-group-xs">
                            <button type="button" class="btn btn-primary popup-btn" data-mfp-src="#filter-popup">Расширенный фильтр</button>
                            @if($user->hasAccess(['products.create']))
                                <a href="/admin/products/create" class="btn btn-primary">Добавить новый</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div id="select_group" class="btn-group btn-group-xs progress progress-striped active" style="overflow:unset;margin:0;height:22px;">
                            <div class="btn-group btn-group-xs progress-bar" style="width:100%;height:22px;border-radius:3px;">
                                <div class="btn-group btn-group-xs">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="dropdown-selected-name">Выделение</span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" type="button" id="select_all">Выбрать всё</a></li>
                                        <li><a href="javascript:void(0)" type="button" id="unselect_all">Снять все выделения</a></li>
                                        <li><a href="javascript:void(0)" type="button" id="select_visible">Выбрать видимое</a></li>
                                        <li><a href="javascript:void(0)" type="button" id="unselect_visible">Снять выделение с видимого</a></li>
                                    </ul>
                                </div>
                                <div class="btn btn-primary">Выбрано товаров: <b id="selected_total">0</b></div>
                            </div>
                        </div>
                    </div>
                    @if($user->hasAnyAccess(['products.update', 'products.delete']))
                        <div class="col-sm-6 text-right">
                            <div class="btn-group btn-group-xs">
                                <div class="btn-group btn-group-xs" id="current_action">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="dropdown-selected-name">Групповое действие</span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        @if($user->hasAccess(['products.delete']))
                                            <li><a href="javascript:void(0)" data-action="remove_products">Удалить</a></li>
                                        @endif
                                        @if($user->hasAccess(['products.update']))
                                            <li><a href="javascript:void(0)" data-action="change_status">Изменить статус</a></li>
                                            <li><a href="javascript:void(0)" data-action="change_attributes">Изменить атрибуты</a></li>
                                            <li><a href="javascript:void(0)" data-action="change_sort_priority">Изменить сортировку</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)" data-action="add_label">Добавить маркер</a></li>
                                            <li><a href="javascript:void(0)" data-action="remove_labels">Убрать маркеры</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)" data-action="add_category">Добавить категорию</a></li>
                                            <li><a href="javascript:void(0)" data-action="remove_category">Удалить категорию</a></li>
                                            <li><a href="javascript:void(0)" data-action="change_categories">Переместить в категорию</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)" data-action="add_price">Обновить цену на</a></li>
                                            <li><a href="javascript:void(0)" data-action="add_sale_price">Обновить акционную цену на</a></li>
                                            <li><a href="javascript:void(0)" data-action="multiply_price">Обновить цену в</a></li>
                                            <li><a href="javascript:void(0)" data-action="multiply_sale_price">Обновить акционную цену в</a></li>
                                            {{--<li class="divider"></li>--}}
                                            {{--<li><a href="javascript:void(0)" data-action="add_text">Добавить текст</a></li>--}}
                                            {{--<li><a href="javascript:void(0)" data-action="replace_text">Заменить текст</a></li>--}}
                                        @endif
                                    </ul>
                                    <input type="hidden" name="action" value="">
                                </div>
                                <div class="btn-group btn-group-xs" id="actions_container">

                                </div>
                                <button type="button" class="btn btn-primary" id="submit_mass_action">Применить</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <form action="" id="products_list" class="table-responsive">
                <input type="hidden" name="products_list" value="">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr class="success">
                        <td></td>
                        <td align="center" style="min-width: 100px">Фото</td>
                        <td style="max-width: 220px;">Название</td>
                        <td align="center" class="hidden-xs">Категория</td>
                        <td align="center">Видимость</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr id="product-{{ $product->id }}">
                            <td>
                                <input type="checkbox" name="selected[]" value="{{ $product->id }}" autocomplete="off">
                            </td>
                            <td align="center">
                                @if(!empty($product->image))
                                    <img src="{!! $product->image->url([100, 100])!!}"
                                         alt="{!! $product->image->title !!}"
                                         class="img-thumbnail">
                                @else
                                    <img src="/uploads/no_image.jpg"
                                         alt="no_image"
                                         class="img-thumbnail">
                                @endif
                            </td>
                            <td style="max-width: 220px;white-space: normal;"><a href="{{ $product->link() }}" target="_blank">{{ $product->localize('ua', 'name') }}</a></td>
                            <td align="center" class="hidden-xs product-categories">
                                @foreach($product->categories as $category)
                                    <span class="product-category category-{{ $category->id }}">{!! $category->name !!}</span><br>
                                @endforeach
                            </td>
                            <td{!! $user->hasAnyAccess(['products.update']) ? ' class="status"' : '' !!} align="center">
                            <span class="{!! $product->visible ? 'on' : 'off' !!}" data-id="{{ $product->id }}" style="cursor: pointer;">
                                        <span class="runner"></span>
                                    </span>
                            </td>
                            <td class="actions" align="center">
                                @if($user->hasAccess(['products.view']))
                                    <a class="btn btn-primary" href="/admin/products/edit/{!! $product->id !!}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                @endif
                                @if($user->hasAccess(['products.delete']))
                                    <button type="button" class="btn btn-danger" onclick="confirmProductsDelete('{!! $product->id !!}', '{{ $product->name }}')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" align="center">Нет добавленных товаров!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </form>
            <div class="panel-footer text-right">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div id="product-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить товар <span id="product-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden">
        <div id="filter-popup">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <span class="title">Расширенный фильтр</span>
                    </div>
                    <div class="col-xs-12">
                        <form action="/admin/products" id="filter-form">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group" data-group-id="0">
                                        <div class="row condition" data-id="0">
                                            <div class="form-element col-sm-4">
                                                <label class="text-right">Критерий фильтрации:</label>
                                                <select name="filter[0][0][criterion]" class="form-control criterion">
                                                    <option value="category" selected>Категория</option>
                                                    <option value="attribute">Атрибут</option>
                                                    <option value="status">Наличие</option>
                                                    <option value="price">Цена</option>
                                                    <option value="description">Описание товара</option>
                                                </select>
                                            </div>
                                            <div class="form-element col-sm-4">
                                                <label class="text-right">Значение</label>
                                                <select name="filter[0][0][value]" class="form-control value">
                                                    @foreach($categories as $category)
                                                        <option value="{!! $category->id !!}">{!! $category->name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-element col-sm-4">
                                                <label class="text-right">Условие:</label>
                                                <select name="filter[0][0][condition]" class="form-control criterion">
                                                    <option value="with_child" selected="">Включая дочерние</option>
                                                    <option value="without_child">Без дочерних</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-center buttons">
                                                <button type="button" class="btn btn-primary add_sub_condition">Добавить условие</button>
                                                {{--<button type="button" class="btn add_sub_condition_group">Добавить группу условий</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    {{--<button type="button" class="btn" id="add_condition">Добавить условие</button>--}}
                                    <button type="button" class="btn btn-primary" id="add_condition_group">Добавить группу условий</button>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary add_sub_condition">Применить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            navigateProductFilter();
            // $('#current-cat').click(function () {
            //     $('.btn-group').toggleClass('open');
            // });
            $('#sort-by, #show').change(function(){
                $('form#settings-form').submit();
            });
        });
        window.categories = {!! json_encode($categories) !!};
        window.attributes = {!! json_encode($all_attributes) !!};
        jQuery(document).ready(function(){
            // Выделение
            window.selected_products = $('#products_list [name="products_list"]');
            if(window.selected_products.length){
                window.product_selectors = $('#products_list [name="selected[]"]');
                window.selected_total = $('#selected_total');
                $('#select_all').click(function(){
                    $('#select_group .btn').css('background', 'transparent');
                    $.get('/admin/products/get_filtered_ids'+location.search, {}, function(response){
                        window.selected_products.val(response);
                        window.product_selectors.prop('checked', true);
                        $('#select_group .btn').css('background', '');
                        window.selected_total.text(response.split(',').length);
                    });
                });
                $('#unselect_all').click(function(){
                    window.product_selectors.prop('checked', false);
                    window.selected_products.val('');
                    $('#selected_total').text(0);
                });
                $('#select_visible').click(function(){
                    window.product_selectors.prop('checked', true);
                    let ids = [];
                    window.product_selectors.each(function(){
                        if($.inArray($(this).val(), ids) == -1)
                            ids.push($(this).val());
                    });
                    window.selected_products.val(ids.join(','));
                    window.selected_total.text(ids.length);
                });
                $('#unselect_visible').click(function(){
                    window.product_selectors.prop('checked', false);
                    let ids = [];
                    let val = window.selected_products.val();
                    if(val !== ''){
                        ids = window.selected_products.val().split(',');
                    }
                    window.product_selectors.each(function(){
                        let key = ids.indexOf($(this).val());
                        if(key >= 0)
                            ids.splice(key, 1);
                    });
                    window.selected_products.val(ids.join(','));
                    window.selected_total.text(ids.length);
                });
                window.product_selectors.change(function(){
                    let ids = [];
                    let val = window.selected_products.val();
                    if(val !== ''){
                        ids = window.selected_products.val().split(',');
                    }
                    if($(this).prop('checked')){
                        if($.inArray($(this).val(), ids) == -1)
                            ids.push($(this).val());
                    }else{
                        let key = ids.indexOf($(this).val());
                        if(key >= 0)
                            ids.splice(key, 1);
                    }
                    window.selected_products.val(ids.join(','));
                    window.selected_total.text(ids.length);
                });
            }

            // Групповые действия
            $('#current_action .dropdown-menu a').click(function(){
                let action = $(this).data('action');
                $('#current_action .dropdown-selected-name').text($(this).text());
                $('#current_action input').val(action);
                if(action === 'add_category' || action === 'remove_category' || action === 'change_categories'){
                    let dropdown = '<div class="btn-group btn-group-xs">\n' +
                        '<input type="hidden" name="category" value="" id="mass_category">\n' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '<span class="dropdown-selected-name">Категория</span>\n' +
                        '<span class="caret"></span>\n' +
                        '</button>\n' +
                        '<ul class="dropdown-menu" role="menu">';

                    for(let cat in window.categories) {
                        dropdown += '<li><a href="javascript:void(0)" data-value="'+window.categories[cat].id+'">'+window.categories[cat].name+'</a></li>\n';
                    }

                    dropdown += '</ul>';
                    $('#actions_container').html(dropdown);
                }else if(action === 'add_label'){
                    let dropdown = '<div class="btn-group btn-group-xs">\n' +
                        '<input type="hidden" name="label" value="" id="mass_label">\n' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '<span class="dropdown-selected-name">Метка</span>\n' +
                        '<span class="caret"></span>\n' +
                        '</button>\n' +
                        '<ul class="dropdown-menu" role="menu">';

                    for(let label in window.labels) {
                        dropdown += '<li><a href="javascript:void(0)" data-value="'+window.labels[label].id+'">'+window.labels[label].name+'</a></li>\n';
                    }

                    dropdown += '</ul>';
                    $('#actions_container').html(dropdown);
                }else if(action === 'change_status'){
                    let dropdown = '<div class="btn-group btn-group-xs">\n' +
                        '<input type="hidden" name="category" value="" id="mass_status">\n' +
                        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '<span class="dropdown-selected-name">Наличие</span>\n' +
                        '<span class="caret"></span>\n' +
                        '</button>\n' +
                        '<ul class="dropdown-menu" role="menu">\n' +
                        '<li><a href="javascript:void(0)" data-value="1">В наличии</a></li>\n' +
                        '<li><a href="javascript:void(0)" data-value="0">Отсутсвует</a></li>\n' +
                        '</ul>';
                    $('#actions_container').html(dropdown);
                }else{
                    $('#actions_container').html('');
                }
            });
            $('#actions_container').on('click', '.dropdown-menu a', function(){
                $(this).parents('#actions_container .btn-group').find('.dropdown-selected-name').text($(this).text());
                $(this).parents('#actions_container .btn-group').find('input').val($(this).data('value'));
            });
            $('#submit_mass_action').click(function(){
                let action = $('#current_action input').val();
                let products = window.selected_products.val();
                $(".alert").alert('close');
                addPlaceholder();
                if(action === 'add_category' || action === 'remove_category' || action === 'change_categories'){
                    let category_id = $('#mass_category').val();
                    $.post('/admin/products/mass_action/'+action, {products: products, category: category_id}, function(response){
                        let message_type = '';
                        if(response.result === 'success'){
                            message_type = 'alert-success';
                            products = products.split(',');
                            if(action === 'remove_category'){
                                for(let i in products){
                                    $('#product-'+products[i]+' .category-'+category_id + ' + br').remove();
                                    $('#product-'+products[i]+' .category-'+category_id).remove();
                                }
                            }else if(action === 'change_categories'){
                                for(let i in products) {
                                    $('#product-'+products[i]+' .product-category + br').remove();
                                    $('#product-'+products[i]+' .product-category').remove();
                                    $('#product-'+products[i]+' .product-categories').append('<span class="product-category category-' + category_id + '">' + $('#actions_container .btn-group').find('.dropdown-selected-name').text() + '</span><br>');
                                }
                            }else if(action === 'add_category'){
                                for(let i in products) {
                                    $('#product-'+products[i]+' .product-categories').append('<span class="product-category category-'+category_id+'">'+$('#actions_container .btn-group').find('.dropdown-selected-name').text()+'</span><br>');
                                }
                            }
                        }else if(response.result === 'error'){
                            message_type = 'alert-danger';
                        }
                        $('#settings-form').after('<div class="alert '+message_type+' alert-dismissable">\n' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n' +
                            response.message+'\n' +
                            '</div>\n');
                        removePlaceholder();
                    });
                }else if(action === 'add_label'){
                    let label_id = $('#mass_label').val();
                    $.post('/admin/products/mass_action/'+action, {products: products, label: label_id}, function(response){
                        let message_type = '';
                        if(response.result === 'success'){
                            message_type = 'alert-success';
                        }else if(response.result === 'error'){
                            message_type = 'alert-danger';
                        }
                        $('#settings-form').after('<div class="alert '+message_type+' alert-dismissable">\n' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n' +
                            response.message+'\n' +
                            '</div>\n');
                        removePlaceholder();
                    });
                }else if(action === 'remove_labels'){
                    $.post('/admin/products/mass_action/'+action, {products: products}, function(response){
                        let message_type = '';
                        if(response.result === 'success'){
                            message_type = 'alert-success';
                        }else if(response.result === 'error'){
                            message_type = 'alert-danger';
                        }
                        $('#settings-form').after('<div class="alert '+message_type+' alert-dismissable">\n' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n' +
                            response.message+'\n' +
                            '</div>\n');
                        removePlaceholder();
                    });
                }else if(action === 'remove_products'){
                    swal({
                        title: 'В уверены что хотите удалить выбранные товары?',
                        text: 'Это действие необратимо!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Удалить',
                        cancelButtonText: 'Отмена',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return new Promise((resolve, reject) => {
                                $.post('/admin/products/mass_action/'+action, {products: products}, function(response){
                                    if(response.result === 'success'){
                                        resolve(response.message);
                                    }else{
                                        reject(response.message);
                                    }
                                    removePlaceholder();
                                });
                            });
                        },
                    }).then(function(message) {
                        swal(
                            'Удалено!',
                            message,
                            'success'
                        );
                        products = products.split(',');
                        for(let i in products){
                            $('#product-'+products[i]).remove();
                        }
                        window.product_selectors.prop('checked', false);
                        window.selected_products.val('');
                        $('#selected_total').text(0);
                        removePlaceholder();
                    }, function(dismiss) {
                        if (dismiss === 'cancel') {
                            removePlaceholder();
                        }
                    });
                }else if(action === 'change_status'){
                    $.post('/admin/products/mass_action/'+action, {products: products, status: $('#mass_status').val()}, function(response){
                        let message_type = '';
                        if(response.result === 'success'){
                            message_type = 'alert-success';
                            products = products.split(',');
                            for(let i in products) {
                                $('#product-'+products[i]+' .status > span').attr('class', $('#mass_status').val() == 0 ? 'off' : 'on');
                            }
                        }else if(response.result === 'error'){
                            message_type = 'alert-danger';
                        }
                        $('#settings-form').after('<div class="alert '+message_type+' alert-dismissable">\n' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n' +
                            response.message+'\n' +
                            '</div>\n');
                        removePlaceholder();
                    });
                }else if(action === 'add_price' || action === 'add_sale_price' || action === 'multiply_price' || action === 'multiply_sale_price'){
                    if(action === 'add_price' || action === 'add_sale_price'){
                        var title = 'На сколько увеличить цену?';
                    }else if(action === 'multiply_price' || action === 'multiply_old_price'){
                        var title = 'Во сколько раз увеличить цену?';
                    }

                    swal({
                        title: title,
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Применить',
                        cancelButtonText: 'Отмена',
                        showLoaderOnConfirm: true,
                        preConfirm: (num) => {
                            return new Promise((resolve, reject) => {
                                $.post('/admin/products/mass_action/'+action, {products: products, num: num}, function(response){
                                    if(response.result === 'success'){
                                        resolve(response.message);
                                    }else{
                                        reject(response.message);
                                    }
                                    removePlaceholder();
                                });
                            });
                        },
                    }).then(function(message){
                        swal(
                            'Цены обновлены!',
                            message,
                            'success'
                        );
                    }, function(dismiss) {
                        if(dismiss === 'cancel') {
                            removePlaceholder();
                        }
                    });
                }else if(action === 'change_sort_priority'){
                    swal({
                        title: 'Установите порядок сортировки',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Применить',
                        cancelButtonText: 'Отмена',
                        showLoaderOnConfirm: true,
                        preConfirm: (priority) => {
                            return new Promise((resolve, reject) => {
                                $.post('/admin/products/mass_action/'+action, {products: products, sort_priority: priority}, function(response){
                                    if(response.result === 'success'){
                                        resolve(response.message);
                                    }else{
                                        reject(response.message);
                                    }
                                    removePlaceholder();
                                });
                            });
                        },
                    }).then(function(message){
                        swal(
                            'Порядок сортировки обновлён!',
                            message,
                            'success'
                        );
                    }, function(dismiss) {
                        if(dismiss === 'cancel') {
                            removePlaceholder();
                        }
                    });
                }else if(action === 'add_text'){

                }else if(action === 'replace_text'){

                }
            });
        });
    </script>
@endsection
