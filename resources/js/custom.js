'use strict';
// Depends
import PerfectScrollbar from "perfect-scrollbar";

var $ = require('jquery');
var swal = require('sweetalert2');
const Cookie = require('js-cookie');

// Are you ready?
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.header-lang a, .mobile-menu__lang a').click(function(e){
        let $this = $(this);
        e.preventDefault();
        Cookie.set('lang', $(this).data('lang'));
        setTimeout(function(){location = $this.attr('href');}, 10);
    });

    // if(Cookie.get('lang_selected') !== '1' && $('html').attr('lang') == 'ru'){
    //     var lang = window.navigator.language || navigator.userLanguage;
    //     if(lang === 'en' || lang === 'uk'){
    //         Cookie.set('lang_selected', 1);
    //         location = '/'+(lang === 'uk' ? 'ua' : lang)+location.pathname;
    //     }else{
    //         Cookie.set('lang_selected', 1);
    //     }
    // }

    $('.js_contact_form').on('sent', function(){
        $('.js_contact_form_text').addClass('hidden');
        $('.js_contact_form_success').removeClass('hidden');
    });

    $('.ja_lang_select').change(function(){
        location = $(this).val();
    });

    $(document).on('click', '.js_add_to_wish', function(){
        var $this = $(this);
        var data = {};
        data['product_id'] = $this.data('id');
        if($this.hasClass('active')) {
            data['action'] = 'remove';
        }else{
            data['action'] = 'add';
        }
        $.ajax({
            url: '/wishlist/update', type: 'POST', data: data, dataType: 'JSON',
            success: function (response) {
                if(response.count !== false) {
                    // if($this.hasClass('is_wish')){
                    //     $this.parents('.col').eq(0).remove();
                    // }else{
                        $this.toggleClass('active');
                    // }

                    if(response.count > 0){
                        $('.js_header_fav').removeClass('empty');
                    }else if(!$('.js_header_fav').hasClass('empty')){
                        $('.js_header_fav').addClass('empty');
                    }
                }
            }
        });
    });

    /*
     * Добавление отзывов комментариев
     */
    $('.js_product_review_form').on('submit', function(e){
        e.preventDefault();
        let form = $(this);

        let fd = new FormData();
        form.find('[type="file"]').each(function(){
            if($(this).get(0).files.length) {
                for(let i in  $(this).get(0).files){
                    fd.append($(this).attr('name')+'['+i+']', $(this).get(0).files[i]);
                }
            }
        });

        form.find('input[type="text"], input[type="hidden"], input[type="checkbox"]:checked, input[type="radio"]:checked').each(function(){
            fd.append($(this).attr('name'), $(this).val());
        });

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            processData: false,
            contentType: false,
            data: fd,
            dataType: 'json',
            success: function (response){
                if(response.result === 'error'){
                    var html = '';
                    $.each(response.errors, function(i, value){
                        html += value + '<br>';
                    });
                    $('.product-reviews__form-title').text(html);
                }else if(response.result === 'success'){
                    $('.product-reviews__form-title').html(response.msg);
                    form[0].reset();
                }
            }
        });
    });

    $('form.js_site_review_form').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        let data = form.formData({
            validator: {},
            invalid: function(data) {
                for (let name in data.errors) {
                    data.obj[name].obj.validateTooltip({
                        text: data.obj[name].obj.rules[data.errors[name][0]]
                    });
                }
            }
        });

        if(data === false)
            return false;

        $.ajax({
            url: (location.pathname.substr(0, 3) == '/ru' ? '/ru' : '')+'/shopreview/add',
            data: $(this).serialize()+'&url='+location.href,
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                //$this.find('.error-message').fadeOut(300);
                form.find('button[type="submit"]').html(location.pathname.substr(0, 3) == '/ua' ? 'Відправляємо...' : 'Отправляем...');
            },
            success: function (response) {
                /*if(response.error){
                    var html = '';
                    $.each(response.error, function(i, value){
                        html += value + '<br>';
                    });

                    swal(location.pathname.substr(0, 3) == '/ua' ? 'Помилка' : 'Ошибка', html, 'error');
                } else if(response.success) {

                    swal(location.pathname.substr(0, 3) == '/ua' ? 'Ваш відгук додано успішно!' : 'Ваш отзыв успешно добавлен!', location.pathname.substr(0, 3) == '/ua' ? 'Він з\'явиться на сайті після модерації.' : 'Он появится на сайте после модерации.', 'success');

                    $.magnificPopup.close();
                }*/
                form.trigger('sent', response);
                if(response.success && (typeof form.data('success-title') !== 'undefined' || typeof  form.data('success-message') !== 'undefined')){
                    swal({
                        showCancelButton: true,
                        timer: 3000
                    });
                    swal(form.data('success-title'), form.data('success-message'), 'success').then(() => {
                        location.reload(false);
                    });
                    /*form.hide();
                    setTimeout(function() {
                        $('body, html').animate({scrollTop: $('.reviews-section').offset().top}, 300);
                    }, 100);*/

                }
                $('#review').modal('show');
                form.find('input, textarea').val('');
                form.find('button[type="submit"]').html(location.pathname.substr(0, 3) == '/ua' ? 'Відправити' : 'Отправить')
            }
        });
    });

    $(document).on('click', '.cart-popup__back', function(){
        $.magnificPopup.close();
    });

    $(document).on('click', '.category-view__item', function(){
        $('.category-view__item').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', '.js_product_popup', function(e){
        e.preventDefault();
        $.ajax({
            url: (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/product_popup',
            data: {
                'id': $(this).data('id'),
                'type': $(this).data('type')
            },
            method: 'POST',
            success: function(resp) {
                $(resp).modal('show');
                //
                // $.magnificPopup.open({
                //     items: {
                //         src: resp,
                //         type: 'inline'
                //     },
                //     callbacks: {
                //         close: function(){
                //             $('.header').css({'right': '0', 'z-index': '1080'});
                //             setTimeout(function() { $('.header').css({'transition': '.3s linear'}); }, 10);
                //         },
                //         open: function(){
                //             $('.header').css({'right': '17px', 'transition': 'none'});
                //             setTimeout(function() { $('.header').css({'z-index': '10'}); }, 10);
                //
                //             $('form.consult-popup__form input[name="phone"]').each(function() {
                //                 let $this = $(this);
                //                 if (typeof $this.data('validate-phone') !== 'undefined')
                //                     $this.mask('+99 (999) 999-9999');
                //
                //                 if (typeof $this.data('validate-uaphone') !== 'undefined')
                //                     $this.mask('+38 (999) 999-99-99');
                //
                //                 if (typeof $this.data('validate-ruphone') !== 'undefined')
                //                     $this.mask('+7 (999) 999-99-99');
                //             });
                //         }
                //     }
                // }, 0);
            }
        });
    });

    $('.config-popup__form').on('sent', function(){
        $.magnificPopup.close();
    });

    $(document).on('sent', '.consult-popup__form', function(){
        $.magnificPopup.close();
    });

    // search
    var search_output = $('[data-output="search-results"]');
    window.livesearch_updatind = false;
    $('[data-autocomplete="input-search"]').on('keyup focus', function(){
        if($(this).val().length > 1){
            livesearch();
        }else{
            search_output.hide();
        }
    });

    function livesearch(){
        var text = $('[data-autocomplete="input-search"]').val();
        if(window.livesearch_updatind){
           setTimeout(function(){
               livesearch(text);
           }, 100);
        }else if(text !== window.livesearch_text){
            window.livesearch_updatind = true;
            window.livesearch_text = text;
            var data = {};
            data.search = text;
            $.ajax({
                url: (location.pathname.substr(0, 3) == '/ua' ? '/ua' : '')+'/livesearch',
                data: data,
                method: 'GET',
                dataType: 'JSON',
                success: function(resp) {
                    $('.header-search-options').hide();
                    var html = '';

                    $.each(resp, function(i, value){
                        html += '<li>';
                        html += '<a href="'+value.url+'" class="search-product">';
                        html += '<div class="search-product__pic">';
                        html += '<picture><img src="'+value.image+'" alt="'+value.name+'"></picture>';
                        html += '</div>';
                        html += '<div class="search-product__about">';
                        html += '<span class="search-product__about-name">';
                        html += value.name;
                        html += '</span>';
                        if(value.price) {
                            html += '<span class="search-product__about-price">';
                            html += value.price;
                            html += '</span>';
                        }
                        html += '</div>';
                        html += '</a>';
                        html += '</li>';
                    });

                    if(html === ''){
                        search_output.hide();
                    }else{
                        search_output.find('ul').html(html);
                        search_output.show();
                    }

                    window.livesearch_updatind = false;
                }
            });
        }
    }

    $(document).on('click', '.search-results__all a', function(){
        $(this).parents('form').submit();
    });

    // $(document).on('click', '.catalog-filter__items a', function(e){
    //     e.preventDefault();
    //     let parent = $(this).parent();
    //     let input = parent.find('input');
    //     parent.toggleClass('current');
    //     input.click();
    // });

    $('.product_popup_form, .consult-form').on('sent', function(){
        $('#order-one-click, #order-available').modal('hide');
        $('#order-one-confirm').modal('show');
    });

    $('.product_popup_form').on('sent', function(){
        if(typeof ga !== 'undefined')
            ga(ga.getAll()[0].get('name') + '.send', 'event', { eventCategory: 'send', eventAction: 'buyoneclick', eventValue: 1 });
    });

    $('.consult-form').on('sent', function(){
        if(typeof ga !== 'undefined')
            ga(ga.getAll()[0].get('name') + '.send', 'event', { eventCategory: 'send', eventAction: 'consalt', eventValue: 1 });
    });

    $('.contacts-form').on('sent', function(){
        if(typeof ga !== 'undefined')
            ga(ga.getAll()[0].get('name') + '.send', 'event', { eventCategory: 'send', eventAction: 'contactus', eventValue: 1 });
    });

    $('.js_region_select').change(function(){
        let region_id = $(this).val();
        Cookie.set('region', region_id);
        $('.js_region_select').each(function(){
            if($(this).val() != region_id){
                $(this)[0].sumo.selectItem(region_id);
                if($('.ua-map').length){
                    $('.ua-map > g').eq(region_id).trigger('mousedown');
                }
            }
        });
    });

    $('.ua-map > g').mousedown(function(){
        let region_id = $(this).index();
        if(region_id !== 24){
            Cookie.set('region', region_id);
            $('.js_region_select').each(function(){
                if($(this).val() != region_id){
                    $(this)[0].sumo.selectItem(region_id);
                }
            });
        }
    });

    $('#question-popup .ajax_form').on('sent', function(){
        $.magnificPopup.close();
    });
});
require('./larchik/filter');
// require('./larchik/cart');
// require('./larchik/checkout');
// require('./larchik/currency');