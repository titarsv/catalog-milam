<tr class="delivery">
    <td>Область</td>
    <td>
        <select name="region" id="region" class="form-control" onchange="window.newpostUpdate('region', jQuery(this).val())">
            <option value="0">Выберите область</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}"{{ !empty($region_id) && $region_id == $region->region_id ? ' selected' : '' }}>{{ $region->{'name_'.$lang} }}</option>
            @endforeach
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Город</td>
    <td>
        <select name="city" id="city" class="form-control" onchange="window.newpostUpdate('city', jQuery(this).val())">
            @if(!empty($cities))
                <option value="0">Выберите город!</option>
                @foreach($cities as $city)
                    <option value="{{ $city->city_id }}"{{ !empty($city_id) && $city_id == $city->city_id ? ' selected' : '' }}>{{ $city->{'name_'.$lang} }}</option>
                @endforeach
            @else
                <option value="0">Сначала выберите область!</option>
            @endif
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Отделение почтовой службы</td>
    <td>
        <select name="warehouse" id="warehouse" class="form-control">
            @if(!empty($warehouses))
                <option value="0">Выберите отделение!</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->{'address_'.$lang} }}</option>
                @endforeach
            @else
                <option value="0">Сначала выберите город!</option>
            @endif
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Номер экспресс-накладной</td>
    <td>
        <div class="input-group">
            <input type="text" class="form-control" id="js_ttn" value="{{ !empty($ttn) ? $ttn : '' }}" placeholder="Ввести вручную" autocomplete="off">
            <span class="input-group-btn">
                <button class="btn btn-primary" id="js_save_ttn" type="button"><i class="glyphicon glyphicon-refresh"></i></button>
            </span>
        </div>
        <span class="or"><span>или</span></span>
        <button type="button" id="js_generate_np_ttn" class="btn btn-success">Сгенерировать ЭН</button>
    </td>
</tr>