@if(empty($field->data))
    <div class="row">
        <div class="col-sm-12 repeater" data-parent="{{ '['.$field->slug.']' }}" data-iterator="0">
            <div class="row">
                <div class="col-sm-10">
                    @php
                        $children_fields = [];
                        foreach($fields as $lang => $lang_fields){
                           $children_fields[$lang] = $lang == $main_lang ? $field->fields : $fields[$lang][$key]->fields;
                        }
                    @endphp
                    @include('admin.pages.fields', ['parent' => (!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']', 'fields' => $children_fields, 'iterator' => 0])
                </div>
                <div class="col-sm-2 text-right">
                    <div class="btn-group">
                        <span class="btn btn-success add-item">+</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $it = 0;
    @endphp
    @foreach($field->data as $i => $data)
        @php
            $children_fields = [];
            foreach($fields as $lang => $lang_fields){
               $children_fields[$lang] = $fields[$lang][$key]->fields;
            }
            foreach($children_fields as $lang => $lang_fields){
                foreach($lang_fields as $children_key => $lang_field){
                    if(!is_array($fields[$lang][$key]->data)){
                        $fields[$lang][$key]->data = (array)$fields[$lang][$key]->data;
                    }

                    if(!empty($lang_field->slug) && isset($fields[$lang][$key]->data[$i]->{$lang_field->slug})){
                        if($lang_field->type == 'repeater'){
                            if(!empty($fields[$lang][$key]->data[$i]->{$lang_field->slug})){
                                $children_fields[$lang][$children_key]->data = $fields[$lang][$key]->data[$i]->{$lang_field->slug};
                            }
                        }else{
                            $children_fields[$lang][$children_key]->value = $fields[$lang][$key]->data[$i]->{$lang_field->slug};
                        }
                    }else{
                        $children_fields[$lang][$children_key]->value = null;
                    }
                }
            }

            $it++;
        @endphp
        <div class="row">
            <div class="col-sm-12 repeater" data-parent="[{{ $field->slug }}]" data-iterator="{{ $i }}">
                <div class="row">
                    <div class="col-sm-10">
                        @include('admin.pages.fields', ['parent' => (!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']', 'fields' => $children_fields, 'iterator' => $i])
                    </div>
                    <div class="col-sm-2 text-right">
                        <div class="btn-group">
                            @if($it == count($field->data))
                                <button type="button" class="btn btn-success add-item">+</button>
                            @endif
                            <button type="button" class="btn btn-danger remove-item"><i class="glyphicon glyphicon-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif