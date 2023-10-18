@extends('public.layouts.main')
@section('meta')
    <title>{{ trans('app.registration') }}</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
    <meta name="robots" content="noindex, nofollow" />
@endsection
@section('page_vars')
    @include('public.layouts.microdata.open_graph', [
     'title' => trans('app.registration'),
     'description' => $settings->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main>
        <div class="section-login">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('registration') !!}
            </div>
            <div class="container">
                <div class="login-wrapper">
                    <div class="login-inner">
                        <div class="registration">
                            {{--<picture>
                                <source srcset="/images/pixel.webp" data-original="/images/acc-logo.webp" class="lazy-web" type="image/webp">
                                <source srcset="/images/pixel.png" data-original="/images/acc-logo.png" class="lazy-web" type="image/png">
                                <img src="/images/pixel.png" data-original="/images/acc-logo.png"  class="lazy" alt="">
                            </picture>--}}
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title">{{ trans('app.registration') }}</div>
                            <form class="registration-form" method="post">
                                {!! csrf_field() !!}
                                <div class="input-wrapper">
                                    <label>E-mail ({{ trans('app.Login') }})</label>
                                    <input type="text" name="email" placeholder="">
                                </div>
                                @if($errors->has('email'))
                                    <p class="warning" role="alert">{{ $errors->first('email',':message') }}</p>
                                @endif
                                <div class="input-wrapper">
                                    <label>{{ trans('app.Password') }}</label>
                                    <input type="password" name="password" placeholder="">
                                </div>
                                @if($errors->has('password'))
                                    <p class="warning" role="alert">{{ $errors->first('password',':message') }}</p>
                                @endif
                                <div class="input-wrapper">
                                    <label>{{ trans('app.confirm_password') }}</label>
                                    <input type="password" name="password_confirmation" placeholder="">
                                </div>
                                @if($errors->has('password_confirmation'))
                                    <p class="warning" role="alert">{{ $errors->first('password_confirmation',':message') }}</p>
                                @endif
                                <div class="input-wrapper">
                                    <label>{{ trans('app.name') }}</label>
                                    <input type="text" name="first_name" placeholder="">
                                </div>
                                @if($errors->has('first_name'))
                                    <p class="warning" role="alert">{{ $errors->first('first_name',':message') }}</p>
                                @endif
                                <button type="submit" class="btn">{{ trans('app.to_register') }}</button>
                            </form>
                            @if (session('message-success'))
                                <div class="alert alert-success">
                                    {{ session('message-success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @elseif(session('message-error'))
                                <div class="alert alert-danger">
                                    {{ session('message-error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/registration.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/registration.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/registration.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
@endsection