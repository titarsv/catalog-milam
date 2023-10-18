<div class="header" style="text-align: center;">
    <img src="{!! url('/images/logo.png') !!}" alt="logo" title="Milam" width="228" height="60" />
    <p style="font-size: 20px;">{{ __('Скидочный купон на сайте Milam') }}</p>
</div>

<p>{{ trans('app.Your_promo_code') }}: <b>{{ $coupon->code }}</b></p>
@if(!empty($coupon->price))
<p>{{ trans('app.Discount_by_promo_code') }}: <b>{{ $coupon->price }}грн.</b></p>
@elseif(!empty($coupon->percent))
<p>{{ trans('app.Discount_by_promo_code') }}: <b>{{ $coupon->percent }}%</b></p>
@endif