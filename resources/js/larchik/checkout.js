// import PerfectScrollbar from "perfect-scrollbar/types/perfect-scrollbar";

$(function(){
    $.fn.validateTooltip = function (options) {
        let $this = $(this);

        $this.tooltip = $('<div class="validate-error">' +
            '<div class=""><div><i></i>' + options.text + '</div></div>' +
            '</div>');

        $(this).closest('.input-wrapper').addClass('err').append($this.tooltip);

        $this.click(function () {
            $(this).closest('.input-wrapper').removeClass('err');
            $this.tooltip.remove();
        });
    };

    $('#js_order_checkout .js_phone').mask('+99 (999) 999-9999');

    $('#js_go_to_second_step').click(function(){
        let error = false;
        let re;
        let name = $('#js_order_checkout .js_name');
        let phone = $('#js_order_checkout .js_phone');
        let email = $('#js_order_checkout .js_email');
        if(name.val() === ''){
            error = true;
            name.validateTooltip({
                text: name.data('validate-required')
            });
        }
        if(phone.val() === ''){
            error = true;
            phone.validateTooltip({
                text: phone.data('validate-required')
            });
        }
        re = /^\+?[-\(\)\d\s]{5,19}$/;
        if(!re.test(phone.val())){
            error = true;
            phone.validateTooltip({
                text: phone.data('validate-phone')
            });
        }
        if(email.val() === ''){
            error = true;
            email.validateTooltip({
                text: email.data('validate-required')
            });
        }
        re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(email.val())){
            error = true;
            email.validateTooltip({
                text: email.data('validate-email')
            });
        }

        if(!error){
            $('.js_step_1_filled_content').text(name.val()+', '+email.val()+', '+phone.val());
            $('.js_step_1').addClass('hidden');
            $('.js_step_1_filled, .js_step_2').removeClass('hidden');
        }
    });

    $('.js_back_to_first_step').click(function(){
        $('.js_step_1').removeClass('hidden');
        $('.js_step_1_filled, .js_step_2').addClass('hidden');
    });

    $('#js_go_to_third_step').click(function(){
        let error = false;
        let text = '';
        let country_input = $('.js_country');
        let city_input = country_input.val() === 'ukraine' ? $('.js_ukrainian_city') : $('.js_city');
        let delivery_input = $('.js_delivery:checked');

        if(delivery_input.length === 0 || country_input.val() === '' || city_input.val() === ''){
            error = true;
        }else{
            let delivery = delivery_input.val();
            let template = delivery_input.data('template');

            if(delivery === 'newpost'){
                let warehouse = $('.js_newpost_warehouse');
                if(warehouse.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: warehouse.data('validate-required')
                    });
                }else{
                    text = template.replace('%city%', city_input.find('[value="'+city_input.val()+'"]').text()).replace('%warehouse%', warehouse.find('[value="'+warehouse.val()+'"]').text());
                }
            }else if(delivery === 'newpost_courier'){
                let address = $('.js_newpost_courier_address');
                if(address.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: address.data('validate-required')
                    });
                }else{
                    text = template.replace('%city%', city_input.find('[value="'+city_input.val()+'"]').text()).replace('%address%', address.val());
                }
            }else if(delivery === 'ukrpost'){
                let street = $('.js_ukrpost_street');
                if(street.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: street.data('validate-required')
                    });
                }
                let house = $('.js_ukrpost_house');
                if(house.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: house.data('validate-required')
                    });
                }
                let apart = $('.js_ukrpost_apart');
                if(apart.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: apart.data('validate-required')
                    });
                }
                let index = $('.js_ukrpost_index');
                if(index.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: index.data('validate-required')
                    });
                }

                if(!error){
                    text = template.replace('%city%', city_input.find('[value="'+city_input.val()+'"]').text())
                        .replace('%street%', street.val())
                        .replace('%house%', house.val())
                        .replace('%apart%', apart.val())
                        .replace('%index%', index.val());
                }
            }else if(delivery === 'pickup'){
                let warehouse = $('.js_pickup_warehouse:checked');
                if(warehouse.length === 0){
                    error = true;
                    $('.address-list').addClass('error');
                }else{
                    text = template.replace('%city%', city_input.find('[value="'+city_input.val()+'"]').text()).replace('%warehouse%', warehouse.val());
                }
            }else if(delivery === 'courier'){
                let street = $('.js_courier_street');
                if(street.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: street.data('validate-required')
                    });
                }
                let house = $('.js_courier_house');
                if(house.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: house.data('validate-required')
                    });
                }
                let apart = $('.js_courier_apart');
                if(apart.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: apart.data('validate-required')
                    });
                }
                let index = $('.js_courier_index');
                if(index.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: index.data('validate-required')
                    });
                }

                let details = $('.js_courier_details');

                if(!error){
                    text = template.replace('%city%', city_input.find('[value="'+city_input.val()+'"]').text())
                        .replace('%street%', street.val())
                        .replace('%house%', house.val())
                        .replace('%apart%', apart.val())
                        .replace('%index%', index.val())
                        .replace('%details%', details.val());
                }
            }else if($.inArray(delivery, ['emc', 'dhl', 'fedex', 'newpost_international', 'tnt']) !== false){
                let street = $('.js_international_street');
                if(street.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: street.data('validate-required')
                    });
                }
                let house = $('.js_international_house');
                if(house.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: house.data('validate-required')
                    });
                }
                let apart = $('.js_international_apart');
                if(apart.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: apart.data('validate-required')
                    });
                }
                let index = $('.js_international_index');
                if(index.val() === ''){
                    error = true;
                    name.validateTooltip({
                        text: index.data('validate-required')
                    });
                }

                if(!error){
                    text = template.replace('%country%', country_input.find('[value="'+country_input.val()+'"]').text())
                        .replace('%city%', city_input.val())
                        .replace('%delivery%', delivery_input.data('name'))
                        .replace('%street%', street.val())
                        .replace('%house%', house.val())
                        .replace('%apart%', apart.val())
                        .replace('%index%', index.val());
                }
            }
        }

        if(!error){
            $('.js_step_2_filled_content').text(text);
            $('.js_step_2').addClass('hidden');
            $('.js_step_2_filled, .js_step_3').removeClass('hidden');
        }
    });

    $('.js_back_to_second_step').click(function(){
        $('.js_step_2').removeClass('hidden');
        $('.js_step_2_filled, .js_step_3').addClass('hidden');
    });

    $('.js_payment').change(function(){
        let payment = $(this).val();
        $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+'/checkout/change_payment', {payment: payment}, function(response){
            if(response.result === 'success'){
                $('#js_checkout_total_block').replaceWith(response.cart.html);
            }
        }, 'json');
    });

    /**
     * Обработка оформления заказа
     */
    window.loading = false;
    $('#js_order_checkout').on('submit', function (e) {
        e.preventDefault();
        if(window.loading)
            return false;
        window.loading = true;
        $('#js_order_checkout .checkout-confirmation__btn').prop('disabled', true);
        $('.validate-error').remove();
        var form = $(this);
        var error_div = form.find('.error-messages');
        var error_phone = form.find('.error-phone');
        var error_name = form.find('.error-name');
        $('#promocode, #promocode2').prop('disabled', false);
        var data = $(this).serialize();
        $('#promocode, #promocode2').each(function(){
            if(!$(this).next().hasClass('hidden'))
                $(this).prop('disabled', true);
        });

        $.ajax({
            url: (location.pathname.substr(0, 3) == '/ru' ? '/ru' : '')+'/checkout',
            type: 'post',
            data: data,
            beforeSend: function beforeSend() {
                error_div.removeClass('active');
                error_phone.removeClass('active');
                error_name.removeClass('active');
                $('select, input').removeClass('input-error');
            },
            success: function success(response) {
                if (response.error) {
                    $.each(response.error, function (id, text) {
                        var error = id.split('.');
                        if(error.length == 1){
                            $('[name="' + error[0] + '"]').addClass('input-error').validateTooltip({
                                text: text
                            });
                        }else if(error.length == 2){
                            $('[name="' + error[0] + '[' + error[1] + ']"]').addClass('input-error').validateTooltip({
                                text: text
                            });
                        }
                    });
                    $('html, body').scrollTop($('#js_order_checkout').offset().top - 60);
                } else if (response.success) {
                    if(typeof dataLayer !== 'undefined'){
                        dataLayer.push({'event':'checkout'});
                    }
                    if(response.success == 'liqpay'){
                        $('body').prepend(
                            '<form method="POST" id="liqpay-form" action="' + response.liqpay.url + '" accept-charset="utf-8">' +
                            '<input type="hidden" name="data" value="' + response.liqpay.data + '" />' +
                            '<input type="hidden" name="signature" value="' + response.liqpay.signature + '" />' +
                            '</form>');
                        $('#liqpay-form').submit();

                        // LiqPayCheckout.init({
                        //     data: response.liqpay.data,
                        //     signature: response.liqpay.signature,
                        //     embedTo: "#liqpay_checkout",
                        //     mode: "embed" // embed || popup
                        // }).on("liqpay.callback", function (data) {
                        //     console.log(data.status);
                        //     console.log(data);
                        //     window.location = '/checkout/complete?order_id=' + response.order_id;
                        // }).on("liqpay.ready", function (data) {
                        //     $('#liqpay_checkout').css('display', 'block');
                        // }).on("liqpay.close", function (data) {
                        //     window.location = '/checkout/complete?order_id=' + response.order_id;
                        // });

                        // window.location = (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/thanks?order_id=' + response.order_id;
                    } else if (response.success === 'wayforpay') {
                        $('body').prepend(response.form);
                        $('#wayforpay-form').submit();
                    } else if (response.success === 'ipay') {
                        window.location = response.redirect;
                    } else if (response.success === 'redirect') {
                        window.location = (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/thanks?order_id=' + response.order_id;
                    }
                }
                window.loading = false;
                $('#js_order_checkout .checkout-confirmation__btn').prop('disabled', false);
            },
            error: function(){
                window.loading = false;
                $('#js_order_checkout .checkout-confirmation__btn').prop('disabled', false);
            }
        });
    });

    // $('#js_order_checkout [name="delivery"]').change(function(){
    //     var method = $(this).val();
    //     var data = {
    //         delivery: method,
    //         city: $('#city').val()
    //     };
    //
    //     $.post(($('html').attr('lang') == 'ru' ? '' : '/'+$('html').attr('lang'))+'/checkout/delivery', data, function(response){
    //         $('#js_delivery_form').html(response.delivery).show();
    //         $('#js_delivery_form .cart-select').SumoSelect({search: true, searchText: 'Поиск', noMatch: 'Нет результатов для "{0}"'});
    //     }, 'json');
    // });

    // if($('#js_order_checkout [name="delivery"]').val() !== 'pickup'){
    //     $('#js_order_checkout [name="delivery"]').trigger('change');
    // }

    $('#promocode, #promocode2').change(function(){
        if($('#promocode').val() == $('#promocode2').val()){
            return;
        }
        var $this = $(this);
        var code = $this.val();
        $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+'/apply_coupon', {code: code, delivery: $('[name="delivery"]:checked').val()}, function(response){
            if(response.result === 'success'){
                $this.next().removeClass('hidden');
                $this.prop('disabled', true).css('background', 'transparent');
                $('.checkout-confirmation__sum, .checkout-confirmation__discount, .checkout-confirmation__footer').remove();
                $('.checkout-confirmation__block .checkout-subtitle').after(response.cart.html);
                $('.checkout-right .checkout-confirmation__block').html(response.cart.html);
            }
        }, 'json');
    });

    /**
     * Удаление товара из заказа
     */
    $(document).on('click', '.js_remove_product_from_order', function(e){
        e.preventDefault();
        let $this = $(this);
        let id = $this.data('prod-id');
        $(this).parents('.checkout-item').slideUp('slow').promise().done(function() {
            $(this).closest('.checkout-item').remove();
        });

        let data = {
            action: 'remove',
            product_id: id
        };

        update_order(data);
    });

    /**
     * Обновление колличества товара в заказе
     */
    $(document).on('input change', '.js_order_qty', function(){
        let $this = $(this);
        let data = {
            action: 'update',
            product_id: $this.data('prod-id'),
            quantity: $this.val()
        };

        update_order(data);
    });

    $('.btn-promo').on('click', function() {
        var code =  $('#js_promocode').val();
        if(code != ''){
            $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+'/apply_coupon', {code: code}, function(response){
                if(response.result === 'success'){
                    $('#js_checkout_total_block').replaceWith(response.cart.html);
                    // $('.checkout-promo__form').addClass('disabled');
                    // $('.checkout-promo__form .input-wrapper').removeClass('err');
                    // $('.cart-popup__promo').addClass('active');
                }else if(typeof response.msg !== 'undefined'){
                    $('.checkout-promo__form .validate-error').text(response.msg);
                    $('.checkout-promo__form .input-wrapper').addClass('err');
                }
            }, 'json');
        }
    });

    window.livesearch_cities = false;
    function livesearch_cities(){
        var text = $('.form-select-city.ukraina .search-txt').val();
        if(text.length !== 3)
            return false;

        if(window.livesearch_cities){
            setTimeout(function(){
                livesearch_cities(text);
            }, 100);
        }else if(text !== window.livesearchcities_text){
            window.livesearch_cities = true;
            window.livesearchcities_text = text;
            let data = {search: text};
            $.ajax({
                url: ($('html').attr('lang') == 'ru' ? '' : '/'+$('html').attr('lang'))+'/checkout/search_cities',
                data: data,
                method: 'POST',
                dataType: 'JSON',
                success: function(resp){
                    let input = $('.form-select-city.ukraina .search-txt');
                    let select = $('.form-select-city.ukraina select');
                    let text = input.val();
                    let cities = resp.cities;

                    select[0].sumo.removeAll();
                    for(let i in cities){
                        select[0].sumo.add(i, cities[i]);
                    }

                    input.val(text).trigger('keyup');

                    window.livesearch_cities = false;
                }
            });
        }
    }

    $('.form-select-city.ukraina').on('keyup', '.search-txt', function(e){
        e.preventDefault();
        livesearch_cities();
    });
});

function update_order(data){
    $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+"/cart/update", data, function(cart){
        if($('.js_cart_counter small').length){
            $('.js_cart_counter small').text(cart.count);
        }else{
            $('.js_cart_counter').append('<small>'+cart.count+'</i>');
        }

        if($('.js_cart_price').length)
            $('.js_cart_price').text(number_format(cart.total, 2, '.', ' '));

        $('.js_cart_sum').each(function(){
            let price = parseFloat($(this).data('price'));
            let qty = parseInt($(this).parents('.cart-popup__item-info').find('.js_order_qty').val());

            let sum = number_format(price * qty, 2, '.', ' ');
            $(this).text(sum);
        });

        $('.js_checkout_total').text(number_format(cart.total, 2, '.', '') + ' грн');

        if($('.js_checkout_coupon_sale').length)
            $('.js_checkout_coupon_sale').text(number_format(cart.coupon_sale, 2, '.', ' '));

        if(cart.count == 0){
            location = (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/produkcija';
        }
    });
}

function number_format( number, decimals, dec_point, thousands_sep ) {
    var i, j, kw, kd, km;

    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

    return km + kw + kd;
}


/**
 * Загрузка городов и отделений Новой Почты
 * @param id
 * @param value
 */
window.newpostUpdate = function(id, value) {
    if (id === 'city') {
        var data = {
            city_id: value
        };
        var path = '/checkout/warehouses';
        var selector = $('#checkout-step__warehouse');
        $('#checkout-step__city').removeClass('input-error');
        selector.removeClass('input-error');
    } else if (id === 'region') {
        var data = {
            region_id: value
        };
        var path = '/checkout/cities';
        var selector = $('#checkout-step__city');
        selector.removeClass('input-error');
        $('#checkout-step__region').removeClass('input-error');
        $('#checkout-step__warehouse').html('<option value="">'+(location.pathname.substr(0, 3) == '/ua' ? 'Оберіть населений пункт' : 'Выберите населённый пункт')+'</option>').removeClass('input-error');
    }
    selector.find('option').text(location.pathname.substr(0, 3) == '/ua' ? 'Оновлюються дані, чекайте...' : 'Обновляются данные, ожидайте...');
    selector.attr('disabled', 'disabled');

    $.ajax({
        url: ($('html').attr('lang') == 'ru' ? '' : '/'+$('html').attr('lang'))+path,
        data: data,
        type: 'post',
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(response){
            if (response.error) {

            } else if (response.success) {
                var html = '<option value="0">'+response.msg+'</option>';
                jQuery.each(response.success, function(i, resp){
                    html += '<option value="' + i + '">' + resp + '</option>';
                });
                selector.html(html);
                selector.prop('disabled', false);
                selector[0].sumo.reload();
            }
        }
    });
};

window.justinUpdate = function(id, value) {
    if (id === 'city') {
        var data = {
            city_id: value
        };
        var path = '/checkout/justin_warehouses';
        var selector = $('#checkout-step__warehouse');
        $('#checkout-step__city').removeClass('input-error');
        selector.removeClass('input-error');
    } else if (id === 'region') {
        var data = {
            region_id: value
        };
        var path = '/checkout/justin_cities';
        var selector = $('#checkout-step__city');
        selector.removeClass('input-error');
        $('#checkout-step__region').removeClass('input-error');
        $('#checkout-step__warehouse').html('<option value="">'+(location.pathname.substr(0, 3) == '/ua' ? 'Оберіть населений пункт' : 'Выберите населённый пункт')+'</option>').removeClass('input-error');
    }
    selector.find('option').text(location.pathname.substr(0, 3) == '/ua' ? 'Оновлюються дані, чекайте...' : 'Обновляются данные, ожидайте...');
    selector.attr('disabled', 'disabled');

    jQuery.ajax({
        url: (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+path,
        data: data,
        type: 'post',
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(response){
            if(response.error){

            }else if(response.success){
                var html = '<option value="0">'+(location.pathname.substr(0, 3) == '/ua' ? 'Зробіть вибір' : 'Сделайте выбор')+'</option>';
                jQuery.each(response.success, function(i, resp){
                    var info = resp.name;
                    html += '<option value="' + i + '">' + info + '</option>';
                });
                selector.html(html);
                selector.prop('disabled', false);
                selector[0].sumo.reload();
            }
        }
    })
};