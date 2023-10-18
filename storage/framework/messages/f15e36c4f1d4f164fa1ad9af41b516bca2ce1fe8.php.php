<div class="row form-group" id="value_<?php echo e($value->id); ?>">
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[<?php echo e($value->id); ?>][name_ru]" class="form-control" value="<?php echo e($value->name); ?>" placeholder="На русском" />
        <?php if($errors->has('values.'.$value->id.'.name_ru')): ?>
            <p class="warning" role="alert"><?php echo e($errors->first('values.'.$value->id.'.name_ru',':message')); ?></p>
        <?php endif; ?>
    </div>
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[<?php echo e($value->id); ?>][name_ua]" class="form-control" value="<?php echo e($value->localize('ua', 'name')); ?>" placeholder="Українською" />
        <?php if($errors->has('values.'.$value->id.'.name_ua')): ?>
            <p class="warning" role="alert"><?php echo e($errors->first('values.'.$value->id.'.name_ua',':message')); ?></p>
        <?php endif; ?>
    </div>
    <div class="col-xs-2 attribute-name">
        <input type="text" name="values[<?php echo e($value->id); ?>][name_en]" class="form-control" value="<?php echo e($value->localize('en', 'name')); ?>" placeholder="English" />
        <?php if($errors->has('values.'.$value->id.'.name_en')): ?>
            <p class="warning" role="alert"><?php echo e($errors->first('values.'.$value->id.'.name_en',':message')); ?></p>
        <?php endif; ?>
    </div>
    <div class="col-xs-3 attribute-name">
        <input type="text" name="values[<?php echo e($value->id); ?>][value]" class="form-control" value="<?php echo e($value->value); ?>" placeholder="Значение" />
        <?php if($errors->has('values.'.$value->id.'.value')): ?>
            <p class="warning" role="alert"><?php echo e($errors->first('values.'.$value->id.'.value',':message')); ?></p>
        <?php endif; ?>
    </div>
    <div class="col-xs-2 value-image">
        <?php echo $__env->make('admin.layouts.form.image', [
         'key' => 'values['.$value->id.'][file_id]',
         'image' => $value->image
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php if($user->hasAccess(['attributes.update'])): ?>
    <div class="col-xs-1 text-center">
        <button type="button" class="btn btn-danger" onclick="confirmAttributeValueDelete(<?php echo e($value->id); ?>);"><i class="glyphicon glyphicon-trash"></i></button>
    </div>
    <?php endif; ?>
</div>