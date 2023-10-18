<div class="row">
    <div class="col-sm-3">
        <div class="image-container">
            <input type="hidden"
                   id="fields<?php echo e(!empty($parent) ? str_replace(['[', ']'], '', $parent).(isset($iterator) ? $iterator : 0) : ''); ?><?php echo e($field->slug); ?>"
                   name="fields[all]<?php echo e(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : ''); ?>[<?php echo e($field->slug); ?>]"
                   value="<?php echo isset($field->value) ? $field->value['id'] : ''; ?>"
                   data-prefix="fields[all]"
                   data-name="<?php echo e($field->slug); ?>"
            />
            <?php if(!empty($field->value)): ?>
                <div>
                    <div>
                        <i class="remove-image">-</i>
                        <img src="<?php echo e(!empty($field->value['image']) ? $field->value['image']->url() : '/uploads/no_image.jpg'); ?>" />
                    </div>
                </div>
                <div class="upload_image_button" data-type="single" data-extensions="image" style="display: none;">
                    <div class="add-btn"></div>
                </div>
            <?php else: ?>
                <div class="upload_image_button" data-type="single" data-extensions="image">
                    <div class="add-btn"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/pages/fields/oembed.blade.php ENDPATH**/ ?>