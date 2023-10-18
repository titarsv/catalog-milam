@if(isset($fields[$main_lang]))
    @foreach($fields[$main_lang] as $key => $field)
        <div class="form-group" style="box-shadow: 0 0 2px rgba(0, 0, 0, 0.4); padding: 10px 10px 10px 0">
            <div class="row">
                <label class="col-sm-2 text-right">{{ $field->name }}</label>
                <div class="form-element col-sm-10">
                    @if(!empty($field->type) && in_array($field->type, ['text', 'textarea', 'wysiwyg', 'oembed', 'select', 'repeater', 'product']))
                        @include('admin.pages.fields.'.$field->type)
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif