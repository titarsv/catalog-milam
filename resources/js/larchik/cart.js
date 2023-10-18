import PerfectScrollbar from 'perfect-scrollbar';

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Выбор вариации
    $(document).on('click', '.js_variation_switcher', function(){
        if($(this).hasClass('js_active')){
            return false;
        }
        var $this = $(this);
        var id = $(this).data('id');
        var hash;
        var attrs = [id];
        var product_card = $this.parents('.js_product_card');

        $('.js_variation_switcher.js_active').removeClass('js_active').removeClass('checked');
        $('.js_variation_switcher[data-id="'+id+'"]').addClass('js_active').addClass('checked');
        $('.js_variation_content:not(.hidden)').addClass('hidden');
        $('.js_variation_content[data-id="'+id+'"]').removeClass('hidden');

        // product_card.find('.js_variation.js_active').each(function(){
        //     var val = $(this).data('id');
        //     if(val != ''){
        //         attrs.push(val);
        //     }
        // });
        hash = attrs.sort(function(a,b){
            return a - b
        }).join('_');

        $('[name="variation"]').prop('checked', false);
        var input = $('.js_var_'+hash);
        if(hash != '' && input.length){
            input.prop('checked', true);
            location.hash = hash;
            var price = input.data('price');
            $('.js_current_price').text(price);
        }else{
            if(window.location.hash !== '')
                history.pushState("", document.title, window.location.pathname + window.location.search);
            $('.js_current_price').text($('.js_current_price').data('price'));
        }
    });

    var hash_parts = location.hash.replace('#', '').split('_');
    if(hash_parts.length && hash_parts[0] !== ''){
        for(var i=0; i<hash_parts.length; i++){
            var option = $('.js_variation_switcher[data-id="'+hash_parts[i]+'"]').eq(0);
            option.trigger('click');
        }
    }else if($('.js_variation_switcher').length){
        // $('.js_variation_switcher').eq(0).click();
    }

    // Добавление товаров в корзину
    $(document).on('click', '.js_add_to_cart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var variation;
        var $this = $(this);
        var product_card = $this.parents('.js_product_card');
        var qty = product_card.find('.js_qty').val();
        var data = {
            action: 'add',
            product_id: $this.data('id'),
            quantity: qty > 1 ? qty : 1
        };

        variation = product_card.find('[name="variation"]:checked');

        if(variation.length){
            data['variation'] = variation.val();
        }

        $.post(($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang'))+"/cart/update", data, function(cart){
            $('.js_cart_counter').each(function(){
                if($(this).find('.header-cart-count').length){
                    $(this).find('.header-cart-count').text(cart.count);
                }else{
                    $(this).append('<span class="header-cart-count">'+cart.count+'</span>');
                }
            });

            $('.js_cart_price').text(cart.total + ' грн');

            $('.js_minicart_wrapper').html(cart.html);

            $('.header-cart__popup-main').each(function() {
                const ps1 = new PerfectScrollbar($(this)[0], {
                    wheelSpeed: 1,
                    wheelPropagation: false,
                    useBothWheelAxes: false,
                    suppressScrollX: true
                });
                ps1.update();
                $(window).resize(function() {
                    ps1.update();
                });
            });

            $('.header-cart').addClass('animated').addClass('shake');
            setTimeout(function(){
                $('.header-cart').removeClass('shake');
            }, 1000);

            if(typeof fbq !== 'undefined' && typeof fbqProductsData[data.product_id] !== 'undefined'){
                fbq('track', 'AddToCart', fbqProductsData[data.product_id]);
            }
        });
    });

    /**
     * Загрузка корзины
     */
    // $('.header-cart').on('click', function(){
    //     $.post((location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/cart/get', {}, function(cart){
    //         $('.cart-modal').remove();
    //         $('body').append(cart);
    //         $('.cart-modal').show();
    //         $('body').addClass('active');
    //         //$('.scroll-wrapper').getNiceScroll().resize();
    //         //$('.nicescroll-rails').css('z-index', '11');
    //         const ps1 = new PerfectScrollbar('#cart-popup .scroll-wrapper',{
    //             wheelSpeed: 1,
    //             wheelPropagation: false
    //         });
    //         setTimeout(function() { ps1.update(); }, 10);
    //     });
    // });

    /**
     * Удаление товара из корзины
     */
    $(document).on('click', '.js_remove_product_from_cart', function(e){
        e.preventDefault();
        var $this = $(this);
        var id = $this.data('id');
        $('#js_delete_product .js_confirm_delete').data('id', id);
        $.magnificPopup.open({
            items: {
                src: '#js_delete_product'
            },
            type: 'inline',
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });

    $(document).on('click', '.js_close_popup', function(e){
        e.preventDefault();
        $.magnificPopup.close();
    });

    $(document).on('click', '#js_delete_product .js_confirm_delete', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $this = $(this);
        var id = $this.data('id');
        update_cart({
            action: 'remove',
            product_id: id
        });
        $.magnificPopup.close();
    });

    /**
     * Обновление колличества товара в корзине
     */
    $(document).on('input change', '.js_cart_qty', function(){
        var $this = $(this);
        update_cart({
            action: 'update',
            product_id: $this.data('prod-id'),
            quantity: $this.val()
        });
        const ps1 = new PerfectScrollbar('#cart-popup .scroll-wrapper',{
            wheelSpeed: 1,
            wheelPropagation: false
        });
        ps1.update();
    });

    /**
     * Кнопка уменьшения колличества товара в корзине
     */
    $(document).on('click', '.cart-modal__item .product-counter .minus, .cart-item__counter .product-counter .minus', function () {
        const ps1 = new PerfectScrollbar('#cart-popup .scroll-wrapper',{
            wheelSpeed: 1,
            wheelPropagation: false
        });
        setTimeout(function() { ps1.update(); }, 100);
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.next().text(count);
        $input.change();
        return false;
    });

    /**
     * Кнопка увеличения колличества товара в корзине
     */
    $(document).on('click', '.cart-modal__item .product-counter .plus, .cart-item__counter .product-counter .plus', function () {
        const ps1 = new PerfectScrollbar('#cart-popup .scroll-wrapper',{
            wheelSpeed: 1,
            wheelPropagation: false
        });
        setTimeout(function() { ps1.update(); }, 100);
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) + 1;
        $input.val(count);
        $input.next().text(count);
        $input.change();
        return false;
    });

    /**
     * Повторный заказ
     */
    // $('.order-again').click(function(e){
    //     e.preventDefault();
    //     e.stopPropagation();
    //     var $this = $(this);
    //     var ids = $this.data('id');
    //     for(var i in ids){
    //         var key = ''+ids[i];
    //         var id = key.split('_');
    //         var data = {
    //             action: 'add',
    //             product_id: id[0],
    //             quantity: 1
    //         };
    //
    //         if(id.length){
    //             data['variation'] = id[1];
    //         }
    //
    //         $.post((location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+"/cart/update", data, function(cart){
    //             if($('.header-cart i').length){
    //                 $('.header-cart i').text(cart.count);
    //             }else{
    //                 $('.header-cart').append('<i>'+cart.count+'</i>');
    //             }
    //         });
    //     }
    // });
});

/**
 * Обновление корзины
 * @param data
 */
function update_cart(data){
    $.post((location.pathname.substr(0, 3) == '/ru' ? '/ru' : '')+"/cart/update", data, function(cart){
        $('.js_cart_counter').each(function(){
            if($(this).find('.header-cart-count').length){
                $(this).find('.header-cart-count').text(cart.count);
            }else{
                $(this).append('<span class="header-cart-count">'+cart.count+'</span>');
            }
        });

        $('.js_cart_price').text(cart.total + ' грн');

        $('.js_minicart_wrapper').html(cart.html);

        $('.header-cart__popup-main').each(function(){
            const ps1 = new PerfectScrollbar($(this)[0], {
                wheelSpeed: 1,
                wheelPropagation: false,
                useBothWheelAxes: false,
                suppressScrollX: true
            });
            ps1.update();
            $(window).resize(function() {
                ps1.update();
            });
        });
    });
}