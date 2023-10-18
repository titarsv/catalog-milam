<div class="input-wrapper">
    <label>{{ trans('app.region') }}:</label>
    <input name="ukrpost[region]" class="input" value="{{ !empty($region) ? $region : '' }}">
</div>
{{--<div class="input-wrapper">--}}
    {{--<label>{{ trans('app.town') }}:</label>--}}
    {{--<input name="ukrpost[city]" class="input" value="{{ $city }}">--}}
{{--</div>--}}
<div class="input-wrapper">
    <label>{{ trans('app.index') }}:</label>
    <input name="ukrpost[index]" class="input">
</div>
<div class="input-wrapper">
    <label>{{ trans('app.street') }}:</label>
    <input name="ukrpost[street]" class="input">
</div>
<div class="input-wrapper">
    <label>{{ trans('app.house') }}:</label>
    <input name="ukrpost[house]" class="input">
</div>
<div class="input-wrapper">
    <label>{{ trans('app.sq') }}:</label>
    <input name="ukrpost[apart]" class="input">
</div>