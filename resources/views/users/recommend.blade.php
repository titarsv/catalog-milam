@extends('public.layouts.main')

@section('content')
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('recommend') !!}
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
                                <li><a href="{{ base_url('/user/wishlist') }}">{{ trans('app.Wish_list') }}</a></li>
                                <li><a href="{{ base_url('/user/history') }}">{{ trans('app.Order_history') }}</a></li>
                                <li class="active"><span>{{ trans('app.Recommendations') }}</span></li>
                                <li><a href="{{ base_url('/user/payment') }}">{{ trans('app.Payment_and_delivery') }}</a></li>
                                <li><a href="{{ base_url('/user/contacts') }}">{{ trans('app.Contacts') }}</a></li>
                                <li><a href="{{ base_url('/logout') }}">{{ trans('app.Exit') }}</a></li>
                            </ul>
                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title">{{ trans('app.Recommendations') }}</span>
                                <span class="account-descr"><span>{{ trans('app.recommend_descr') }}</span></span>
                                <div class="wishlist">
                                    <div class="row wishlist-wrapper">
                                        @foreach($products as $key => $product)
                                            @include('public.layouts.product', ['product' => $product, 'size' => ''])
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