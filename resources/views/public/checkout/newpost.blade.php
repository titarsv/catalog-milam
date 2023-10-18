<div class="input-wrapper">
    <label>{{ __('Область') }}</label>
    <select id="checkout-step__region" class="cart-select" name="newpost[region]" onchange="newpostUpdate('region', jQuery(this).val());">
        <option value="0">{{ __('Выберите область') }}</option>
        @foreach($regions as $region)
            <option value="{{ $region->id }}"{{ !empty($region_id) && $region_id == $region->region_id ? ' selected' : '' }}>{{ $region->{'name_'.$lang} }}</option>
        @endforeach
    </select>
</div>
<div class="input-wrapper">
    <label>{{ __('Город') }}</label>
    <select id="checkout-step__city" class="cart-select" name="newpost[city]" onchange="newpostUpdate('city', jQuery(this).val());">
        @if(!empty($cities))
            <option value="0">{{ __('Выберите город') }}</option>
            @foreach($cities as $city)
                <option value="{{ $city->city_id }}"{{ !empty($city_id) && $city_id == $city->city_id ? ' selected' : '' }}>{{ $city->{'name_'.$lang} }}</option>
            @endforeach
        @else
            <option value="0">{{ __('Сначала выберите область') }}</option>
        @endif
    </select>
</div>
<div class="input-wrapper">
    <label>{{ __('Отделение') }}</label>
    <select id="checkout-step__warehouse" class="cart-select" name="newpost[warehouse]">
        @if(!empty($warehouses))
            <option value="0">{{ __('Выберите отделение') }}</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->{'address_'.$lang} }}</option>
            @endforeach
        @else
            <option value="0">{{ __('Сначала выберите город') }}</option>
        @endif
    </select>
</div>