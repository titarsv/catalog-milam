<div class="form-group<?php echo e(!empty($languages) ? ' js_langs' : ''); ?>">
    <?php if(!empty($languages)): ?>
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row js_lang lng_<?php echo e($lang_key); ?><?php echo e($main_lang == $lang_key ? ' active_lang' : ''); ?>">
                <label class="col-sm-2 text-right<?php echo e(!empty($required) ? ' control-label' : ''); ?>"><?php echo e($label); ?></label>
                <div class="form-element col-sm-10">
                    <input type="text" class="form-control" name="<?php echo e($key); ?>_<?php echo e($lang_key); ?>" value="<?php echo e(old($key.'_'.$lang_key) ? old($key.'_'.$lang_key) : (isset($item) ? $item->localize($lang_key, $key) : '')); ?>" placeholder="<?php echo e($lang_name); ?>"<?php echo e(!empty($required) && $main_lang == $lang_key ? ' required' : ''); ?> />
                    <?php if($errors->has($key.'_'.$lang_key)): ?>
                        <p class="warning" role="alert"><?php echo e($errors->first($key.'_'.$lang_key,':message')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="row">
            <label class="col-sm-2 text-right<?php echo e(!empty($required) ? ' control-label' : ''); ?>"><?php echo e($label); ?></label>
            <div class="form-element col-sm-10">
                <input type="text" class="form-control" name="<?php echo e($key); ?><?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                       value="<?php echo e(old($key) ? old($key) : (isset($locale) ? (isset($item) ? $item->localize($locale, $key) : '') : (isset($item) && isset($item->$key) ? $item->$key : ''))); ?>"
                        <?php echo e(!empty($required) ? ' required' : ''); ?> />
                <?php if($errors->has($key)): ?>
                    <p class="warning" role="alert"><?php echo e($errors->first($key,':message')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>