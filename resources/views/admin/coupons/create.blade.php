@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Промокоды
@endsection
@section('content')

    <h1>Создание промокода</h1>

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
                        <h4>Основная информация</h4>
                    </div>
                    <div class="panel-body">
                        @include('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'required' => true
                        ])
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Размер скидки</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sale" name="percent" value="{{ old('percent') ? old('percent') : (old('price') ? old('price') : '') }}">
                                                <div class="input-group-btn" id="coupon-price">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">% <span class="caret"></span></button>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li><a href="#" data-name="price">грн</a></li>
                                                        <li><a href="#" data-name="percent">%</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            @if($errors->has('price'))
                                                <p class="warning" role="alert">{{ $errors->first('price',':message') }}</p>
                                            @elseif($errors->has('percent'))
                                                <p class="warning" role="alert">{{ $errors->first('percent',':message') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Промокод</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') ? old('code') : '' }}" autocomplete="off">
                                                <div class="input-group-btn" id="generate_code">
                                                    <button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-refresh"></i></button>
                                                </div>
                                            </div>
                                            @if($errors->has('code'))
                                                <p class="warning" role="alert">{{ $errors->first('code',':message') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Промокод действует</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="radio">
                                                <label>
                                                    <input name="scope" value="all" autocomplete="off" type="radio"{{ old('scope', 'all') == 'all' ? ' checked' : '' }}> <b>На все товары</b>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input name="scope" value="categories" autocomplete="off" type="radio"{{ old('scope', 'all') == 'categories' ? ' checked' : '' }}> <b>На выбранные категории</b>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input name="scope" value="products" autocomplete="off" type="radio"{{ old('scope', 'all') == 'products' ? ' checked' : '' }}> <b>На выбранные товары</b>
                                                </label>
                                            </div>
                                            <div class="js_categories"{!! old('scope', 'all') != 'categories' ? ' style="display: none"' : '' !!}>
                                                <input type="hidden" name="scope_categories" value="{{ old('scope_categories') }}" autocomplete="off">
                                                <button type="button" id="js_add_categories" class="btn btn-primary">Добавить категорию</button>
                                                <span id="js_categories_count">Выбрано 0 категорий</span> <i id="clear_categories" class="glyphicon glyphicon-trash" style="cursor: pointer;"></i>
                                            </div>
                                            <div class="js_products"{!! old('scope', 'all') != 'products' ? ' style="display: none"' : '' !!}>
                                                <input type="hidden" name="scope_products" value="{{ old('scope_products') }}" autocomplete="off">
                                                <button type="button" id="js_add_products" class="btn btn-primary">Добавить товары</button>
                                                <span id="js_products_count">Выбрано 0 товаров</span> <i id="clear_products" class="glyphicon glyphicon-trash" style="cursor: pointer;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Ограничения по использованию</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input name="disposable" value="1" type="checkbox" autocomplete="off"{{ old('disposable', 1) ? ' checked' : '' }}> <b>Возможно использовать неограниченное количество раз</b>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="min_total_toggle" autocomplete="off"{{ old('min_total') ? ' checked' : '' }}> <b>Скидка действует при минимальной сумме заказа</b>
                                    </label>
                                </div>
                                <div class="input-group" id="min_total" style="max-width: 200px;{!! old('min_total') ? '' : ' display:none;' !!}">
                                    <input type="text" class="form-control" name="min_total" value="{{ old('min_total', 0) }}" autocomplete="off"{!! old('min_total') ? '' : ' disabled' !!}>
                                    <span class="input-group-addon">грн.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input name="without_sale" value="1" autocomplete="off" type="checkbox"{{ old('without_sale', 1) ? ' checked' : '' }}> <b>Не применять промокод для товаров с действующей скидкой</b>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Срок действия промокода</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <span style="display: block;margin-bottom: 10px;">Дата старта</span>
                                            <input type="text" class="form-control from" name="from" autocomplete="off" value="{{ old('from') ? old('from') : date('d.m.Y') }}">
                                        </div>
                                        <div class="col-sm-1 text-center with_end_date"{!! old('to') ? '' : ' style="display:none;"' !!}>
                                            <br>
                                            <span style="display: block;margin-top: 15px;">-</span>
                                        </div>
                                        <div class="col-sm-2 with_end_date"{!! old('to') ? '' : ' style="display:none;"' !!}>
                                            <span style="display: block;margin-bottom: 10px;">Дата окончания</span>
                                            <input type="text" class="form-control to" name="to" autocomplete="off" value="{{ old('to') ? old('to') : date('d.m.Y') }}"{!! old('to') ? '' : ' disabled' !!}>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input id="with_end_date" type="checkbox"{{ old('to') ? ' checked' : '' }} autocomplete="off"> <b>Установить дату окончания</b>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($user->hasAccess(['coupons.create']))
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                timepicker: false,
                format:'d.m.Y'
            });
            $('#coupon-price a').click(function(){
                var name = $(this).data('name');
                var text = $(this).text();

                $('#coupon-price button').html(text+' <span class="caret"></span>');
                $('#sale').attr('name', name);
            });
            $('#generate_code').click(function(){
                $.post('/admin/coupons/generate_code', {}, function(code){
                    $('#code').val(code);
                })
            });
            $('#with_end_date').click(function(){
                if(!$(this).prop('checked')){
                    $('.with_end_date').hide();
                    $('[name="to"]').prop('disabled', true);
                }else{
                    $('.with_end_date').show();
                    $('[name="to"]').prop('disabled', false);
                }
            });
            $('#min_total_toggle').change(function(){
                if(!$(this).prop('checked')){
                    $('#min_total').hide();
                    $('[name="min_total"]').prop('disabled', true);
                }else{
                    $('#min_total').show();
                    $('[name="min_total"]').prop('disabled', false);
                }
            });
            $('[name="scope"]').change(function(){
                var scope = $('[name="scope"]:checked').val();
                $('.js_categories').hide();
                $('.js_products').hide();
                if($('.js_'+scope).length){
                    $('.js_'+scope).show();
                }
            });

            $(document).on('click', '#js_add_products', function(){
                if(typeof window.products_popup_html === 'undefined'){
                    addPlaceholder();
                    if(window.xhr && window.xhr.readyState != 4){
                        window.xhr.abort();
                    }

                    window.xhr = $.post('/admin/categories/children/1', [], function(response){
                        window.products_popup_html = '';
                        for(var i in response.categories){
                            var category = response.categories[i];
                            window.products_popup_html += '<li>';
                            window.products_popup_html += '<span class="category" data-id="'+category.id+'">'+category.name+'</span>';
                            window.products_popup_html += '<div class="children">';
                            window.products_popup_html += '</div>';
                            window.products_popup_html += '</li>';
                        }
                        removePlaceholder();
                        showProductsPopup();
                    });
                }else{
                    showProductsPopup();
                }
            });

            function showProductsPopup(){
                let checked = $('[name="scope_products"]').val().split(',');
                let html = $('<form id="search_product">\n' +
                    '<div class="search">\n' +
                    '<svg class="search-ico" viewBox="0 0 64 64"><path d="M54.375 49.625L43.25 38.375c1.875-2.875 2.875-6 2.875-9.625 0-9.5-7.75-17.25-17.375-17.25-9.5 0-17.5 7.375-17.5 17s7.625 17.25 17.25 17.25c3.875 0 7-1.25 9.875-3.25l11.25 11.25zM28.75 39.375c-6 0-10.875-4.875-10.875-10.875 0-6.125 4.875-10.875 10.875-10.875 6.125 0 10.875 4.75 10.875 10.875 0 6-4.75 10.875-10.875 10.875z"></path></svg>' +
                    '<input placeholder="Поиск по товарам" data-autocomplete="input-search" autocomplete="off" name="text" type="search">\n' +
                    '<div data-output="search-results" class="search-results" style="display: none"></div>\n' +
                    '</div>\n' +
                    '<ul id="categories_tree">\n' +
                    window.products_popup_html +
                    '</ul>\n' +
                    '</form>');
                html.find('[name="products[]"]').each(function(){
                    if($.inArray($(this).val(), checked) !== -1){
                        $(this).prop('checked', true);
                    }
                });
                swal({
                    title: 'Добавление товара',
                    html: html,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonText: 'Добавить выбранный товар',
                    cancelButtonText: 'Отменить',
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            var data = [];
                            $('#search_product [name="products[]"]:checked').each(function(){
                                data.push($(this).val());
                            });
                            $('[name="scope_products"]').val(data.join(','));
                            if(data.length === 1 || (data.length > 20 && data.length%10 === 1)){
                                $('#js_products_count').text('Выбран '+data.length+' товар');
                            }else if((data.length > 4 && data.length < 21) || data.length%10 == 0 || data.length%10 > 4){
                                $('#js_products_count').text('Выбрано '+data.length+' товаров');
                            }else{
                                $('#js_products_count').text('Выбрано '+data.length+' товара');
                            }
                            window.products_popup_html = $('#categories_tree').html();
                            resolve();
                        });
                    }
                }).then(function(){
                    swal.close();
                }, function(errors) {
                    if(typeof errors !== 'string'){
                        var message = '';
                        for(err in errors){
                            message += errors[err] + '<br>';
                        }
                        swal(
                            'Ошибка!',
                            message,
                            'error'
                        );
                    }
                });
            }

            $(document).on('keyup', '[data-autocomplete="input-search"]', function(){
                var search_output = $('[data-output="search-results"]');
                var search = $(this).val();
                var target = $(this).attr('data-target');
                search_output.html('').hide();

                if (search.length > 2) {
                    var data = {limit: 1000};
                    data.search = search;
                    if(window.xhr && window.xhr.readyState != 4){
                        window.xhr.abort();
                    }
                    window.xhr = $.ajax({
                        url: '/livesearch',
                        data: data,
                        method: 'GET',
                        dataType: 'JSON',
                        success: function(resp) {
                            var html = '<ul class="products">';
                            $.each(resp, function(i, value){
                                if (value.empty) {
                                    html += '<li>';
                                    html += value.empty;
                                    html += '</li>';
                                } else {
                                    html += '<li>';
                                    html += '<div><input type="checkbox" name="products[]" value="'+value.product_id+'"></div>';
                                    html += '<div><img src="'+value.image+'"></div>';
                                    html += '<div><span>'+value.name+'</span><span>'+value.sku+'</span><span>'+value.price+' грн.</span></div>';
                                    html += '</li>';
                                }
                            });
                            html += '</ul>';

                            $.each(search_output, function(i, value){
                                if ($(value).attr('data-target') == target) {
                                    $(value).html(html).show();
                                }
                            });

                            $('#categories_tree').hide();
                        }
                    });
                } else {
                    search_output.hide();
                    $('#categories_tree').show();
                }
            });

            $(document).on('click', '#categories_tree .category', function(){
                var $this = $(this);
                var id = $this.data('id');
                if($this.parent().hasClass('active')){
                    $this.parent().removeClass('active')
                }else{
                    var children = $this.next();
                    if(children.hasClass('loaded')){
                        $this.parent().addClass('active');
                    }else{
                        $.post('/admin/categories/children/'+id, [], function(response){
                            var categories = '<ul class="categories">';
                            if($this.parents('#search_product').length){
                                for(var i in response.categories){
                                    var category = response.categories[i];
                                    categories += '<li>';
                                    categories += '<span class="category" data-id="'+category.id+'">'+category.name+'</span>';
                                    categories += '<div class="children">';
                                    categories += '</div>';
                                    categories += '</li>';
                                }
                            }else if($this.parents('#search_category').length){
                                for(var i in response.categories){
                                    var category = response.categories[i];
                                    categories += '<li>';
                                    categories += '<input type="checkbox" class="category-checkbox" name="categories[]" value="'+category.id+'">';
                                    categories += '<span'+(category.has_children ? ' class="category"' : '')+' data-id="'+category.id+'">'+category.name+'</span>';
                                    categories += '<div class="children">';
                                    categories += '</div>';
                                    categories += '</li>';
                                }
                            }
                            categories += '<ul>';
                            children.append(categories);
                            if($this.parents('#search_product').length){
                                if(response.products.length){
                                    var products = '<ul class="products">';
                                    for(var i in response.products){
                                        var product = response.products[i];
                                        products += '<li>';
                                        products += '<div><input type="checkbox" name="products[]" value="'+product.id+'"></div>';
                                        products += '<div><img src="'+product.image+'"></div>';
                                        products += '<div><span>'+product.name+'</span><span>'+product.sku+'</span><span>'+product.price+' грн.</span></div>';
                                        products += '</li>';
                                    }
                                    products += '</ul>';
                                    children.append(products);
                                }
                            }

                            children.addClass('loaded');
                            $this.parent().addClass('active');
                        });
                    }
                }
            });

            $(document).on('change', '#search_product .products input', function(){
                var products = $('#search_product [name="products[]"]:checked').length;
                if(products == 0){
                    $('.swal2-confirm').prop('disabled', true);
                    $('.swal2-confirm').text('Добавить выбранный товар');
                }else if(products == 1){
                    $('.swal2-confirm').prop('disabled', false);
                    $('.swal2-confirm').text('Добавить выбранный товар');
                }else if(products > 1){
                    $('.swal2-confirm').prop('disabled', false);
                    $('.swal2-confirm').text('Добавить выбранные товары ('+products+'шт.)');
                }
            });

            $('#clear_products').click(function(){
                $('[name="scope_products"]').val('');
                $('#js_products_count').text('Выбрано 0 товаров');
            });

            $(document).on('click', '#js_add_categories', function(){
                if(typeof window.categories_popup_html === 'undefined'){
                    addPlaceholder();
                    if(window.xhr && window.xhr.readyState != 4){
                        window.xhr.abort();
                    }

                    window.xhr = $.post('/admin/categories/children/1', [], function(response){
                        window.categories_popup_html = '';
                        for(var i in response.categories){
                            var category = response.categories[i];
                            window.categories_popup_html += '<li>';
                            window.categories_popup_html += '<input type="checkbox" class="category-checkbox" name="categories[]" value="'+category.id+'">';
                            window.categories_popup_html += '<span'+(category.has_children ? ' class="category"' : '')+' data-id="'+category.id+'">'+category.name+'</span>';
                            window.categories_popup_html += '<div class="children">';
                            window.categories_popup_html += '</div>';
                            window.categories_popup_html += '</li>';
                        }
                        removePlaceholder();
                        showCategoriesPopup();
                    });
                }else{
                    showCategoriesPopup();
                }
            });

            function showCategoriesPopup(){
                let checked = $('[name="scope_categories"]').val().split(',');
                let html = $('<form id="search_category">\n' +
                    '<div class="search">\n' +
                    '<svg class="search-ico" viewBox="0 0 64 64"><path d="M54.375 49.625L43.25 38.375c1.875-2.875 2.875-6 2.875-9.625 0-9.5-7.75-17.25-17.375-17.25-9.5 0-17.5 7.375-17.5 17s7.625 17.25 17.25 17.25c3.875 0 7-1.25 9.875-3.25l11.25 11.25zM28.75 39.375c-6 0-10.875-4.875-10.875-10.875 0-6.125 4.875-10.875 10.875-10.875 6.125 0 10.875 4.75 10.875 10.875 0 6-4.75 10.875-10.875 10.875z"></path></svg>' +
                    '<input placeholder="Поиск" data-autocomplete="categories-search" autocomplete="off" name="text" type="search">\n' +
                    '<div data-output="search-results" class="search-results" style="display: none"></div>\n' +
                    '</div>\n' +
                    '<ul id="categories_tree">\n' +
                    window.categories_popup_html +
                    '</ul>\n' +
                    '</form>');
                html.find('[name="categories[]"]').each(function(){
                    if($.inArray($(this).val(), checked) !== -1){
                        $(this).prop('checked', true);
                    }
                });
                swal({
                    title: 'Добавление категории',
                    html: html,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonText: 'Добавить выбранные категории',
                    cancelButtonText: 'Отменить',
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            var data = [];
                            $('#search_category [name="categories[]"]:checked').each(function(){
                                data.push($(this).val());
                            });
                            $('[name="scope_categories"]').val(data.join(','));
                            if(data.length === 1 || (data.length > 20 && data.length%10 === 1)){
                                $('#js_categories_count').text('Выбрана '+data.length+' категория');
                            }else if((data.length > 4 && data.length < 21) || data.length%10 == 0 || data.length%10 > 4){
                                $('#js_categories_count').text('Выбрано '+data.length+' категории');
                            }else{
                                $('#js_categories_count').text('Выбрано '+data.length+' категорий');
                            }
                            window.categories_popup_html = $('#categories_tree').html();
                            resolve();
                        });
                    }
                }).then(function(){
                    swal.close();
                }, function(errors) {
                    if(typeof errors !== 'string'){
                        var message = '';
                        for(err in errors){
                            message += errors[err] + '<br>';
                        }
                        swal(
                            'Ошибка!',
                            message,
                            'error'
                        );
                    }
                });
            }

            $(document).on('change', '#search_category input', function(){
                var categories = $('#search_category [name="categories[]"]:checked').length;
                if(categories == 0){
                    $('.swal2-confirm').prop('disabled', true);
                    $('.swal2-confirm').text('Добавить выбранную категорию');
                }else if(categories == 1){
                    $('.swal2-confirm').prop('disabled', false);
                    $('.swal2-confirm').text('Добавить выбранную категорию');
                }else if(categories > 1){
                    $('.swal2-confirm').prop('disabled', false);
                    $('.swal2-confirm').text('Добавить выбранные товары ('+categories+'шт.)');
                }
            });

            $(document).on('keyup', '[data-autocomplete="categories-search"]', function(){
                var search_output = $('[data-output="search-results"]');
                var search = $(this).val();
                var target = $(this).attr('data-target');
                search_output.html('').hide();

                if (search.length > 2) {
                    var data = {limit: 1000};
                    data.search = search;
                    if(window.xhr && window.xhr.readyState != 4){
                        window.xhr.abort();
                    }
                    window.xhr = $.ajax({
                        url: '/admin/categories/livesearch',
                        data: data,
                        method: 'GET',
                        dataType: 'JSON',
                        success: function(resp) {
                            var html = '<ul class="categories">';
                            $.each(resp, function(i, value){
                                if (value.empty) {
                                    html += '<li>';
                                    html += value.empty;
                                    html += '</li>';
                                } else {
                                    html += '<li>';
                                    html += '<div><input type="checkbox" name="categories[]" value="'+value.id+'"></div>';
                                    html += '<div><span>'+value.name+'</span></div>';
                                    html += '</li>';
                                }
                            });
                            html += '</ul>';

                            $.each(search_output, function(i, value){
                                if ($(value).attr('data-target') == target) {
                                    $(value).html(html).show();
                                }
                            });

                            $('#categories_tree').hide();
                        }
                    });
                } else {
                    search_output.hide();
                    $('#categories_tree').show();
                }
            });

            $('#clear_categories').click(function(){
                $('[name="scope_categories"]').val('');
                $('#js_categories_count').text('Выбрано 0 категорий');
            });
        });
    </script>
@endsection
