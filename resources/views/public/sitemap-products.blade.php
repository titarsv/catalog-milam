@extends('public.layouts.main')
@section('meta')
    <title>{{ __('Карта товаров') }}</title>
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
     'title' => __('Карта товаров'),
     'description' => __('Карта товаров'),
     'image' => '/images/logo.png'
     ])
@endsection
@section('content')
    <main>
        <div class="container">
            <div class="sitemap products">
                <h1 class="sitemap__title">{{ __('ТОВАРЫ') }}</h1>
                <a class="sitemap__top-link" href="{{ base_url('/sitemap') }}">{{ __('Карта категорий') }}</a>
                <p class="amount"><span>{{ __('Показано') }} {{ 1 + $products->perPage() * ($products->currentPage() - 1) }}-{{ $products->currentPage() == $products->lastPage() ? $products->total() : $products->perPage() * $products->currentPage() }} {{ __('из') }} {{ $products->total() }}</span></p>
                @include('public.layouts.pagination', ['paginator' => $products])
                <ul class="sitemap">
                    @foreach($products as $product)
                        <li><a href="{{ $product->link() }}">{{ $product->name }}</a></li>
                    @endforeach
                </ul>
                <a class="sitemap__top-link" href="{{ base_url('/sitemap') }}">{{ __('Карта категорий') }}</a>
                <p class="amount"><span>{{ __('Показано') }} {{ 1 + $products->perPage() * ($products->currentPage() - 1) }}-{{ $products->currentPage() == $products->lastPage() ? $products->total() : $products->perPage() * $products->currentPage() }} {{ __('из') }} {{ $products->total() }}</span></p>
                @include('public.layouts.pagination', ['paginator' => $products])
            </div>
        </div>
    </main>
    @include('public.layouts.consult')
@endsection
