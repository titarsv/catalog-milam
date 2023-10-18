<div class="input-wrapper select-wrapper">
    <select id="checkout-step__region" class="search-select" name="newpost_courier[region]" onchange="newpostUpdate('region', jQuery(this).val());">
        <option value="0">{{ trans('app.choose_area') }}</option>
        @foreach($regions as $region)
            <option value="{{ $region->id }}"{{ !empty($region_id) && $region_id == $region->region_id ? ' selected' : '' }}>{{ $region->{'name_'.$lang} }}</option>
        @endforeach
    </select>
</div>
<div class="input-wrapper select-wrapper">
    <select id="checkout-step__city2" class="search-select" name="newpost_courier[city]">
        @if(!empty($cities))
            <option value="0">{{ trans('app.choose_a_city') }}</option>
            @foreach($cities as $city)
                <option value="{{ $city->city_id }}"{{ !empty($city_id) && $city_id == $city->city_id ? ' selected' : '' }}>{{ $city->{'name_'.$lang} }}</option>
            @endforeach
        @else
            <option value="0">{{ trans('app.first_choose_an_area') }}</option>
        @endif
    </select>
</div>
{{--<div class="input-wrapper">
    <input name="newpost_courier[address]" class="input" placeholder="{{ trans('app.enter_your_address') }}:">
</div>--}}
<div class="input-wrapper">
    <input name="newpost_courier[street]" class="input" placeholder="{{ trans('app.street') }}:">
</div>
<div class="input-wrapper">
    <input name="newpost_courier[house]" class="input" placeholder="{{ trans('app.house') }}:">
</div>
<div class="input-wrapper">
    <input name="newpost_courier[apartment]" class="input" placeholder="{{ trans('app.flat') }}:">
</div>
<div class="input-wrapper">
    <input name="comment" class="input" placeholder="{{ trans('app.comment_for_courier') }}:">
</div>
