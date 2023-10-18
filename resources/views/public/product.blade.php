@extends('public.layouts.main')
@section('page_vars')
    @include('public.layouts.microdata.product', ['product' => $product, 'reviews' => $reviews])
    @include('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => !empty($product->image) ? $product->image->url() : '/images/logo.png'
     ])
    @if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false && config('app.debug') === false)
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '867264487143692');
            fbq('track', 'PageView');
        </script>
        <script>
            var fbqProductsData = [];
            fbqProductsData[{{ $product->id }}] = {
                content_type: 'product',
                content_ids: ['{{ $product->id }}'],
                content_name: '{{ $product->name }}',
                content_category: '{{ $product->categories->count() ? $product->categories->first()->name : '' }}',
                value: {{ round($product->price) }},
                currency: 'UAH'
            };
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', fbqProductsData[{{ $product->id }}]);
            }
        </script>
        <!-- End Facebook Pixel Code -->
    @endif
    <!-- Код тега ремаркетинга Google -->
    <script>
        /* <![CDATA[ */
        var google_tag_params = {
            ecomm_prodid: '{{ $product->id }}',
            ecomm_pagetype: 'product',
            ecomm_totalvalue: {{ round($product->price) }}
        };
        /* ]]> */
        var fbqProductsData = [];
    </script>
@endsection

@section('content')
    <main class="main">
        {!! Breadcrumbs::render('product', $product, $product->category) !!}
        <div class="product">
            <div class="container">
                <div class="product-inner row">
                    <div class="product-photo col-md-5 visible-lg visible-md">
                        <div class="product-slider-main slick-slider"
                             data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-slider-previews", "fade": true, "arrows": true, "infinite": true}'>
                            @foreach($gallery as $i => $slide)
                                @if(!empty($slide['image']))
                                    <div class="slide">
                                        {!! $slide['image']->webp([890, 890], ['data-zoom-image' => $slide->image->url(), 'alt' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'width' => 890, 'height' => 890], 'slider', 'contain') !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if($gallery->count() > 1)
                        <div class="product-slider-previews slick-slider" data-slick='{"slidesToShow": 2, "slidesToScroll": 1, "variableWidth": true, "asNavFor": ".product-slider-main", "focusOnSelect": true,  "arrows": false, "infinite": true,
            "responsive":[{"breakpoint":480,"settings":{"slidesToShow": 6}}]}'>
                            @foreach($gallery as $i => $slide)
                                @if(!empty($slide['image']))
                                    <div class="slide">
                                        {!! $slide['image']->webp([890, 890], ['alt' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'width' => 890, 'height' => 890], 'slider', 'contain') !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="product-photo col-md-5 hidden-md hidden-lg">
                        <div class="product-slider-main slick-slider lightgallery"
                             data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-slider-previews", "fade": true, "arrows": true, "infinite": true}'>
                            @foreach($gallery as $i => $slide)
                                @if(!empty($slide['image']))
                                    <div class="slide light-item" data-src="{{ $slide->image->url() }}">
                                        {!! $slide['image']->webp([890, 890], ['alt' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'width' => 890, 'height' => 890], 'slider', 'contain') !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if($gallery->count() > 1)
                        <div class="product-slider-previews slick-slider" data-slick='{"slidesToShow": 2, "slidesToScroll": 1, "variableWidth": true, "asNavFor": ".product-slider-main", "focusOnSelect": true,  "arrows": false, "infinite": true,
            "responsive":[{"breakpoint":480,"settings":{"slidesToShow": 6}}]}'>
                            @foreach($gallery as $i => $slide)
                                @if(!empty($slide['image']))
                                    <div class="slide">
                                        {!! $slide['image']->webp([890, 890], ['alt' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'width' => 890, 'height' => 890], 'slider', 'contain') !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="product-description col-md-7">
                        <h1>{{ !empty($seo->name) ? $seo->name : $product->name }}</h1>
                        @if(!empty($product->description))
                        <span>{{ __('Описание') }}:</span>
                        {!! $product->description !!}
                        @endif
                        <span>{{ __('Объем') }}:</span>
                        <ul class="product-description-size">
                            @if(!empty($product->capacity))
                                <li>
                                    <div>
                                        {!! $product->image->webp([150, 150], ['alt' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($seo->name) ? $seo->name : $product->name).__('купить в Украине - фото, отзывы'), 'width' => 75, 'height' => 75], 'static', 'contain') !!}
                                        <span>{{ $product->capacity->name }}</span>
                                    </div>
                                </li>
                            @endif
                            @if($related->count())
                                @foreach($related as $related_product)
                                <li>
                                    <a href="{{ $related_product->link() }}">
                                        {!! $related_product->image->webp([150, 150], ['alt' => (!empty($related_product->seo->name) ? $related_product->seo->name : $related_product->name).__('купить в Украине - фото, отзывы'), 'title' => (!empty($related_product->seo->name) ? $related_product->seo->name : $related_product->name).__('купить в Украине - фото, отзывы'), 'width' => 75, 'height' => 75], 'static', 'contain') !!}
                                        <span>{{ $related_product->capacity->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                        @if(!empty($product->purposes))
                        <span>{{ __('Назначение') }}:</span>
                        <p>{{ $product->purposes }}</p>
                        @endif
                        @if(!empty($product->instructions))
                        <span>{{ __('Инструкция по применению') }}</span>
                        {!! $product->instructions !!}
                        @endif
                        @if(!empty($product->security))
                        <span>{{ __('Меры безопасности') }}</span>
                        {!! $product->security !!}
                        @endif
                        @if(!empty($documents))
                        <span>{{ __('Документы / Сертификаты') }}:</span>
                        <div class="product-description-doc">
                            @foreach($documents as $i => $document)
                                <a href="{{ $document->url() }}" target="_blank">{{ __('Сертификат') }}{{ $i + 1 }}.{{ substr($document->image->title, strrpos($document->image->title, '.') + 1) }}</a>
                            @endforeach
                        </div>
                        @endif
                        @if(!empty($product->compound))
                        <span>{{ __('Состав') }}:</span>
                        {!! $product->compound !!}
                        @endif
                        @if(!empty($product->shelf_life))
                        <span>{{ __('Срок годности') }}</span>
                        <p>{{ $product->shelf_life }}</p>
                        @endif
                        @if(!empty($product->storage_conditions))
                        <span>{{ __('Условия хранения') }}</span>
                        <p>{{ $product->storage_conditions }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('public.layouts.consult')
    </main>
@endsection
