'use strict';
// Depends
import PerfectScrollbar from 'perfect-scrollbar';

var $ = require('jquery');

// Are you ready?
$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '.js_attribute_checkbox_filter, .js_stock_checkbox_filter', function(){
        filterProducts();
    });

    $(document).on('click', '.js_submit_filters', function(){
        mobFilterProducts();
    });

    // $(document).on('change', '.js_mob_attribute_checkbox_filter, .js_mob_stock_checkbox_filter', function(){
    //     mobFilterProducts();
    // });

    $(document).on('click', '.js_attribute_link_filter', function(e){
        e.preventDefault();
        $(this).toggleClass('js_active');
        filterProducts();
    });

    $(document).on('change', '.js_price_range_filter', function(){
        var $this = $(this);
        var val = $this.val();
        setTimeout(function(){
            if($this.val() === val){
                filterProducts();
            }
        }, 500);
    });

    $(document).on('click', '#js_more_products', function(e){
        e.preventDefault();
        let page = $(this).data('id');
        if(page > 0){
            filterProducts(page, true);
        }
    });

    $(document).on('click', '.js_remove_filter', function(){
        let id = $(this).data('id');
        let type = $(this).data('type');
        if(type === 'price'){
            let price = $('.js_price_range_filter');
            $('.js_range_from').val(price.data('min')).change();
            $('.js_range_to').val(price.data('max')).change();
            price.val(price.data('min')+';'+price.data('max'));
        }else if(type === 'attribute'){
            $('.js_attribute_checkbox_filter[data-id="'+id+'"]').prop('checked', false);
            $('.js_attribute_link_filter[data-id="'+id+'"]').removeClass('js_active');
        }else if(type === 'stock'){
            $('.js_stock_checkbox_filter[data-id="'+id+'"]').prop('checked', false);
        }
        $(this).remove();
        filterProducts();
    });

    $(document).on('click', '.js_clear_filters', function(){
        $('.js_attribute_checkbox_filter').prop('checked', false);
        let price = $('.js_price_range_filter');
        $('.js_range_from').val(price.data('min'));
        $('.js_range_to').val(price.data('max')).change();
        $('.js_checked_filters').hide();
        filterProducts();
    });

    $(document).on('change', '.js_sort_select', function(){
        filterProducts();
    });

    $(document).on('click', '.js_view_link', function(){
        if( $('#js_products_wrapper').data('view') !== $(this).data('view')){
            $('#js_products_wrapper').data('view', $(this).data('view'));
            filterProducts();
        }
    });

    $(document).on('click', '.js_pagination a', function(e){
        e.preventDefault();
        let page = parseInt($(this).text());
        if(page > 0){
            filterProducts(page);
        }
    });

    $(document).on('click', '.js_show_all', function(e){
        e.preventDefault();
        $('#js_show_all').val(1);
        filterProducts(0);
    });

    function filterProducts(page = 1, more = false){
        if($('#js_show_all').val() === '1'){
            page = 0;
        }
        if(window.loading === true){
            if(typeof window.loadingTimeout !== 'undefined')
                clearInterval(window.loadingTimeout);
            window.loadingTimeout = setTimeout(filterProducts(page), 100);
            return;
        }
        window.loading = true;
        var products_wrapper = $('#js_products_wrapper');

        if(!more)
            products_wrapper.animate({'opacity': 0}, 100);

        let filters = [];
        $('.js_attribute_checkbox_filter:checked, .js_attribute_link_filter.js_active').each(function () {
            filters.push($(this).data('id'));
        });

        // let stock = [];
        // $('.js_stock_checkbox_filter:checked').each(function () {
        //     stock.push($(this).data('id'));
        // });

        let data = {
            filters: filters,
            // stock: stock,
            category: $('#js_category').val(),
            search_text: $('#js_search_text').val(),
            order: $('.js_sort_select').val(),
            sale: $('#sale').length ? $('#sale').val() : '',
            // view: products_wrapper.data('view'),
            page: page,
            // limit: $('#js_limit').val()
        };

        let price_input = $('.js_price_range_filter');
        if(price_input.length){
            let price = price_input.val().split(';');
            let price_min = Math.floor(parseFloat(price[0]));
            let price_max = Math.ceil(parseFloat(price[1]));
            if(price_min > parseFloat(price_input.data('min'))){
                data.price_min = price_min;
            }
            if(price_max < parseFloat(price_input.data('max'))){
                data.price_max = price_max;
            }
        }

        if(!more)
            $('body, html').animate({scrollTop: products_wrapper.offset().top - $('.catalog-filters__bar-wrapper').height()}, 300);
        $.ajax({
            type: 'post',
            url: ($('html').attr('lang') == 'ua' ? '' : '/'+$('html').attr('lang')) + '/products/filter',
            data: data,
            success: function(response){
                window.loading = false;
                if(response.result == 'success'){
                    // $('.js_checked_filters').replaceWith(response.checked);
                    // $('.js_main_filters').replaceWith(response.filters);
                    $('.js_total_products_count').text(response.count);
                    if(response.pagination === ''){
                        $('.pagination').html('').hide();
                    }else{
                        $('#js_more_products').replaceWith(response.pagination);
                    }
                    $('.js_filter_counter').text(response.counter);

                    if(more){
                        products_wrapper.append(response.html);
                    }else{
                        products_wrapper.html(response.html);
                    }

                    $(window).lazyLoadXT();
                    // $('.js_main_filters .scroll-wrapper').each(function() {
                    //     const ps = new PerfectScrollbar($(this)[0], {
                    //         wheelSpeed: 1,
                    //         wheelPropagation: false
                    //     });
                    //     ps.update();
                    //
                    //     $('.category-filter__title').on('click', function() {
                    //         setTimeout(function() { ps.update(); }, 10);
                    //     });
                    // });
                    products_wrapper.animate({'opacity': 1}, 100);
                    history.pushState("", document.title, response.link);
                }
            },
            error: function(){
                window.loading = false;
                products_wrapper.animate({'opacity': 1}, 100);
            },
            async: true,
            dataType: 'json'
        });
    }

    function mobFilterProducts(page = 1){
        if($('#js_show_all').val() === '1'){
            page = 0;
        }
        if(window.loading === true){
            if(typeof window.loadingTimeout !== 'undefined')
                clearInterval(window.loadingTimeout);
            window.loadingTimeout = setTimeout(mobFilterProducts(page), 100);
            return;
        }
        window.loading = true;
        var products_wrapper = $('#js_products_wrapper');
        products_wrapper.animate({'opacity': 0}, 100);
        let filters = [];
        $('.js_mob_attribute_checkbox_filter:checked, .js_mob_attribute_link_filter.js_active').each(function () {
            filters.push($(this).data('id'));
        });

        let data = {
            filters: filters,
            category: $('#js_category').val(),
            search_text: '',
            order: '',
            page: page,
        };

        //$('body, html').animate({scrollTop: products_wrapper.offset().top - 160}, 300);
        $.ajax({
            type: 'post',
            url: (location.pathname.substr(0, 3) == '/ru' ? '/ru' : '') + '/products/filter',
            data: data,
            success: function(response){
                window.loading = false;
                if(response.result == 'success'){
                    products_wrapper.html(response.html);
                    $('.js_checked_filters').replaceWith(response.checked);
                    $('.js_main_filters').replaceWith(response.filters);
                    // $('.js_mob_filters').replaceWith(response.mob_filters);
                    $(window).lazyLoadXT();
                    $('.js_main_filters .scroll-wrapper').each(function() {
                        const ps = new PerfectScrollbar($(this)[0], {
                            wheelSpeed: 1,
                            wheelPropagation: false
                        });
                        ps.update();

                        $('.category-filter__title').on('click', function() {
                            setTimeout(function() { ps.update(); }, 10);
                        });
                    });
                    products_wrapper.animate({'opacity': 1}, 100);
                    history.pushState("", document.title, response.link);
                    $("body").removeClass("filters-opened");
                }
            },
            error: function(){
                window.loading = false;
                products_wrapper.animate({'opacity': 1}, 100);
            },
            async: true,
            dataType: 'json'
        });
    }
});