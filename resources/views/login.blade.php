@extends('public.layouts.main')
@section('meta')
    <title>{{ __('Авторизация') }}</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
    <meta name="robots" content="noindex, nofollow" />
@endsection
@section('page_vars')
    @include('public.layouts.microdata.open_graph', [
     'title' => __('Авторизация'),
     'description' => $settings->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main class="admin-main">
        <div class="admin-wrapper">
            <div class="container">
                <div class="admin">
                    <div class="admin__inner">
                        <p class="admin__title">{{ __('Вход в админ панель') }}</p>
                        <form class="admin-form" method="POST">
                            {!! csrf_field() !!}
                            <input class="input" type="text" name="email" placeholder="{{ __('Логин') }}" style="color: #000; border: 1px solid #000">
                            @if($errors->has('email'))
                                <p class="warning" role="alert">{{ $errors->first('email',':message') }}</p>
                            @endif
                            <input class="input" type="password" name="password" placeholder="{{ __('Пароль') }}" style="color: #000; border: 1px solid #000">
                            @if($errors->has('password'))
                                <p class="warning" role="alert">{{ $errors->first('password',':message') }}</p>
                            @endif
                            <button type="submit" class="btn btn-w">{{ __('Войти') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
