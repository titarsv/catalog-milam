@extends('public.layouts.main')
@section('page_vars')
    @if(!empty($seo))
        @include('public.layouts.microdata.open_graph', [
         'title' => $seo->meta_title,
         'description' => $seo->meta_description,
         'image' => '/images/logo.png'
         ])
    @endif
@endsection

@section('content')
    <main class="main">
        <div class="section main-section">
            <div class="main-slider slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "fade": true, "autoplay": true, "arrows": true, "dots": true, "infinite": true}'>
                @foreach($fields['slider'] as $slide)
                <a class="slide" href="{{ !empty($slide->link) ? base_url($slide->link) : 'javascript:void(0)' }}">
                    @if(!empty($slide->image))
                        <picture>
                            @if(!empty($slide->image_mob))
                                <source media="(max-width: 480px)" srcset="/images/pixel.webp"
                                        data-original="{{ $slide->image_mob['image']->url_webp([640, 420]) }}" class="lazy-web" type="image/webp">
                                <source media="(max-width: 480px)" srcset="/images/pixel.jpg"
                                        data-original="{{ $slide->image_mob['image']->url([640, 420]) }}" class="lazy-web" type="image/jpg">
                            @endif
                            <source srcset="/images/pixel.webp" data-original="{{ $slide->image['image']->url_webp([3840, 1100]) }}" class="lazy-web"
                                    type="image/webp">
                            <source srcset="/images/pixel.jpg" data-original="{{ $slide->image['image']->url([3840, 1100]) }}" class="lazy-web"
                                    type="image/jpg">
                            <img src="/images/pixel.jpg" data-original="{{ $slide->image['image']->url([3840, 1100]) }}" class="lazy" alt="{{ !empty($seo->name) ? $seo->name : $page->name }}">
                        </picture>
                    @endif
                    <div class="container">
                        <div class="main-wrapper">
                            <div>
                                <span>{{ $slide->text }}</span>
                                @if(!empty($slide->button))
                                <div class="btn popup-btn" data-mfp-src="#partners-popup">{{ $slide->button }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        <div class="section products-section">
            <div class="section-title">
                <div>{{ $categories[0]->name }}</div>
            </div>
            <div class="container">
                <div class="products-wrapper">
                    @foreach($categories[0]->children as $subcategory)
                    <a href="{{ $subcategory->link }}" class="product-item">
                        <div class="product-pic">
                            <svg width="235" height="244" viewBox="0 0 235 244" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="path1" d="M130.128 22.5249C171.811 29.0213 200.162 52.6834 212.976 82.328C225.802 112.001 223.163 147.898 202.368 179.066L206.527 181.841C228.224 149.322 231.093 111.639 217.566 80.3442C204.026 49.0212 174.164 24.3276 130.898 17.5845L130.128 22.5249Z" fill="#78BEE1"/>
                                <path class="path2" d="M233.934 121.105C233.934 182.506 184.463 232.271 123.45 232.271C62.438 232.271 12.9673 182.506 12.9673 121.105C12.9673 89.8149 25.8143 61.5466 46.4914 41.3447C66.3879 21.9055 93.5298 9.93945 123.45 9.93945C155.619 9.93945 184.577 23.7716 204.771 45.8523C222.877 65.6511 233.934 92.0774 233.934 121.105Z" stroke="#DFDFDF" stroke-width="2"/>
                                <path class="path3" d="M129.703 233.334L129.016 223.358L129.016 223.358L129.703 233.334ZM10.6538 129.428L0.67727 130.112L10.6538 129.428ZM129.016 223.358C73.0199 227.211 24.4805 184.867 20.6304 128.743L0.67727 130.112C5.2814 197.228 63.3421 247.925 130.389 243.311L129.016 223.358ZM20.6304 128.743C16.7802 72.618 59.0836 24.0195 115.082 20.1659L113.709 0.213049C46.663 4.82701 -3.92674 62.9983 0.67727 130.112L20.6304 128.743ZM195.824 191.825C178.895 209.723 155.497 221.535 129.016 223.358L130.389 243.311C162.079 241.13 190.113 226.968 210.354 205.568L195.824 191.825Z" fill="#DFDFDF"/>
                            </svg>
                            {!! $subcategory->image !!}
                        </div>
                        <span class="product-title">{{ $subcategory->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="section popular-section">
            <div class="section-title">
                <div>{{ $fields['popular_title'] }}</div>
            </div>
            <div class="popular-wrapper">
                <div class="container">
                    <div class="popular-slider slick-slider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "arrows": true, "dots": true, "infinite": false, "responsive":[{"breakpoint":991,"settings":{"slidesToShow": 2}}]}'>
                        @foreach($fields['popular'] as $popular)
                        <a href="{{ $popular->product['product']->link() }}" class="slide">
                            <div class="popular-pic">
                                {!! $popular->product['product']->image->webp([694, 694], ['alt' => $popular->product['product']->name], 'slider') !!}
                            </div>
                            <span class="popular-title">{{ $popular->product['product']->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="section about-section">
            <div class="about-top">
                <div class="section-title">
                    <div>{{ $fields['about_title'] }}</div>
                </div>
                <div class="container">
                    <div class="about-wrapper">
                        <div class="about-pic">
                            {!! $fields['about_image']['image']->webp([960, 700], ['alt' => $fields['about_title']], 'static') !!}
                        </div>
                        <div class="about-descr">
                            {!! $fields['about_text'] !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-bot">
                <div class="container">
                    <div class="row about-brands">
                        @foreach($fields['brands'] as $i => $brand)
                        <div class="about-brand b{{ $i + 1 }}">
                            <div class="about-brand__pic">
                                {!! $brand->logo['image']->webp([345, 204], ['alt' => $brand->name], 'static') !!}
                            </div>
                            <div class="about-brand__text">
                                <span>{{ $brand->name }}</span>
                                <p>{{ $brand->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if($articles->count())
        <div class="section blog-section">
            <div class="section-title">
                <div>{{ __('Блог') }}</div>
            </div>
            <div class="blog-wrapper">
                <div class="container">
                    <div class="row blog-sliders">
                        <div class="col-md-6 col-sm-12 col-xs-12 blog-slider__txt-wrapper">
                            <div class="blog-slider__txt slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "adaptiveHeight": true, "arrows": false, "dots": false, "infinite": false, "asNavFor": ".blog-slider__pic"}'>
                                @foreach($articles as $article)
                                <div class="slide">
                                    <span>{{ $article->name }}</span>
                                    {!! $article->body !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 blog-slider__pic-wrapper">
                            <div class="blog-slider__pic slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "arrows": true, "dots": false, "infinite": false, "asNavFor": ".blog-slider__txt", "responsive":[{"breakpoint":574,"settings":{"fade": true}}]}'>
                                @foreach($articles as $article)
                                    <div class="slide">
                                        {!! $article->image->webp([555, 329], ['alt' => $article->name], 'slide') !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @include('public.layouts.consult')
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
