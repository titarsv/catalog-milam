<div class="panel panel-default select">
    <div class="panel-body fields">
        <div class="row">
            <label class="col-sm-3">Мультиязычное поле</label>
            <div class="form-element col-sm-3">
                <select class="form-control" autocomplete="off" name="{{ isset($parent) ? $parent : '' }}[langs]">
                    <option value="0"{{ !empty($field->langs) ? '' : ' selected' }}>Нет</option>
                    <option value="1"{{ !empty($field->langs) ? ' selected' : '' }}>Да</option>
                </select>
            </div>
        </div>
    </div>
</div>