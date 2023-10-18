<div class="row<?php echo e(!empty($field->langs) ? ' js_langs' : ''); ?>" style="flex-wrap: wrap">
    <?php if(!empty($field->langs)): ?>
        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $lang_fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xs-12 js_lang lng_<?php echo e($lang); ?><?php echo e($main_lang == $lang ? ' active_lang' : ''); ?>">
                <?php echo $__env->make('admin.layouts.texteditor', [
                 'content' => isset($fields[$lang][$key]->value) ? $fields[$lang][$key]->value : '',
                 'editor_id' => 'fields['.$lang.']'.(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']',
                 'placeholder' => $locales_names[$lang],
                 'data-prefix' => 'fields['.$lang.']',
                 'data-name' => $field->slug
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="col-xs-12">
            <?php echo $__env->make('admin.layouts.texteditor', [
                'content' => isset($field->value) ? $field->value : '',
                'editor_id' => 'fields[all]'.(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : '').'['.$field->slug.']',
                'data-prefix' => 'fields[all]',
                'data-name' => $field->slug
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/pages/fields/wysiwyg.blade.php ENDPATH**/ ?>