@extends('public.layouts.main', ['wrapper_class' => 'not-found__wrapper'])
@section('meta')
    <title>{{ __('Ошибка 404. Страница не найдена') }}</title>
@endsection
@section('content')
    <main class="main">
        <div class="not-found">
            <div class="breadcrumbs">
                <div class="container">
                    <ul>
                        <li><a href="{{ base_url('/') }}">{{ __('Главная') }}</a></li>
                        <li><span>{{ __('Страница не найдена') }}</span></li>
                    </ul>
                </div>
            </div>
            <span class="not-found-title">404</span>
            <p class="not-found-text">{{ __('Простите, но у нас возникли проблемы с поиском страницы, которую Вы запрашиваете.') }}</p>
            <p class="not-found-text">{{ __('Вы можете связаться с нашими менеджерами в форме внизу страницы либо:') }}</p>
            <a class="not-found-link" href="{{ base_url('/') }}">{{ __('Перейти на главную') }}</a>
            <picture>
                <source media="(max-width: 574px)" srcset="/images/pixel.webp"
                        data-original="/images/not-found-bg-mob.webp" class="lazy-web" type="image/webp">
                <source media="(max-width: 574px)" srcset="/images/pixel.jpg"
                        data-original="/images/not-found-bg-mob.jpg" class="lazy-web" type="image/jpg">
                <source srcset="/images/pixel.webp" data-original="/images/not-found-bg.webp" class="lazy-web"
                        type="image/webp">
                <source srcset="/images/pixel.jpg" data-original="/images/not-found-bg.jpg" class="lazy-web"
                        type="image/jpg">
                <img src="/images/pixel.jpg" data-original="/images/not-found-bg.jpg" class="lazy" alt="">
            </picture>
        </div>
        @include('public.layouts.consult')
    </main>
@endsection