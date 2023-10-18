@extends('public.layouts.main')

@section('content')
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('wishlist') !!}
            </div>
            <div class="container">
                <div class="col">
                    <div class="account-wrapper tabs-wrapper">
                        <aside class="account-sidebar">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="account-hello">{{ trans('app.Hello') }},
                                <span>{{ $user->first_name }} {{ $user->last_name }}!</span>
                            </div>
                            <ul class="account-tabs">
                                <li><a href="{{ base_url('/user') }}">{{ trans('app.Personal_information') }}</a></li>
                                <li class="active"><span>{{ trans('app.Wish_list') }}</span></li>
                                <li><a href="{{ base_url('/user/history') }}">{{ trans('app.Order_history') }}</a></li>
                                <li><a href="{{ base_url('/user/recommend') }}">{{ trans('app.Recommendations') }}</a></li>
                                <li><a href="{{ base_url('/user/payment') }}">{{ trans('app.Payment_and_delivery') }}</a></li>
                                <li><a href="{{ base_url('/user/contacts') }}">{{ trans('app.Contacts') }}</a></li>
                                <li><a href="{{ base_url('/logout') }}">{{ trans('app.Exit') }}</a></li>
                            </ul>
                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title">{{ trans('app.Wish_list') }}</span>
                                <span class="account-descr"><span>{{ trans('app.Choose_from_your_favorite_products_and_add_them_to_the_cart') }}</span></span>
                                <div class="wishlist">
                                    <div class="row wishlist-wrapper">
                                        @foreach($products as $key => $product)
                                            @include('public.layouts.product', ['product' => $product->product, 'size' => '', 'is_wish' => true])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection