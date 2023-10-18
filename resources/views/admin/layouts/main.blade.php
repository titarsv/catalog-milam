<body>
<main class="clearfix">
    <aside id="sidebar">
        <div class="logo">
            <a href="/admin">
                <img src="/images/logo-milam.png" alt="logo" style="height: 50px; margin: 0 auto;" />
            </a>
        </div>
        @include('admin.layouts.sidebar')
        <div class="bottom-logo">
            <a href="http://triplefork.com.ua/" target="_blank">

            </a>
            <span><a href="https://triplefork.it" target="_blank" style="color: #fff">&copy; &laquo;Triplefork&raquo; {{ date('Y') }}</a></span>
        </div>
    </aside>
    <div id="content">
        <div class="row">
            <nav class="navbar col-sm-12">
                <div class="navbar-title">
                    @yield('title')
                </div>
                <ul class="nav">
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown" data-toggle="dropdown">
                            <img src="/images/larchik/flags/ru.png" alt="Русский" title="Русский">
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($locales_names as $lang => $lang_name)
                                <li class="js_lang_switcher{{ $lang == $main_lang ? ' active' : '' }}" data-lang="{{ $lang }}">
                                    <a href="javascript:void(0)">
                                        <img src="/images/larchik/flags/{{ $lang }}.png" alt="{{ $lang_name }}" title="{{ $lang_name }}"> {{ $lang_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge">{!! $new_orders + $new_reviews !!}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/orders">Заказы <span class="badge">{!! $new_orders !!}</span></a></li>
                            <li><a href="/admin/reviews">Отзывы <span class="badge">{!! $new_reviews !!}</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/admin/settings" data-toggle="tooltip" data-placement="bottom" title="Настройки">
                            <i class="fa fa-gears"></i>
                        </a>
                    </li>
                    <li>
                        <p>{!! $user->first_name !!} {!! $user->last_name !!}</p>
                    </li>

                    <li>
                        <a href="/" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Перейти в магазин">
                            <i class="fa fa-television" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" data-toggle="tooltip" data-placement="bottom" title="Выйти">
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="content-container">
            @yield('content')
        </div>
    </div>
</main>


@yield('before_footer')
@include('admin.layouts.footer')