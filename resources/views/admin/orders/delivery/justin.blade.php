<tr class="delivery">
    <td>Область</td>
    <td>
        <select name="region" id="region" class="form-control" onchange="window.justinUpdate('region', jQuery(this).val())">
            <option value="0">Выберите область</option>
            @foreach($regions as $uuid => $region)
                <option value="{{ $uuid }}"{{ !empty($region_id) && $region_id == $uuid ? ' selected' : '' }}>{{ $region['name'] }}</option>
            @endforeach
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Город</td>
    <td>
        <select name="city" id="city" class="form-control" onchange="window.justinUpdate('city', jQuery(this).val())">
            @if(!empty($cities))
                <option value="0">Выберите город!</option>
                @foreach($cities as $uuid => $city)
                    <option value="{{ $uuid }}"{{ !empty($city_id) && $city_id == $uuid ? ' selected' : '' }}>{{ $city['name'] }}</option>
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
                @foreach($warehouses as $id => $warehouse)
                    <option value="{{ $id }}">{{ $warehouse['name_'.$lang] }}</option>
                @endforeach
            @else
                <option value="0">Сначала выберите город!</option>
            @endif
        </select>
    </td>
</tr>