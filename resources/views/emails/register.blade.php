<div class="header" style="text-align: center;">
    <img src="{!! url('/images/logo.png') !!}" alt="logo" title="{{ str_replace(['http://', 'https://'], '', env('APP_URL')) }}" width="228" height="60" />
</div>

<h1>Здравствуйте, <strong>{!! $user['last_name'] or '' !!} {!! $user['first_name'] !!}</strong>!</h1>
<p>Добро пожаловать в Интернет-магазин {{ env('APP_URL') }}!</p>
<p>Для входа в <a href="{!! url('/user') !!}">личный кабинет</a> используйте свой e-mail и пароль, указанный при регистрации.</p>