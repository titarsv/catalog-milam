'use strict';

performance.mark("vendors initialization");

// Depends
let $ = require('jquery');
require('./bootstrap');

// Modules
let Lazyload = require('./components/lazyload');
let Forms = require('./components/forms');
let Slider = require('./components/slider');
let Popup = require('./components/popup');
let LightGallery = require('./components/lightgallery');
require('../../node_modules/sumoselect/jquery.sumoselect');
require('../../node_modules/ez-plus/src/jquery.ez-plus');
require('../../node_modules/sweetalert2/dist/sweetalert2');

// Are you ready?
$(function() {
    new Forms();
    new Popup();
    new LightGallery();
    new Slider();

    setTimeout(function() {
        $('body').trigger('scroll');
        $(window).trigger('resize');
    }, 300);

    // fixed header

    var header = $('.header'),
        scrollPrev = 0;

    $(window).scroll(function() {
        var scrolled = $(window).scrollTop();

        if (scrolled > 200 && scrolled > scrollPrev) {
            header.addClass('fixed');
        } else {
            header.removeClass('fixed');
        }
        scrollPrev = scrolled;
    });

    // select

    $('.select').SumoSelect({
        forceCustomRendering: true
    });

    // mobile menu

    var touch = $('.mobile-menu__btn');

    var toggles = document.querySelectorAll('.mobile-menu__btn');

    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    }

    function toggleHandler(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            (this.classList.contains('active') === true) ? this.classList.remove('active') : this.classList.add('active');
        });
    }

    $(touch).click(function(e) {
        e.preventDefault();
        $('body').toggleClass('menu-opened');
        return false;
    });

    $(document).on('click', '.mobile-menu__btn', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.mobile-menu__wrapper', function(e) {
        e.stopPropagation();
    });

    $(window).resize(function() {
        if ($(window).width() > 991) {
            $('.mobile-menu__btn').removeClass('active');
            $('body').removeClass('menu-opened');
        }
    });

    $('.mobile-menu .has-children > span').on('click', function() {
        $(this).toggleClass('opened').closest('li').find('.submenu').slideToggle();
    });

    $('.header-menu .has-children').on('mouseover', function() {
        $(this).addClass('hover').removeClass('hover-out').siblings().addClass('hover-out').removeClass('hover');
    });

    $('.has-children span.lvl').on('click', function() {
        $(this).toggleClass('opened-lvl').next().slideToggle();
    });

    // filters

    $('.btn-filters').on('click', function() {
        var btn_txt = $(this).find('span');
        if (window.location.href.indexOf("/ru") > -1){
            btn_txt.html() == 'Скрыть фильтры' ? btn_txt.html('Отобразить фильтры') : btn_txt.html('Скрыть фильтры');
        }
        else if (window.location.href.indexOf("/en") > -1){
            btn_txt.html() == 'Hide filters' ? btn_txt.html('Show filters') : btn_txt.html('Hide filters');
        }
        else{
            btn_txt.html() == 'Приховати фільтри' ? btn_txt.html('Відобразити фільтри') : btn_txt.html('Приховати фільтри');
        }
        $('.categories-main').toggleClass('opened-filters');
    });

    $('.filter-wrapper .has-children').on('click', function() {
        $(this).closest('.filter-wrapper').toggleClass('open').find('.subcategory').slideToggle();
    });



    $(window).resize(function() {
        var btn_txt = $('.btn-filters').find('span');
        if ($(window).width() < 991) {
            btn_txt.html('Отобразить фильтры');
            $('.categories-main').removeClass('opened-filters');
        }
    });

    $('.btn-filters__mob').on('click', function() {
        $('body').toggleClass('filters-opened');
    });

    $('.mobile-filters__close').click(function() {
        $('body').removeClass('filters-opened');
    });

    $(document).click(function() {
        $('body').removeClass('filters-opened');
    });

    $(document).on('click', '.mobile-filters__wrapper', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.btn-filters__mob', function(e) {
        e.stopPropagation();
    });

    $('.categories-filter__head').on('click', function() {
        $(this).toggleClass('active').next().slideToggle();
    });

    $(window).resize(function() {
        if ($(window).width() > 991) {
            $('body').removeClass('filters-opened');
        }
    });

    // tabs

    $('.tabs').on('click', 'li:not(.active)', function() {
        $(this)
            .addClass('active').siblings().removeClass('active')
            .closest('.tabs-wrapper').find('.tabs-content').removeClass('active').eq($(this).index()).addClass('active');
    });

    // map

    $('.city').mousedown(function(e) {
        e.stopPropagation();
        var map = $('.map-wrapper'),
            dot = $(this).find('circle'),
            left = dot.offset().left - map.offset().left,
            top = dot.offset().top - map.offset().top;
        $(this).addClass('active').siblings().removeClass('active')
            .closest('.map-wrapper').find('.title').removeClass('active').eq($(this).index()).addClass('active')
            .closest('.distributors-wrapper').find('.distributors-info').removeClass('active').eq($(this).index()).addClass('active');
        $('.map-wrapper .title.active').each(function() {
            var title_pos = $(this).width() + 28;
            $(this).css({ 'left': left + 3 - title_pos / 2, 'top': top - 25 });
        });
    });

    $('.map-cities__list').on('click', 'li', function(e) {
        var city_name = $(this).html();
        $(this).addClass('active').siblings().removeClass('active');
        $(this).closest('.map-cities__wrapper').find('.map-cities__title').html(city_name);
    });

    $('.distributors-info__head').on('click', function() {
        $(this).toggleClass('opened').next('.distributors-info__body').slideToggle();
    });

    $(window).resize(function() {
        if ($(window).width() > 574) {
            $('.distributors-info__head').removeClass('opened');
            $('.distributors-info__body').removeAttr('style');
        }
    });

    // lazy load

    var lazyload = function() {
        var scroll = $(window).scrollTop() + $(window).height() * 3;

        $('.lazy').each(function() {
            var $this = $(this);
            if ($this.offset().top < scroll) {
                $this.attr('src', $(this).data('original'));
            }
        });
        $('.lazy-web').each(function() {
            var $this = $(this);
            if ($this.offset().top < scroll) {
                $this.attr('srcset', $(this).data('original'));
            }
        });
    };
    $(window).scroll(lazyload);

    // zoom product

    $(window).resize(function() {
        if ($(window).width() > 991) {
            $('.product-slider-main .slick-current img').ezPlus({
                borderSize: 0,
                easing: false,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
                zoomWindowHeight: 500,
                zoomWindowWidth: 680
            });

            $('.product-slider-main').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                var img = $(slick.$slides[nextSlide]).find("img");
                $('.zoomWindowContainer,.zoomContainer').remove();
                $(img).ezPlus({
                    borderSize: 0,
                    easing: false,
                    zoomWindowFadeIn: 300,
                    zoomWindowFadeOut: 300,
                    lensFadeIn: 300,
                    lensFadeOut: 300,
                    zoomWindowHeight: 500,
                    zoomWindowWidth: 680
                });
            });
        }
        /*else {
            $('.product-slider-main .slick-current img').ezPlus({
                zoomType: 'inner',
                cursor: 'crosshair',
                borderSize: 0,
                zoomWindowHeight: 600,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
            });

            $('.product-slider-main').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                var img = $(slick.$slides[nextSlide]).find("img");
                $('.zoomWindowContainer,.zoomContainer').remove();
                $(img).ezPlus({
                    zoomType: 'inner',
                    cursor: 'crosshair',
                    borderSize: 0,
                    zoomWindowHeight: 600,
                    zoomWindowFadeIn: 300,
                    zoomWindowFadeOut: 300,
                    lensFadeIn: 300,
                    lensFadeOut: 300,
                });
            });
        }*/
    });

    if ($(window).width() > 991) {
        $('.product-slider-main').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var img = $(slick.$slides[nextSlide]).find("img");
            $('.zoomWindowContainer,.zoomContainer').remove();
            $(img).ezPlus({
                borderSize: 0,
                easing: false,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
                zoomWindowHeight: 500,
                zoomWindowWidth: 680
            });
        });
    }
    /*else {
        $('.product-slider-main').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var img = $(slick.$slides[nextSlide]).find("img");
            $('.zoomWindowContainer,.zoomContainer').remove();
            $(img).ezPlus({
                zoomType: 'inner',
                cursor: 'crosshair',
                borderSize: 0,
                zoomWindowHeight: 600,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
            });
        });
    }*/
});

performance.mark("larchik initialization");
require('./custom.js');
performance.measure("larchik initialization");
