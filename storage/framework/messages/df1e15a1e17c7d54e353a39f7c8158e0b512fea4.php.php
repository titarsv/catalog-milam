<div class="row">
    <div class="col-sm-12">
        <select class="form-control"
                name="fields[all]<?php echo e(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : ''); ?>[<?php echo e($field->slug); ?>]"
                data-prefix="fields[all]"
                autocomplete="off"
                data-name="<?php echo e($field->slug); ?>">
            <option value=""></option>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($product->id); ?>"<?php echo e(isset($field->value) && $field->value == $product->id ? ' selected' : ''); ?>><?php echo e($product->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/pages/fields/product.blade.php ENDPATH**/ ?>