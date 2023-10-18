<div class="panel panel-default repeater">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h4>Вложенные поля</h4>
            </div>
            <div class="col-sm-6 text-right">
                <div class="btn-group">
                    <span class="btn btn-primary add-field" data-key="<?php echo e(isset($field->fields) ? count($field->fields) : 0); ?>" data-parent="<?php echo e(isset($parent) ? $parent : ''); ?>">Добавить поле</span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body fields">
        <?php if(isset($field->fields)): ?>
            <?php $__currentLoopData = $field->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.pages.templates.field', ['parent' => $parent."[fields][$key]"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/pages/templates/fields/repeater.blade.php ENDPATH**/ ?>