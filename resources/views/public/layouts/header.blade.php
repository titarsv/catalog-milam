<header class="header">
    <div class="header-main">
        <div class="container">
            <div class="header-main__wrapper">
                <div class="header-left">
                    <a href="{{ base_url('/') }}" class="header-logo1">
                        <picture>
                            <source srcset="/images/pixel.webp" data-original="/images/logo-milam.webp" class="lazy-web"
                                    type="image/webp">
                            <source srcset="/images/pixel.png" data-original="/images/logo-milam.png" class="lazy-web"
                                    type="image/png">
                            <img src="/images/pixel.png" data-original="/images/logo-milam.png" class="lazy" alt="">
                        </picture>
                    </a>
                    <a href="{{ base_url('/') }}" class="header-logo2">
                        <picture>
                            <source srcset="/images/pixel.webp" data-original="/images/logo-milam-chemical.webp"
                                    class="lazy-web" type="image/webp">
                            <source srcset="/images/pixel.png" data-original="/images/logo-milam-chemical.png"
                                    class="lazy-web" type="image/png">
                            <img src="/images/pixel.png" data-original="/images/logo-milam-chemical.png" class="lazy"
                                 alt="">
                        </picture>
                    </a>
                </div>
                <div class="header-mid">
                    <span>{{ __('ООО Торговый Дом «Пирана»') }}</span>
                    <small>{{ __('Производство Бытовой Химии') }}</small>
                </div>
                <div class="header-right">
                    <div class="header-region">
                        <span>{{ __('Ваша область') }}:</span>
                        <select class="select js_region_select" autocomplete="off">
                            @foreach($regions as $i => $region)
                                @if($i < 24)
                                    <option
                                        value="{{ $i }}"{{ (!isset($_COOKIE['region']) && $i == 8) || (isset($_COOKIE['region']) && $i == $_COOKIE['region']) ? ' selected' : '' }}>{{ $region }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <ul class="header-phones">
                        <li>
                            <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>
                        </li>
                        @foreach($settings->other_phones as $phone)
                            <li><a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $phone) }}">{{ $phone }}</a></li>
                        @endforeach
                    </ul>
                    <div class="mobile-menu__btn">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bot">
        <div class="container">
            <div class="header-bot__wrapper">
                <ul class="header-menu">
                    <li{!! rtrim(env('APP_URL').'/'.request()->path(), '/') == base_url('/') ? ' class="current"' : '' !!}>
                        <a href="{{ base_url('/') }}">{{ __('Главная') }}</a>
                    </li>
                    @foreach($categories as $category)
                        <li class="{!! !empty($category->children) ? 'has-children' : '' !!}{!! !empty($category->active) ? ' current' : '' !!}">
                            <a href="{{ $category->link }}">{{ $category->name }}
                                <i>
                                    <svg width="8" height="8" viewBox="0 0 8 8" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z"
                                            fill="#ffffff"></path>
                                    </svg>
                                </i>
                            </a>
                            @if(!empty($category->children))
                                <div class="submenu">
                                    <ul>
                                        @foreach($category->children as $subcategory)
                                            <li class="{!! !empty($subcategory->children) ? 'has-children' : '' !!}{!! !empty($subcategory->active) ? ' current' : '' !!}">
                                                <a href="{{ $subcategory->link }}">
                                                    <span>{{ $subcategory->name }}</span>
                                                    @if(!empty($subcategory->children))
                                                        <i>
                                                            <svg width="5" height="8" viewBox="0 0 5 8" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.8"
                                                                      d="M4.85633 4.12072C4.90075 4.0886 4.93706 4.04564 4.96215 3.99551C4.98724 3.94539 5.00035 3.88962 5.00035 3.83299C5.00035 3.77637 4.98724 3.72059 4.96215 3.67047C4.93706 3.62035 4.90075 3.57738 4.85633 3.54527L0.523 0.395024C0.473019 0.35856 0.414477 0.337177 0.353735 0.333198C0.292993 0.329218 0.232374 0.342795 0.178464 0.372453C0.124555 0.402111 0.0794159 0.446715 0.0479525 0.50142C0.0164891 0.556126 -9.57415e-05 0.618839 1.22547e-07 0.682747L3.9795e-07 6.98324C0.000251204 7.047 0.0170493 7.10948 0.0485881 7.16397C0.0801268 7.21845 0.125213 7.26288 0.178998 7.29247C0.232783 7.32206 0.293232 7.33569 0.353844 7.3319C0.414456 7.32811 0.472938 7.30704 0.523 7.27096L4.85633 4.12072Z"
                                                                      fill="#383838"/>
                                                            </svg>
                                                        </i>
                                                    @endif
                                                </a>
                                                @if(!empty($subcategory->children))
                                                    <div class="submenu">
                                                        <ul>
                                                            @foreach($subcategory->children as $subcategory2)
                                                                <li{!! !empty($subcategory2->active) ? ' class="current"' : '' !!}>
                                                                    <a href="{{ $subcategory2->link }}">{{ $subcategory2->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <a href="{{ $subcategory->link }}">{{ __('Открыть всю категорию') }}</a>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ $category->link }}">{{ __('Вся продукция') }}</a>
                                </div>
                            @endif
                        </li>
                    @endforeach
                    @foreach($pages as $page)
                        <li{!! env('APP_URL').'/'.request()->path() == $page->link() ? ' class="current"' : '' !!}>
                            <a href="{{ $page->link() }}">{{ $page->name }}</a>
                        </li>
                    @endforeach
                    <li>
                </ul>
                <div class="header-bot__nav">
                    <ul class="header-lang">
                        @foreach($locales as $locale => $locale_name)
                            <li>
                                @if($locale == $current_locale)
                                    <span>{{ $locale_name }}</span>
                                @else
                                    <a href="{{ env('APP_URL') }}{{ $locale == $main_locale ? (empty($base_url) ? '/' : $base_url) : ('/'.$locale.($base_url == '/' ? '' : $base_url)) }}"
                                       data-lang="{{ $locale }}">{{ $locale_name }}</a>
                                @endif
                            </li>
                            @if($locale != 'en')
                                <li>/</li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="header-question popup-btn" data-mfp-src="#question-popup">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 16.875C6.91142 16.875 4.90838 16.0453 3.43153 14.5685C1.95468 13.0916 1.125 11.0886 1.125 9C1.125 6.91142 1.95468 4.90838 3.43153 3.43153C4.90838 1.95468 6.91142 1.125 9 1.125C11.0886 1.125 13.0916 1.95468 14.5685 3.43153C16.0453 4.90838 16.875 6.91142 16.875 9C16.875 11.0886 16.0453 13.0916 14.5685 14.5685C13.0916 16.0453 11.0886 16.875 9 16.875ZM9 18C11.3869 18 13.6761 17.0518 15.364 15.364C17.0518 13.6761 18 11.3869 18 9C18 6.61305 17.0518 4.32387 15.364 2.63604C13.6761 0.948212 11.3869 0 9 0C6.61305 0 4.32387 0.948212 2.63604 2.63604C0.948212 4.32387 0 6.61305 0 9C0 11.3869 0.948212 13.6761 2.63604 15.364C4.32387 17.0518 6.61305 18 9 18Z"
                                fill="white"/>
                            <path
                                d="M5.91235 6.50925C5.91081 6.54558 5.91672 6.58184 5.92971 6.61581C5.9427 6.64977 5.9625 6.68072 5.9879 6.70674C6.01329 6.73277 6.04374 6.75333 6.07738 6.76715C6.11101 6.78097 6.14712 6.78777 6.18347 6.78713H7.1116C7.26685 6.78713 7.3906 6.66 7.41085 6.50588C7.5121 5.76787 8.01835 5.23013 8.9206 5.23013C9.69235 5.23013 10.3988 5.616 10.3988 6.54412C10.3988 7.2585 9.9781 7.587 9.31322 8.0865C8.5561 8.63663 7.95647 9.279 7.99922 10.3219L8.0026 10.566C8.00378 10.6398 8.03393 10.7102 8.08655 10.762C8.13917 10.8137 8.21003 10.8428 8.28385 10.8427H9.19622C9.27082 10.8427 9.34235 10.8131 9.3951 10.7604C9.44784 10.7076 9.47747 10.6361 9.47747 10.5615V10.4434C9.47747 9.63562 9.7846 9.4005 10.6137 8.77163C11.2988 8.25075 12.0132 7.6725 12.0132 6.45863C12.0132 4.75875 10.5777 3.9375 9.0061 3.9375C7.58072 3.9375 6.01922 4.60125 5.91235 6.50925ZM7.66397 12.9926C7.66397 13.5922 8.1421 14.0355 8.80022 14.0355C9.48535 14.0355 9.95672 13.5922 9.95672 12.9926C9.95672 12.3716 9.48422 11.9351 8.7991 11.9351C8.1421 11.9351 7.66397 12.3716 7.66397 12.9926Z"
                                fill="white"/>
                        </svg>
                        {{ __('Задать вопрос') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="header-spacer"></div>
<div class="mobile-menu__wrapper">
    <div class="mobile-menu__main">

        <ul class="mobile-menu">
            <li>
                <a href="{{ base_url('/') }}"{!! request()->path() === (app()->getLocale() === 'ua' ? '/' : app()->getLocale()) ? ' class="current"' : '' !!}>{{ __('Главная') }}</a>
            </li>
            @foreach($categories as $category)
                <li{!! !empty($category->children) ? '  class="has-children"' : '' !!}>
                    <a href="{{ $category->link }}"{!! !empty($category->active) ? ' class="current"' : '' !!}>{{ $category->name }}</a>
                    @if(!empty($category->children))
                        <span{!! !empty($category->active) ? ' class="opened"' : '' !!}>
                          <i>
                            <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                  d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z"
                                  fill="#003174"/>
                            </svg>
                          </i>
                        </span>
                        <ul class="submenu"{!! !empty($category->active) ? 'style="display: block;"' : '' !!}>
                            @foreach($category->children as $subcategory)
                                <li>
                                    <a href="{{ $subcategory->link }}"{!! !empty($subcategory->active) ? ' class="current"' : '' !!}>{{ $subcategory->name }}</a>
                                    @if(!empty($subcategory->children))
                                        <span class="lvl{!! !empty($subcategory->active) ? ' opened' : '' !!}">
                                          <i>
                                            <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path
                                                      d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z"
                                                      fill="#003174"/>
                                            </svg>
                                          </i>
                                        </span>
                                        <ul class="lvl"{!! !empty($subcategory->active) ? 'style="display: block;"' : '' !!}>
                                            @foreach($subcategory->children as $subcategory2)
                                                <li><a href="{{ $subcategory2->link }}"{!! !empty($subcategory2->active) ? ' class="current"' : '' !!}>{{ $subcategory2->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            @foreach($pages as $page)
                <li>
                    <a href="{{ $page->link() }}"{!! request()->url() === $page->link() ? ' class="current"' : '' !!}>{{ $page->name }}</a>
                </li>
            @endforeach
        </ul>

        <ul class="mobile-menu__phones">
            <li>
                <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>
            </li>
            @foreach($settings->other_phones as $phone)
                <li><a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $phone) }}">{{ $phone }}</a></li>
            @endforeach
        </ul>
        <div class="mobile-menu__links">
            <ul class="mobile-menu__lang">
                @foreach($locales as $locale => $locale_name)
                    <li>
                        @if($locale == $current_locale)
                            <span>{{ $locale_name }}</span>
                        @else
                            <a href="{{ env('APP_URL') }}{{ $locale == $main_locale ? (empty($base_url) ? '/' : $base_url) : ('/'.$locale.($base_url == '/' ? '' : $base_url)) }}"
                               data-lang="{{ $locale }}">{{ $locale_name }}</a>
                        @endif
                    </li>
                    @if($locale != 'en')
                        <li>/</li>
                    @endif
                @endforeach
            </ul>
            <div class="mobile-menu__socials">
                <a href="https://www.facebook.com/tdpirana/" class="mobile-menu__insta" target="_blank" rel="nofollow">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.1169 16C15.6046 16 16 15.6046 16 15.1169V0.883059C16 0.395311 15.6046 0 15.1169 0H0.883062C0.39525 0 0 0.395311 0 0.883059V15.1169C0 15.6046 0.39525 16 0.883062 16H15.1169Z"
                            fill="#003174"></path>
                        <path
                            d="M10.219 16V10.0701H12.1355L12.4224 7.75914H10.219V6.28362C10.219 5.61452 10.3979 5.15855 11.3218 5.15855L12.5 5.15801V3.0911C12.2961 3.06293 11.5968 3 10.7831 3C9.08431 3 7.92133 4.07697 7.92133 6.05482V7.75914H6V10.0701H7.92133V16H10.219Z"
                            fill="#FFF"></path>
                    </svg>
                </a>
                <a href="https://www.instagram.com/himia_milam" class="mobile-menu__insta" target="_blank"
                   rel="nofollow">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4.64 0H11.36C13.92 0 16 2.08 16 4.64V11.36C16 12.5906 15.5111 13.7708 14.641 14.641C13.7708 15.5111 12.5906 16 11.36 16H4.64C2.08 16 0 13.92 0 11.36V4.64C0 3.4094 0.488856 2.22919 1.35902 1.35902C2.22919 0.488856 3.4094 0 4.64 0ZM4.48 1.6C3.71618 1.6 2.98364 1.90343 2.44353 2.44353C1.90343 2.98364 1.6 3.71618 1.6 4.48V11.52C1.6 13.112 2.888 14.4 4.48 14.4H11.52C12.2838 14.4 13.0164 14.0966 13.5565 13.5565C14.0966 13.0164 14.4 12.2838 14.4 11.52V4.48C14.4 2.888 13.112 1.6 11.52 1.6H4.48ZM12.2 2.8C12.4652 2.8 12.7196 2.90536 12.9071 3.09289C13.0946 3.28043 13.2 3.53478 13.2 3.8C13.2 4.06522 13.0946 4.31957 12.9071 4.50711C12.7196 4.69464 12.4652 4.8 12.2 4.8C11.9348 4.8 11.6804 4.69464 11.4929 4.50711C11.3054 4.31957 11.2 4.06522 11.2 3.8C11.2 3.53478 11.3054 3.28043 11.4929 3.09289C11.6804 2.90536 11.9348 2.8 12.2 2.8ZM8 4C9.06087 4 10.0783 4.42143 10.8284 5.17157C11.5786 5.92172 12 6.93913 12 8C12 9.06087 11.5786 10.0783 10.8284 10.8284C10.0783 11.5786 9.06087 12 8 12C6.93913 12 5.92172 11.5786 5.17157 10.8284C4.42143 10.0783 4 9.06087 4 8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4ZM8 5.6C7.36348 5.6 6.75303 5.85286 6.30294 6.30294C5.85286 6.75303 5.6 7.36348 5.6 8C5.6 8.63652 5.85286 9.24697 6.30294 9.69706C6.75303 10.1471 7.36348 10.4 8 10.4C8.63652 10.4 9.24697 10.1471 9.69706 9.69706C10.1471 9.24697 10.4 8.63652 10.4 8C10.4 7.36348 10.1471 6.75303 9.69706 6.30294C9.24697 5.85286 8.63652 5.6 8 5.6Z"
                            fill="#003174"/>
                    </svg>
                    <!--@himia_milam-->
                </a>
            </div>
        </div>
        <div class="mobile-menu__question popup-btn" data-mfp-src="#question-popup">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9 16.875C6.91142 16.875 4.90838 16.0453 3.43153 14.5685C1.95468 13.0916 1.125 11.0886 1.125 9C1.125 6.91142 1.95468 4.90838 3.43153 3.43153C4.90838 1.95468 6.91142 1.125 9 1.125C11.0886 1.125 13.0916 1.95468 14.5685 3.43153C16.0453 4.90838 16.875 6.91142 16.875 9C16.875 11.0886 16.0453 13.0916 14.5685 14.5685C13.0916 16.0453 11.0886 16.875 9 16.875ZM9 18C11.3869 18 13.6761 17.0518 15.364 15.364C17.0518 13.6761 18 11.3869 18 9C18 6.61305 17.0518 4.32387 15.364 2.63604C13.6761 0.948212 11.3869 0 9 0C6.61305 0 4.32387 0.948212 2.63604 2.63604C0.948212 4.32387 0 6.61305 0 9C0 11.3869 0.948212 13.6761 2.63604 15.364C4.32387 17.0518 6.61305 18 9 18Z"
                    fill="#003174"/>
                <path
                    d="M5.91235 6.50925C5.91081 6.54558 5.91672 6.58184 5.92971 6.61581C5.9427 6.64977 5.9625 6.68072 5.9879 6.70674C6.01329 6.73277 6.04374 6.75333 6.07738 6.76715C6.11101 6.78097 6.14712 6.78777 6.18347 6.78713H7.1116C7.26685 6.78713 7.3906 6.66 7.41085 6.50588C7.5121 5.76787 8.01835 5.23013 8.9206 5.23013C9.69235 5.23013 10.3988 5.616 10.3988 6.54412C10.3988 7.2585 9.9781 7.587 9.31322 8.0865C8.5561 8.63663 7.95647 9.279 7.99922 10.3219L8.0026 10.566C8.00378 10.6398 8.03393 10.7102 8.08655 10.762C8.13917 10.8137 8.21003 10.8428 8.28385 10.8427H9.19622C9.27082 10.8427 9.34235 10.8131 9.3951 10.7604C9.44784 10.7076 9.47747 10.6361 9.47747 10.5615V10.4434C9.47747 9.63562 9.7846 9.4005 10.6137 8.77163C11.2988 8.25075 12.0132 7.6725 12.0132 6.45863C12.0132 4.75875 10.5777 3.9375 9.0061 3.9375C7.58072 3.9375 6.01922 4.60125 5.91235 6.50925ZM7.66397 12.9926C7.66397 13.5922 8.1421 14.0355 8.80022 14.0355C9.48535 14.0355 9.95672 13.5922 9.95672 12.9926C9.95672 12.3716 9.48422 11.9351 8.7991 11.9351C8.1421 11.9351 7.66397 12.3716 7.66397 12.9926Z"
                    fill="#003174"/>
            </svg>
            {{ __('Задать вопрос') }}
        </div>
    </div>


    <div class="mobile-menu__region">
        <span>{{ __('Ваша область') }}:</span>
        <select class="select js_region_select" autocomplete="off">
            @foreach($regions as $i => $region)
                @if($i < 24)
                    <option
                        value="{{ $i }}"{{ (empty($_COOKIE['region']) && $i == 8) || (!empty($_COOKIE['region']) && $i == $_COOKIE['region']) ? ' selected' : '' }}>{{ $region }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
