<div class="panel panel-default repeater">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4>Вложенные поля</h4>
            </div>
            <div class="col-sm-6 text-right">
                <div class="btn-group">
                    <span class="btn btn-primary add-field" data-key="{{ isset($field->fields) ? count($field->fields) : 0 }}" data-parent="{{ isset($parent) ? $parent : '' }}">Добавить поле</span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body fields">
        @if(isset($field->fields))
            @foreach($field->fields as $key => $field)
                @include('admin.pages.templates.field', ['parent' => $parent."[fields][$key]"])
            @endforeach
        @endif
    </div>
</div>