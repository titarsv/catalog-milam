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
                        <div class="registration">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title">{{ trans('app.Password_recovery') }}</div>
                            @if(!empty($errors->all()))
                                <div class="error-message">
                                    <div class="error-message__text">
                                        {!! $errors->first() !!}
                                    </div>
                                </div>
                            @endif
                            <form class="registration-form" method="post">
                                {!! csrf_field() !!}
                                <input type="hidden" name="code" value="{{ $code }}">
                                <div class="input-wrapper">
                                    <label>{{ trans('app.New_password') }}</label>
                                    <input type="password" name="password" id="password" class="registration-form__input @if($errors->has('password')) input-error @endif" placeholder="">
                                </div>
                                <div class="input-wrapper">
                                    <label>{{ trans('app.Confirm_password') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="registration-form__input @if($errors->has('password_confirmation')) input-error @endif" placeholder="">
                                </div>
                                <button type="submit" class="btn">{{ trans('app.Change_password') }}</button>
                            </form>
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/reset.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/reset.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/reset.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
@endsection