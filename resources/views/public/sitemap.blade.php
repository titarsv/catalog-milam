@extends('public.layouts.main')
@section('meta')
    <title>{{ __('Карта категорий') }}</title>
    <meta name="robots" content="noindex, follow, noarchive" />
@endsection
@section('page_vars')
    @if(!isset($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false)
        <!-- Facebook Pixel Code -->
        {{--<script>--}}
            {{--!function(f,b,e,v,n,t,s)--}}
            {{--{if(f.fbq)return;n=f.fbq=function(){n.callMethod?--}}
                {{--n.callMethod.apply(n,arguments):n.queue.push(arguments)};--}}
                {{--if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';--}}
                {{--n.queue=[];t=b.createElement(e);t.async=!0;--}}
                {{--t.src=v;s=b.getElementsByTagName(e)[0];--}}
                {{--s.parentNode.insertBefore(t,s)}(window, document,'script',--}}
                {{--'https://connect.facebook.net/en_US/fbevents.js');--}}
            {{--fbq('init', '1172440259605977');--}}
            {{--fbq('track', 'PageView');--}}
        {{--</script>--}}
        <!-- End Facebook Pixel Code -->
    @endif
    @include('public.layouts.microdata.open_graph', [
     'title' => __('Карта категорий'),
     'description' => __('Карта категорий'),
     'image' => '/images/logo.png'
     ])
@endsection
@section('content')
    <main>
        <div class="container">
            <div class="sitemap">
                <h1 class="sitemap__title">{{ __('СТРАНИЦЫ') }}</h1>
                <a class="sitemap__top-link" href="{{ base_url('/sitemap-products') }}">{{ __('Карта товарного каталога') }}</a>
                <ul class="sitemap__list top">
                    <li class="sitemap__item lvl-0"><a href="{{ rtrim(base_url('/'), '/') }}">{{ __('Главная') }}</a></li>
                    @foreach($pages as $page)
                        <li class="sitemap__item lvl-0"><a href="{{ $page->link() }}">{{ $page->name }}</a></li>
                    @endforeach
                </ul>
                <h1 class="sitemap__title">{{ __('КАТЕГОРИИ') }}</h1>
                <p class="amount">{{ __('Показано') }} {{ $categories_count }}</p>
                <ul class="sitemap__list">
                    @foreach($categories as $cat)
                        <li class="sitemap__item lvl-0"><a href="{{ $cat->link() }}">{{ $cat->name }}</a></li>
                        @foreach($cat->attributes as $attr)
                            @foreach($attr->values as $val)
                                <li class="sitemap__item lvl-1"><a href="{{ $cat->link() }}/{{ $attr->slug }}-{{ $val->value }}">{{ $cat->name }} {{ $val->name }}</a></li>
                            @endforeach
                        @endforeach
                    @endforeach
                </ul>
                <ul class="sitemap__list">
                    @foreach($categories[0]->children as $cat)
                        <li class="sitemap__item lvl-1"><a href="{{ $cat->link() }}">{{ $cat->name }}</a></li>
                        @foreach($cat->attributes as $attr)
                            @foreach($attr->values as $val)
                                <li class="sitemap__item lvl-2"><a href="{{ $cat->link() }}/{{ $attr->slug }}-{{ $val->value }}">{{ $cat->name }} {{ $val->name }}</a></li>
                            @endforeach
                        @endforeach
                        @if(!empty($cat->children))
                            @foreach($cat->children as $subcat)
                                <li class="sitemap__item lvl-2"><a href="{{ $subcat->link() }}">{{ $subcat->name }}</a></li>
                                @foreach($subcat->attributes as $attr)
                                    @foreach($attr->values as $val)
                                        <li class="sitemap__item lvl-3"><a href="{{ $subcat->link() }}/{{ $attr->slug }}-{{ $val->value }}">{{ $subcat->name }} {{ $val->name }}</a></li>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endif
                    @endforeach
                </ul>
                <a class="sitemap__top-link" href="{{ base_url('/sitemap-products') }}">{{ __('Карта товарного каталога') }}</a>
                <p class="amount">{{ __('Показано') }} {{ $categories_count }}</p>
            </div>
        </div>
    </main>
    @include('public.layouts.consult')
@endsection
