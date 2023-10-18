<div class="row form-group" id="value_{{ $value->id }}">
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[{{ $value->id }}][name_ru]" class="form-control" value="{{ $value->name }}" placeholder="На русском" />
        @if($errors->has('values.'.$value->id.'.name_ru'))
            <p class="warning" role="alert">{{ $errors->first('values.'.$value->id.'.name_ru',':message') }}</p>
        @endif
    </div>
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[{{ $value->id }}][name_ua]" class="form-control" value="{{ $value->localize('ua', 'name') }}" placeholder="Українською" />
        @if($errors->has('values.'.$value->id.'.name_ua'))
            <p class="warning" role="alert">{{ $errors->first('values.'.$value->id.'.name_ua',':message') }}</p>
        @endif
    </div>
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[{{ $value->id }}][name_en]" class="form-control" value="{{ $value->localize('en', 'name') }}" placeholder="English" />
        @if($errors->has('values.'.$value->id.'.name_en'))
            <p class="warning" role="alert">{{ $errors->first('values.'.$value->id.'.name_en',':message') }}</p>
        @endif
    </div>
    <div class="col-xs-3 attribute-name">
        <input type="text" name="values[{{ $value->id }}][value]" class="form-control" value="{{ $value->value }}" placeholder="Значение" />
        @if($errors->has('values.'.$value->id.'.value'))
            <p class="warning" role="alert">{{ $errors->first('values.'.$value->id.'.value',':message') }}</p>
        @endif
    </div>
    <div class="col-xs-2 value-image">
        @include('admin.layouts.form.image', [
         'key' => 'values['.$value->id.'][file_id]',
         'image' => $value->image
        ])
    </div>
    @if($user->hasAccess(['attributes.update']))
    <div class="col-xs-1 text-center">
        <button type="button" class="btn btn-danger" onclick="confirmAttributeValueDelete({{ $value->id }});"><i class="glyphicon glyphicon-trash"></i></button>
    </div>
    @endif
</div>