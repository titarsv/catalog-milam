@extends('public.layouts.main')
@section('page_vars')
    @include('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main class="main">
        {!! Breadcrumbs::render('page', $page) !!}
        <div class="section contacts-section">
            <div class="section-title">
                <div>{{ $seo->name }}</div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="contacts-top col-md-6">
                        <span>{{ $fields['screen_1_title'] }}</span>
                        <div class="contacts-top-inner">
                            <div>
                                <div class="contacts-top-inner__item">
                                    <span>{{ __('Адрес производства') }}</span>
                                    {!! $fields['screen_1_address'] !!}
                                </div>
                                <div class="contacts-top-inner__item">
                                    <span>{{ __('Телефоны') }}</span>
                                    @foreach($fields['screen_1_phones'] as $phone)
                                        <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $phone->phone) }}">{{ $phone->phone }}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="contacts-top-inner__item">
                                    <span>{{ __('Режим работы') }}</span>
                                    {!! $fields['screen_1_schedule'] !!}
                                </div>
                                <div class="contacts-top-inner__item">
                                    <span>E-mail</span>
                                    <a href="mailto:{{ $fields['screen_1_email'] }}">{{ $fields['screen_1_email'] }}</a>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="popup-btn" data-mfp-src="#partners-popup">{{ $fields['screen_1_button'] }}</a>
                    </div>
                    <div class="contacts-bot col-md-6">
                        <div class="contacts-socials">
                            <a href="https://www.facebook.com/tdpirana/" target="_blank" rel="nofollow">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.1169 16C15.6046 16 16 15.6046 16 15.1169V0.883059C16 0.395311 15.6046 0 15.1169 0H0.883062C0.39525 0 0 0.395311 0 0.883059V15.1169C0 15.6046 0.39525 16 0.883062 16H15.1169Z" fill="#004BB3"></path>
                                    <path d="M10.219 16V10.0701H12.1355L12.4224 7.75914H10.219V6.28362C10.219 5.61452 10.3979 5.15855 11.3218 5.15855L12.5 5.15801V3.0911C12.2961 3.06293 11.5968 3 10.7831 3C9.08431 3 7.92133 4.07697 7.92133 6.05482V7.75914H6V10.0701H7.92133V16H10.219Z" fill="#FFF"></path>
                                </svg>
                                @tdpirana
                            </a>
                            <a href="https://www.instagram.com/{{ $fields['screen_1_instagram'] }}" target="_blank" rel="nofollow">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.64 0H11.36C13.92 0 16 2.08 16 4.64V11.36C16 12.5906 15.5111 13.7708 14.641 14.641C13.7708 15.5111 12.5906 16 11.36 16H4.64C2.08 16 0 13.92 0 11.36V4.64C0 3.4094 0.488856 2.22919 1.35902 1.35902C2.22919 0.488856 3.4094 0 4.64 0V0ZM4.48 1.6C3.71618 1.6 2.98364 1.90343 2.44353 2.44353C1.90343 2.98364 1.6 3.71618 1.6 4.48V11.52C1.6 13.112 2.888 14.4 4.48 14.4H11.52C12.2838 14.4 13.0164 14.0966 13.5565 13.5565C14.0966 13.0164 14.4 12.2838 14.4 11.52V4.48C14.4 2.888 13.112 1.6 11.52 1.6H4.48ZM12.2 2.8C12.4652 2.8 12.7196 2.90536 12.9071 3.09289C13.0946 3.28043 13.2 3.53478 13.2 3.8C13.2 4.06522 13.0946 4.31957 12.9071 4.50711C12.7196 4.69464 12.4652 4.8 12.2 4.8C11.9348 4.8 11.6804 4.69464 11.4929 4.50711C11.3054 4.31957 11.2 4.06522 11.2 3.8C11.2 3.53478 11.3054 3.28043 11.4929 3.09289C11.6804 2.90536 11.9348 2.8 12.2 2.8ZM8 4C9.06087 4 10.0783 4.42143 10.8284 5.17157C11.5786 5.92172 12 6.93913 12 8C12 9.06087 11.5786 10.0783 10.8284 10.8284C10.0783 11.5786 9.06087 12 8 12C6.93913 12 5.92172 11.5786 5.17157 10.8284C4.42143 10.0783 4 9.06087 4 8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4V4ZM8 5.6C7.36348 5.6 6.75303 5.85286 6.30294 6.30294C5.85286 6.75303 5.6 7.36348 5.6 8C5.6 8.63652 5.85286 9.24697 6.30294 9.69706C6.75303 10.1471 7.36348 10.4 8 10.4C8.63652 10.4 9.24697 10.1471 9.69706 9.69706C10.1471 9.24697 10.4 8.63652 10.4 8C10.4 7.36348 10.1471 6.75303 9.69706 6.30294C9.24697 5.85286 8.63652 5.6 8 5.6Z"
                                        fill="#004BB3" />
                                </svg>
                                {{ '@'.$fields['screen_1_instagram'] }}</a>
                        </div>
                        <div class="contacts-bot-pic">
                            @if(!empty($fields['screen_1_image']))
                                <picture>
                                    @if(!empty($fields['screen_1_image_mob']))
                                        <source media="(max-width: 574px)" srcset="/images/pixel.webp"
                                                data-original="{{ $fields['screen_1_image_mob']['image']->url_webp([580, 340]) }}" class="lazy-web" type="image/webp">
                                        <source media="(max-width: 574px)" srcset="/images/pixel.jpg"
                                                data-original="{{ $fields['screen_1_image_mob']['image']->url([580, 340]) }}" class="lazy-web" type="image/jpg">
                                    @endif
                                    <source srcset="/images/pixel.webp" data-original="{{ $fields['screen_1_image']['image']->url_webp([1080, 640]) }}" class="lazy-web"
                                            type="image/webp">
                                    <source srcset="/images/pixel.jpg" data-original="{{ $fields['screen_1_image']['image']->url([1080, 640]) }}" class="lazy-web"
                                            type="image/jpg">
                                    <img src="/images/pixel.jpg" data-original="{{ $fields['screen_1_image']['image']->url([1080, 640]) }}" class="lazy" alt="{{ !empty($seo->name) ? $seo->name : $page->name }}">
                                </picture>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section contacts-department-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-4 col-xs-12 col">
                        <div class="contacts-department-main">
                            <span>{{ $fields['screen_2_title'] }}</span>
                            <p>{{ $fields['screen_2_subtitle'] }}</p>
                            <i><svg width="49" height="37" viewBox="0 0 49 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M29.8062 -0.000178947L27.5521 2.25388L42.0313 16.7331L2.45263e-07 16.7331L2.06019e-07 19.9206L42.0313 19.9206L27.5521 34.3998L29.8062 36.6538L48.1327 18.3273L29.8062 -0.000178947Z"
                                            fill="#C4C4C4" />
                                </svg>
                            </i>
                        </div>
                    </div>
                    <div class="col-12 col-md-3  col-sm-4 col-xs-6 col">
                        <div class="contacts-department-more">
                            <span>{{ __('Отдел сбыта') }}</span>
                            @foreach($fields['sales_department_contacts'] as $contact)
                            <div>
                                @foreach($contact->phones as $phone)
                                <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $phone->phone) }}">{{ $phone->phone }}</a>
                                @endforeach
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                <span>{{ $contact->contact }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-sm-4 col-xs-6 col">
                        <div class="contacts-department-more last">
                            <span>{{ __('Отдел снабжения') }}</span>
                            @foreach($fields['supply_department_contacts'] as $contact)
                                <div>
                                    @foreach($contact->phones as $phone)
                                        <a href="tel:{{ str_replace([' ', '(', ')', '-'], '', $phone->phone) }}">{{ $phone->phone }}</a>
                                    @endforeach
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                    <span>{{ $contact->contact }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="mfp-hide">
        <div class="popup" id="partners-popup">
            <button title="Close (Esc)" type="button" class="mfp-close">
                <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24.0349 10.792L18.5003 16.3266L12.9657 10.792L10.792 12.9657L16.3266 18.5003L10.792 24.0349L12.9657 26.2087L18.5003 20.6741L24.0349 26.2087L26.2087 24.0349L20.6741 18.5003L26.2087 12.9657L24.0349 10.792Z" fill="white"/>
                </svg>
            </button>
            <span class="popup-title">{{ __('Станьте нашим партнером!') }}</span>
            <form class="consult-form ajax_form clear-styles" data-error-title="{{ __('Ошибка отправки!') }}" data-error-message="{{ __('Попробуйте еще раз через некоторое время.') }}" data-success-title="{{ __('Спасибо за сообщение') }}" data-success-message="{{ __('Наш менеджер свяжется с Вами в ближайшее время.') }}">
                <div class="radio-wrapper">
                    <span>{{ __('Связаться как') }}:</span>
                    <div class="radio">
                        <input type="radio" name="type" value="Поставщик" id="rr1" data-title="Связаться как">
                        <label for="rr1">{{ __('Поставщик') }}</label>
                    </div>
                    <div class="radio">
                        <input type="radio" name="type" value="Дистрибьютор" id="rr2" data-title="Связаться как">
                        <label for="rr2">{{ __('Дистрибьютор') }}</label>
                    </div>
                    <div class="radio">
                        <input type="radio" name="type" value="Потребитель" id="rr3" data-title="Связаться как">
                        <label for="rr3">{{ __('Потребитель') }}</label>
                    </div>
                </div>
                <div class="input-wrapper">
                    <input class="input" type="text" name="name" placeholder="{{ __('Имя') }}" data-title="Имя" data-validate-required="{{ __('Обязательное поле') }}">
                </div>
                <div class="form-row">
                    <div class="input-wrapper">
                        <input class="input" type="text" name="email" placeholder="Email" data-title="Email"
                               data-validate-required="{{ __('Обязательное поле') }}" data-validate-email="{{ __('Неправильный email') }}">
                    </div>
                    <div class="input-wrapper">
                        <input class="input" type="tel" name="phone" placeholder="{{ __('Телефон') }}" data-title="Телефон" data-validate-required="{{ __('Обязательное поле') }}" data-validate-uaphone="{{ __('Неправильный номер') }}">
                    </div>
                </div>
                <div class="input-wrapper">
                    <input class="input" type="text" name="comment" placeholder="{{ __('Текст сообщения') }}" data-title="Сообщение" data-validate-required="{{ __('Обязательное поле') }}">
                </div>
                <button type="submit" class="btn btn-tr">{{ __('Отправить') }}</button>
            </form>
        </div>
    </div>
@endsection
