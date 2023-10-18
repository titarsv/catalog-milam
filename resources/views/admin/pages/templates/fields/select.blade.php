<div class="panel panel-default select">
    <div class="panel-body fields">
        <div class="row">
            <div class="col-sm-2" style="line-height: 1.2;">
                <label>Варианты</label>
                <small>
                    <br>
                    Введите каждый вариант выбора на новую строку.
                    <br><br>
                    Для большего контроля, вы можете ввести значение и ярлык по следующему формату:
                    <br><br>
                    red : Красный
                </small>
            </div>
            <div class="form-element col-sm-10">
                <textarea class="form-control" name="{{ isset($parent) ? $parent : '' }}[choices]" cols="30" rows="10">{{ !empty($field->choices) ? $field->choices : '' }}</textarea>
            </div>
        </div>
    </div>
</div>