<?php if(isset($fields[$main_lang])): ?>
    <?php $__currentLoopData = $fields[$main_lang]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="form-group" style="box-shadow: 0 0 2px rgba(0, 0, 0, 0.4); padding: 10px 10px 10px 0">
            <div class="row">
                <label class="col-sm-2 text-right"><?php echo e($field->name); ?></label>
                <div class="form-element col-sm-10">
                    <?php if(!empty($field->type) && in_array($field->type, ['text', 'textarea', 'wysiwyg', 'oembed', 'select', 'repeater', 'product'])): ?>
                        <?php echo $__env->make('admin.pages.fields.'.$field->type, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>