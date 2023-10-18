<div class="input-wrapper">
    <label>{{ trans('app.Region') }}</label>
    <select id="checkout-step__region" class="cart-select" name="justin[region]" onchange="justinUpdate('region', jQuery(this).val());">
        <option value="0">{{ trans('app.choose_area') }}</option>
        @foreach($regions as $uuid => $region)
            <option value="{{ $uuid }}"{{ !empty($region_id) && $region_id == $uuid ? ' selected' : '' }}>{{ $region['name'] }}</option>
        @endforeach
    </select>
</div>
<div class="input-wrapper">
    <label>{{ trans('app.City') }}</label>
    <select id="checkout-step__city" class="cart-select" name="justin[city]" onchange="justinUpdate('city', jQuery(this).val());">
        @if(!empty($cities))
            <option value="0">{{ trans('app.choose_a_city') }}</option>
            @foreach($cities as $uuid => $city)
                <option value="{{ $uuid }}"{{ !empty($city_id) && $city_id == $uuid ? ' selected' : '' }}>{{ $city['name'] }}</option>
            @endforeach
        @else
            <option value="0">{{ trans('app.first_choose_an_area') }}</option>
        @endif
    </select>
</div>
<div class="input-wrapper">
    <label>{{ trans('app.Branch') }}</label>
    <select id="checkout-step__warehouse" class="cart-select" name="justin[warehouse]">
        @if(!empty($warehouses))
            <option value="0">{{ trans('app.choose_a_warehouse') }}</option>
            @foreach($warehouses as $id => $warehouse)
                <option value="{{ $id }}">{{ $warehouse['name_'.$lang] }}</option>
            @endforeach
        @else
            <option value="0">{{ trans('app.first_choose_a_city') }}</option>
        @endif
    </select>
</div>
{{--<div class="input-wrapper">--}}
    {{--<label>{{ trans('app.Region') }}</label>--}}
    {{--<input type="text" class="input" name="justin[region]" placeholder="{{ trans('app.Specify_delivery_area') }}">--}}
{{--</div>--}}
{{--<div class="input-wrapper">--}}
    {{--<label>{{ trans('app.City') }}</label>--}}
    {{--<input type="text" class="input" name="justin[city]" placeholder="{{ trans('app.Enter_the_city_of_delivery') }}">--}}
{{--</div>--}}
{{--<div class="input-wrapper">--}}
    {{--<label>{{ trans('app.Branch') }}</label>--}}
    {{--<input type="text" class="input" name="justin[warehouse]" placeholder="{{ trans('app.Branch_number_address_of_the_selected_delivery_service') }}">--}}
{{--</div>--}}