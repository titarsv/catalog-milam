@extends('public.layouts.main')

@section('breadcrumbs')
    {!! Breadcrumbs::render('history') !!}
@endsection

@section('content')
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                {!! Breadcrumbs::render('history') !!}
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
                                <li class="active"><span>{{ trans('app.Order_history') }}</span></li>
                                <li><a href="{{ base_url('/user/recommend') }}">{{ trans('app.Recommendations') }}</a></li>
                                <li><a href="{{ base_url('/user/payment') }}">{{ trans('app.Payment_and_delivery') }}</a></li>
                                <li><a href="{{ base_url('/user/contacts') }}">{{ trans('app.Contacts') }}</a></li>
                                <li><a href="{{ base_url('/logout') }}">{{ trans('app.Exit') }}</a></li>
                            </ul>

                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title">{{ trans('app.Order_history') }}</span>
                                <span class="account-descr">{{ trans('app.Choose_the_ordered_goods_and_add_them_back_to_the_cart') }}</span>
                                <ul class="orders-history">
                                    @foreach($orders as $order)
                                        <li>
                                            <div class="orders-history__head">
                                                <div class="orders-history__main">
                                                    <div class="orders-history__num"><span>{{ trans('app.Order') }}: </span>
                                                        @if(!empty($order->external_id))
                                                            #{{ $order->external_id }}
                                                        @else
                                                            #0000{{ $order->id }}
                                                        @endif
                                                    </div>
                                                    @if(!empty($order->status_id))
                                                    <div class="orders-history__status"><span>{{ trans('app.Status') }}: </span>{{ $order->status->status }}</div>
                                                    @endif
                                                    @php
                                                        $ids = [];
                                                        foreach($order->getProducts() as $key => $product){
                                                            if(!is_null($product['product']) && $product['product']->stock){
                                                                $ids[] = $product['product_code'];
                                                            }
                                                        }
                                                    @endphp
                                                    <a href="javascript:void(0)" class="orders-history__one-more order-again" data-id="{{ json_encode($ids) }}"><span>{{ trans('app.order_again') }}</span></a>
                                                </div>
                                                <div class="orders-history__date">
                                                    <strong>{{ trans('app.Date_of_registration') }}:</strong>
                                                    <span>{{ $order->created_at }}</span>
                                                </div>
                                                <div class="orders-history__sum">
                                                    <strong>{{ trans('app.Sum') }}:</strong>
                                                    <span>{{ $order->total_price }} ₴</span>
                                                </div>
                                                <div class="orders-history__details">
                                                    <div class="orders-history__details-btn">{{ trans('app.DETAILS') }}</div>
                                                </div>
                                            </div>
                                            <div class="orders-history__body">
                                                <div class="orders-history__items">
                                                    @foreach($order->getProducts() as $key => $product)
                                                        @if(!empty($product['product']) && is_object($product['product']))
                                                            <a href="javascript:void(0)" class="orders-history__item">
                                                                {!! $product['product']->image == null ? '<picture class="lazy-hidden">
        <source data-src="/images/larchik/no_image.webp" srcset="/images/pixel.webpwebp" type="image/webp">
        <source data-src="/images/larchik/no_image.jpg" srcset="/images/pixel.jpg" type="image/jpeg">
        <img src="/images/pixel.jpg" alt="'.$product['product']->name.' ">
        </picture>' : $product['product']->image->webp([92, 100], ['alt' => $product['product']->name]) !!}
                                                                <div>
                                                                    <div class="orders-history__item-top">
                                                                        <span class="orders-history__item-name">{{ $product['product']->name }}</span>
                                                                        @if(!empty($product['variations']))
                                                                            @foreach($product['variations'] as $name => $val)
                                                                                <small class="orders-history__item-vol">{{ $val }}</small>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                    <div class="orders-history__item-bot">
                                                                        <p class="orders-history__item-price">{{ $product['price'] }} грн</p>
                                                                        @if($product['product']->stock)
                                                                        <span class="orders-history__item-more order-again" data-id="[{{ $product['product_code'] }}]">{{ trans('app.order_again') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="orders-history__item">
                                                                <p>{{ trans('app.Product_is_no_longer_available') }}</p>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="orders-history__details hidden-details">
                                                <div class="orders-history__details-btn">{{ trans('app.DETAILS') }}</div>
                                                <a href="javascript:void(0);" class="orders-history__one-more order-again" data-id="[{{ $product['product_code'] }}]"><span>{{ trans('app.order_again') }}</span></a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection