<div class="row<?php echo e(!empty($field->langs) ? ' js_langs' : ''); ?>" style="flex-wrap: wrap">
    <?php if(!empty($field->langs)): ?>
        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $lang_fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xs-12 js_lang lng_<?php echo e($lang); ?><?php echo e($main_lang == $lang ? ' active_lang' : ''); ?>">
                <input type="text"
                       class="form-control"
                       name="fields[<?php echo e($lang); ?>]<?php echo e(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : ''); ?>[<?php echo e($field->slug); ?>]"
                       value="<?php echo e(isset($fields[$lang][$key]->value) ? $fields[$lang][$key]->value : ''); ?>"
                       placeholder="<?php echo e($locales_names[$lang]); ?>"
                       data-prefix="fields[<?php echo e($lang); ?>]"
                       data-name="<?php echo e($field->slug); ?>"
                />
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="col-xs-12">
            <input type="text"
                   class="form-control"
                   name="fields[all]<?php echo e(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : ''); ?>[<?php echo e($field->slug); ?>]"
                   value="<?php echo isset($field->value) ? $field->value : ''; ?>"
                   data-prefix="fields[all]"
                   data-name="<?php echo e($field->slug); ?>"
            />
        </div>
    <?php endif; ?>
</div>