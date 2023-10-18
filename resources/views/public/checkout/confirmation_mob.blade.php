<div class="checkout-products-wrapper-mob" id="js_checkout_total_block_mob">
    <span class="checkout-products-title">{{ __('Корзина') }} ({{ $cart->total_quantity }})</span>
    <div class="checkout-products__list mobile">
        @foreach ($cart->get_products() as $code => $product)
            @if(is_object($product['product']))
                @include('public.layouts.checkout_product')
            @endif
        @endforeach
    </div>
</div>