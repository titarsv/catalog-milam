@extends('public.layouts.main')

@section('meta')
    <title>{{ trans('app.Password_recovery') }}</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

@section('content')
    <main>
        <div class="section-login">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('forgotten') !!}
            </div>
            <div class="container">
                <div class="login-wrapper">
                    <div class="login-inner">
                        <div class="login">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title">{{ trans('app.specialties') }}</div>
                            @if(!empty($errors->all()))
                                <div class="error-message">
                                    <div class="error-message__text">
                                        {!! $errors->first() !!}
                                    </div>
                                </div>
                            @endif
                            <form class="login-form sign-up-form sign-in-form" method="post">
                                {!! csrf_field() !!}
                                <label>E-mail ({{ trans('app.Login') }})</label>
                                <input type="text" value="{!! old('email') !!}" name="email" id="email" class="@if($errors->has('email')) input-error @endif" placeholder="you@mail.com">
                                <button type="submit" class="btn">{{ trans('app.REMIND') }}</button>
                            </form>
                            <a class="login-link" href="{{env('APP_URL')}}{{ App::getLocale() == 'ua' ? '/ua' : '' }}/registration">{{ trans('app.register') }}</a>
                            <div class="login-socials">
                                <span>{{ trans('app.login_from') }}</span>
                                <ul>
                                    <li>
                                        <a class="icon-facebook" href="{{env('APP_URL')}}/login/facebook"></a>
                                    </li>
                                    <li>
                                        <a class="icon-google" href="{{env('APP_URL')}}/login/google"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/forgotten.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/forgotten.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/forgotten.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
@endsection