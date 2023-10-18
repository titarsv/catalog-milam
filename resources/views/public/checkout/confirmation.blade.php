<div class="checkout-col-products__inner" id="js_checkout_total_block">
    <span class="checkout-products-title">{{ __('Корзина') }} ({{ $cart->total_quantity }})</span>
    <div class="checkout-products__list">
        @foreach ($cart->get_products() as $code => $product)
            @if(is_object($product['product']))
                @include('public.layouts.checkout_product')
            @endif
        @endforeach
    </div>
    <div class="checkout-products-more">
        <div class="checkout-products-more-item discount">
            @if(!empty($cart->total_sale))
            <p>
                <span>{{ __('Стоимость заказа без скидки') }}</span>
                <span class="old-price">{{ formatted_price($cart->total_price) }}</span>
            </p>
            @endif
            @if(!empty($cart->payment_sale))
            <p>
                <span>{{ __('Скидка за оплату на сайте') }}</span>
                <span>{{ formatted_price($cart->payment_sale) }}</span>
            </p>
            @endif
            @if(!empty($cart->coupon_sale))
            <p>
                <span>{{ __('Скидка по промокоду') }}</span>
                <span>{{ formatted_price($cart->coupon_sale) }}</span>
            </p>
            @endif
            @if(!empty($cart->total_sale) || !empty($cart->payment_sale) || !empty($cart->coupon_sale))
            <p>
                <span>{{ __('Стоимость заказа со скидкой') }}</span>
                <span>{{ formatted_price($cart->total_price - $cart->total_sale - $cart->payment_sale - $cart->coupon_sale) }}</span>
            </p>
            @endif
        </div>
        <div class="checkout-products-more-item promo">
            <p>
                <span>{{ __('Промокод') }}</span>
                <span>
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.4375 5.34375L12.4687 4.375L7.5 9.34375L2.53125 4.375L1.5625 5.34375L7.5 11.25L13.4375 5.34375Z"
                            fill="#757575" />
                    </svg>
                </span>
            </p>
            <div>
                <form class="form">
                    <div class="input-wrapper">
                        <input class="input" type="text" name="text" placeholder="{{ __('Введите промо-код') }}"
                               data-validate-required="{{ __('неверный промо-код') }}">
                    </div>
                    <button type="submit" class="button">
                        <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 7L7 13L17 1" stroke="white" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="checkout-products-more-item">
            <p>
                <span>{{ __('Доставка') }}:</span>
                <span>{{ __('по тарифам перевозчика') }}</span>
            </p>
        </div>
        <div class="checkout-products-more-item">
            <p>
                <span>{{ __('Ваша экономия') }}</span>
                <span>{{ formatted_price($cart->total_sale + $cart->payment_sale + $cart->coupon_sale) }}</span>
            </p>
        </div>
        <div class="checkout-products-more-price">
            <span>{{ __('Итого к оплате') }}</span>
            <span>{{ formatted_price($cart->total_price - $cart->total_sale - $cart->payment_sale - $cart->coupon_sale) }}</span>
        </div>
    </div>
</div>