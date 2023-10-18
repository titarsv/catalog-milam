<div class="form-group">
    <div class="row">
        <label class="col-sm-2 text-right<?php echo e(!empty($required) ? ' control-label' : ''); ?>"><?php echo e($label); ?></label>
        <div class="form-element col-sm-10">
            <select name="<?php echo e($key); ?><?php echo e(!empty($multiple) ? '[]' : ''); ?>" autocomplete="off" class="form-control chosen-select"<?php echo e(!empty($multiple) ? ' multiple="multiple"' : ''); ?>>
                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($option->id); ?>"
                            <?php if(!empty(old($key))): ?>
                                <?php if(in_array($option->id, (array)old($key))): ?>
                                selected
                                <?php endif; ?>
                            <?php elseif(in_array($option->id, $selected)): ?>
                            selected
                            <?php endif; ?>
                    ><?php echo e($option->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/layouts/form/select.blade.php ENDPATH**/ ?>