<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Просмотр заказа № <?php echo e($order->id); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-title">
        <div class="row">
            <div class="col-sm-10">
                <h1>Просмотр заказа № <?php echo e($order->id); ?></h1>
            </div>
            <div class="col-sm-2 text-right">
                <a class="btn btn-success" href="/admin/orders/invoice/<?php echo $order->id; ?>" data-toggle="tooltip" data-placement="top" title="Накладная">
                    <i class="glyphicon glyphicon-print"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                
                
                
                
                
                <a class="btn btn-primary" onclick="window.history.back()" href="javascript:void(0)">
                    <i class="glyphicon glyphicon-backward"></i>
                </a>
                <?php if(isset($order->user->email) && strpos($order->user->email, '@placeholder.com') === false): ?>
                    <button type="button" class="btn btn-primary" id="js_send_email">Email</button>
                <?php endif; ?>
                <?php if(!empty($order->user->phone)): ?>
                    <button type="button" class="btn btn-primary" id="js_send_sms" data-phone="<?php echo e($order->user->phone); ?>">SMS</button>
                    <a href="viber://chat?number=<?php echo e($order->user->phone); ?>" class="btn btn-primary">Viber</a>
                <?php endif; ?>
            </div>
            <div class="col-sm-8">
                <?php if(session('message-success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('message-success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php elseif(session('message-error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(session('message-error')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            
                
                
                
                
                
            
        </div>
    </div>

    <?php echo $__env->make('admin.orders.edit_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        jQuery(document).ready(function(){
            // Добавить товар в заказ
            $(document).on('click', '#add_to_order', function(){
                addPlaceholder();
                if(window.xhr && window.xhr.readyState != 4){
                    window.xhr.abort();
                }
                window.xhr = $.post('/admin/categories/children/1', [], function(response){
                    var categories = '';
                    for(var i in response.categories){
                        var category = response.categories[i];
                        categories += '<li>';
                        categories += '<span class="category" data-id="'+category.id+'">'+category.name+'</span>';
                        categories += '<div class="children">';
                        categories += '</div>';
                        categories += '</li>';
                    }
                    removePlaceholder();
                    swal({
                        title: 'Добавление товара',
                        html:
                        '<form id="search_product">\n' +
                        '<div class="search">\n' +
                        '<svg class="search-ico" viewBox="0 0 64 64"><path d="M54.375 49.625L43.25 38.375c1.875-2.875 2.875-6 2.875-9.625 0-9.5-7.75-17.25-17.375-17.25-9.5 0-17.5 7.375-17.5 17s7.625 17.25 17.25 17.25c3.875 0 7-1.25 9.875-3.25l11.25 11.25zM28.75 39.375c-6 0-10.875-4.875-10.875-10.875 0-6.125 4.875-10.875 10.875-10.875 6.125 0 10.875 4.75 10.875 10.875 0 6-4.75 10.875-10.875 10.875z"></path></svg>' +
                        '<input placeholder="Введите название или код товара" data-autocomplete="input-search" autocomplete="off" name="text" type="search">\n' +
                        '<div data-output="search-results" class="search-results" style="display: none"></div>\n' +
                        '</div>\n' +
                        '<ul id="categories_tree">\n' +
                        categories +
                        '</ul>\n' +
                        '</form>',
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Добавить выбранный товар',
                        cancelButtonText: 'Отменить',
                        preConfirm: () => {
                            return new Promise((resolve, reject) => {
                                var data = {};
                                var i = 0;
                                $('#search_product [name="products[]"]:checked').each(function(){
                                    data['products['+i+']'] = $(this).val();
                                    i++;
                                });
                                $.post(
                                    "/admin/orders/edit/"+$('#edit_form').data('order-id')+"/add_products",
                                    data,
                                    function(response){
                                        if(response.result === 'success'){
                                            resolve(response.html);
                                        }else{
                                            reject(response.errors);
                                        }
                                    }
                                );
                            });
                        }
                    }).then(function(html){
                        $('#edit_form').replaceWith(html);
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

                    $('.swal2-confirm').prop('disabled', true);
                });
            });

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
                                    html += '<div><span>'+value.name+'</span><span>'+value.sku+'(<b>'+(value.stock == -2 ? 'Нет в наличии' : (value.stock == -1 ? 'Под заказ' : (value.stock == 0 ? 'Ожидается' : 'В наличии')))+'</b>)</span><span>'+value.price+' грн.</span></div>';
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
                            for(var i in response.categories){
                                var category = response.categories[i];
                                categories += '<li>';
                                categories += '<span class="category" data-id="'+category.id+'">'+category.name+'</span>';
                                categories += '<div class="children">';
                                categories += '</div>';
                                categories += '</li>';
                            }
                            categories += '<ul>';
                            children.append(categories);
                            if(response.products.length){
                                var products = '<ul class="products">';
                                for(var i in response.products){
                                    var product = response.products[i];
                                    products += '<li>';
                                    products += '<div><input type="checkbox" name="products[]" value="'+product.id+'"></div>';
                                    products += '<div><img src="'+product.image+'"></div>';
                                    products += '<div><span>'
                                        +product.name
                                        +' (<b>'+(product.stock == -2 ? 'Нет в наличии' : (product.stock == -1 ? 'Под заказ' : (product.stock == 0 ? 'Ожидается' : 'В наличии')))+'</b>)</span><span>'
                                        +product.sku
                                        +'</span><span>'
                                        +product.price
                                        +' грн.</span></div>';
                                    products += '</li>';
                                }
                                products += '</ul>';
                                children.append(products);
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

            $(document).on('change keyup', '#edit_form .count_field', function(e){
                var $this = $(this);
                if($this.val() >= 1) {
                    if (window.xhr && window.xhr.readyState != 4) {
                        window.xhr.abort();
                    }
                    addPlaceholder();
                    window.xhr = $.post(
                        "/admin/orders/edit/" + $('#edit_form').data('order-id') + "/change_qty",
                        {'product': $this.data('id'), 'qty': $this.val()},
                        function (response) {
                            if (response.result === 'success') {
                                $('#edit_form').replaceWith(response.html);
                                $('#edit_form .count_field[data-id="'+$this.data('id')+'"]').focus();
                                removePlaceholder();
                            }
                        }
                    );
                }else{
                    e.preventDefault();
                }
            });

            // Удалить товар из заказа
            $(document).on('click', '.remove-product-from-order', function(){
                swal({
                    title: 'Вы уверены?',
                    text: "Этот товар будет удалён из заказа!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, удалить его!',
                    cancelButtonText: 'Нет, я ошибся.'
                }).then((result) => {
                        addPlaceholder();
                        $.post('/admin/orders/edit/'+$(this).data('order-id')+'/remove_product', {'key': $(this).data('key')}, function(response){
                            $('#edit_form').replaceWith(response);
                            removePlaceholder();
                        });
                    },
                    (cancel) => {});
            });

            $(document).on('click', '.update_order_product', function () {
                addPlaceholder();
                var $this = $(this);
                $.post('/admin/orders/get_product_data', {'order': $this.data('order-id'), 'product': $this.data('key')}, function(response){
                    removePlaceholder();
                    if(response.result == 'success'){
                        swal({
                            title: 'Изменение цены товара в заказе',
                            html: '<div class="swal2-content">' +
                            '<div class="form-group">' +
                            '<label for="company_name">Цена одного товара:</label><br>' +
                            '<input id="product_price" name="price" value="'+response.product.price+'" type="text" class="swal2-input">' +
                            '</div>' +
                            '<div class="form-group">' +
                            '<label for="company_name">Скидка:</label><br>' +

                            '<div class="input-group">\n' +
                            '<span class="input-group-addon">\n' +
                            '<label><input class="product_sale_price" type="radio" name="percent" value="1"'+(response.product.sale_percent ? ' checked' : '')+'> Процент</label>\n' +
                            '<label><input class="product_sale_price" type="radio" name="percent" value="0"'+(response.product.sale ? ' checked' : '')+'> Сумма</label>\n' +
                            '</span>\n' +
                            '<input type="text" id="product_sale" name="sale" class="form-control" value="'+(response.product.sale_percent ? response.product.sale_percent : response.product.sale)+'" style="height: 40px;">\n' +
                            '</div>' +

                            '</div>' +
                            '</div>',
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            focusConfirm: false,
                            preConfirm: () => {
                                return new Promise((resolve, reject) => {
                                    let formData = new FormData();
                                    formData.append('order', $this.data('order-id'));
                                    formData.append('product', $this.data('key'));
                                    formData.append('price', $('#product_price').val());
                                    let sale = $('#product_sale').val();
                                    let sale_type = $('.product_sale_price:checked');
                                    if(sale != '' && sale_type.length){
                                        if(sale_type.val() == 1)
                                            formData.append('sale_percent', sale);
                                        else
                                            formData.append('sale', sale);
                                    }
                                    swal.showLoading();
                                    $.ajax({
                                        type:"POST",
                                        url:"/admin/orders/update_product_data",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        async:true,
                                        success: function(response){
                                            if(response.result === 'success'){
                                                resolve(response.html);
                                            }else{
                                                reject(response.errors);
                                            }
                                        }
                                    });
                                })
                            }
                        }).then(function(html) {
                            $('#edit_form').replaceWith(html);
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
                    }else{
                        swal(
                            'Ошибка!',
                            response.error,
                            'error'
                        );
                    }
                });
            });

            $(document).on('click', '#js_send_email', function(){
                swal({
                    title: 'Email клиенту',
                    html: '<p>ФИО клиента: <?php echo e(isset($order->user->name) ? $order->user->name : ''); ?></p><p>Заказ: №<?php echo e($order->id); ?></p><p>От: <?php echo e(isset($order->user->email) && strpos($order->user->email, '@placeholder.com') === false ? $order->user->email : ''); ?></p>',
                    input: 'textarea',
                    inputLabel: 'Message',
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonText: 'Отправить',
                    cancelButtonText: 'Отменить',
                    preConfirm: (value) => {
                        return new Promise((resolve, reject) => {
                            var data = {text: value};
                            $.post(
                                "/admin/orders/send_email/"+$('#edit_form').data('order-id'),
                                data,
                                function(response){
                                    if(response.result === 'success'){
                                        resolve(response.result);
                                    }else{
                                        reject(response.result);
                                    }
                                }
                            );
                        });
                    }
                }).then(function(){
                    swal(
                        'Отправлено!',
                        '',
                        'success'
                    );
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
            });

            $(document).on('click', '#js_send_sms', function(){
                $.post(
                    "/admin/orders/get_sms_club_balance",
                    {},
                    function(response){
                        let balance = response.data.success_request.info.money;
                        balance = Math.round(balance*100)/100;
                        let currency = response.data.success_request.info.currency;
                        if(currency === 'UAH'){
                            currency = ' ₴';
                        }
                        swal({
                            title: 'Отправка SMS',
                            html: '<p>Остаток на балансе: '+balance+currency+'</p>' +
                            '<a href="https://my.smsclub.mobi/pay?action=index" target="_blank">Пополнить баланс SMSClub</a>' +
                            '<ul id="js_sms_templates">' +
                            '<li><label><input type="radio" name="type" value="<?php echo str_replace(['%id%', '"', PHP_EOL], [$order->id, "'", "\n"], $sms['payment']); ?>"><span>Реквизиты для оплаты</span></label></li>' +
                            '<li><label><input type="radio" name="type" value="<?php echo str_replace(['%id%', '"', PHP_EOL], [$order->id, "'", "\n"], $sms['delivery']); ?>"><span>Транспортная накладная</span></label></li>' +
                            '<li><label><input type="radio" name="type" value="<?php echo str_replace(['%id%', '"', PHP_EOL], [$order->id, "'", "\n"], $sms['promo']); ?>"><span>Промокод</span></label></li>' +
                            '<li><label><input type="radio" name="type" value=""><span>Другое</span></label></li>' +
                            '</ul>',
                            input: 'textarea',
                            inputPlaceholder: 'Введите реквизиты',
                            showCancelButton: true,
                            showLoaderOnConfirm: true,
                            confirmButtonText: 'Отправить',
                            cancelButtonText: 'Отменить',
                            preConfirm: (value) => {
                                return new Promise((resolve, reject) => {
                                    var data = {text: value};
                                    $.post(
                                        "/admin/orders/send_sms/"+$('#edit_form').data('order-id'),
                                        data,
                                        function(response){
                                            if(response.result === 'success'){
                                                resolve(response.result);
                                            }else{
                                                reject(response.result);
                                            }
                                        }
                                    );
                                });
                            }
                        }).then(function(){
                            swal(
                                'Отправлено!',
                                '',
                                'success'
                            );
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
                );
            });

            $(document).on('change', '#js_sms_templates input', function(){
                $('.swal2-textarea').val($(this).val());
            });

            window.newpostUpdate = function(id, value) {
                if (id === 'city') {
                    var data = {
                        city_id: value
                    };
                    var path = '/checkout/warehouses';
                    var selector = $('#warehouse');
                } else if (id === 'region') {
                    var data = {
                        region_id: value
                    };
                    var path = '/checkout/cities';
                    var selector = $('#city');
                    $('#warehouse').html('<option value="">Выберите населённый пункт</option>');
                }
                selector.find('option').text('Обновляются данные, ожидайте...');
                selector.attr('disabled', 'disabled');

                jQuery.ajax({
                    url: path,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function() {

                    },
                    success: function(response){
                        if (response.error) {

                        } else if (response.success) {
                            var html = '<option value="0">Сделайте выбор</option>';
                            jQuery.each(response.success, function(i, resp){
                                if (id === 'city') {
                                    var info = resp.address_ru;
                                } else if (id === 'region') {
                                    var info = resp.name_ru;
                                }
                                html += '<option value="' + resp.id + '">' + info + '</option>';
                            });
                            selector.html(html);
                            selector.prop('disabled', false);
                        }
                    }
                })
            };

            window.justinUpdate = function(id, value) {
                if (id === 'city') {
                    var data = {
                        city_id: value
                    };
                    var path = '/checkout/justin_warehouses';
                    var selector = $('#warehouse');
                } else if (id === 'region') {
                    var data = {
                        region_id: value
                    };
                    var path = '/checkout/justin_cities';
                    var selector = $('#city');
                    $('#warehouse').html('<option value="">Выберите населённый пункт</option>');
                }
                selector.find('option').text('Обновляются данные, ожидайте...');
                selector.attr('disabled', 'disabled');

                jQuery.ajax({
                    url: path,
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(response){
                        if (response.error) {

                        } else if (response.success) {
                            var html = '<option value="0">Сделайте выбор</option>';
                            jQuery.each(response.success, function(i, resp){
                                html += '<option value="' + i + '">' + resp.name + '</option>';
                            });
                            selector.html(html);
                            selector.prop('disabled', false);
                        }
                    }
                })
            };

            $('#js_delivery_method').change(function(){
                var $this = $(this);
                var method = $this.val();
                if(method === ''){
                    $this.parents('table').find('.delivery').remove();
                }else{
                    var data = {
                        delivery: method,
                        id: $(this).data('order-id')
                    };

                    $.post('/admin/orders/delivery', data, function(response){
                        $this.parents('table').find('.delivery').remove();
                        $this.parents('tr').after(response.delivery);
                    }, 'json');
                }

            });

            $(document).on('click', '#js_save_ttn', function(){
                $.post('/admin/orders/update_ttn', {id: $('#edit_form').data('order-id'), ttn: $('#js_ttn').val()}, function(){

                });
            });

            $(document).on('click', '#js_generate_np_ttn', function(){
                $.post('/admin/orders/get_ttn_form', {id: $('#edit_form').data('order-id')}, function(response){
                    swal({
                        title: 'Создание экспресс-накладной',
                        html: response.html,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Сгенерировать',
                        cancelButtonText: 'Отменить',
                        preConfirm: (value) => {
                            return new Promise((resolve, reject) => {
                                var data = {text: value};
                                $.post(
                                    "/admin/orders/generate_ttn/"+$('#edit_form').data('order-id'),
                                    data,
                                    function(response){
                                        if(response.result === 'success'){
                                            resolve(response.result);
                                        }else{
                                            reject(response.result);
                                        }
                                    }
                                );
                            });
                        }
                    }).then(function(){
                        swal(
                            'Отправлено!',
                            '',
                            'success'
                        );
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
                });
            });
        });
    </script>

    <style>
        #js_sms_templates{
            list-style: none;
            padding: 0;
            text-align: left;
        }
        #js_sms_templates span{
            margin-left: 10px;
        }
        .or{
            display: block;
            position: relative;
            margin: 5px 0;
        }
        .or:before{
            content: '';
            width: 100%;
            display: block;
            position: absolute;
            top: 50%;
            border-bottom: 1px dashed #ccc;
        }
        .or span{
            padding: 0 5px;
            background-color: #fff;
            display: inline-block;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        table.table.table-hover > tbody > tr:hover .or span{
            background: #e0effa;
        }
        .genetate_np_ttn_form label{
            font-size: 12px;
            text-align: left;
            display: block;
        }
        .genetate_np_ttn_form input,
        .genetate_np_ttn_form textarea,
        .genetate_np_ttn_form select{
            font-size: 12px;
        }
    </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>