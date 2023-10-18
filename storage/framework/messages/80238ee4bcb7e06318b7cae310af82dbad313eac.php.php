<div class="row">
    <div class="col-sm-12">
        <select class="form-control"
                name="fields[all]<?php echo e(!empty($parent) ? $parent.'['.(isset($iterator) ? $iterator : 0).']' : ''); ?>[<?php echo e($field->slug); ?>]"
                data-prefix="fields[all]"
                autocomplete="off"
                data-name="<?php echo e($field->slug); ?>">
            <?php $__currentLoopData = explode(PHP_EOL, $field->choices); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $choice_arr = explode(':', $choice);
                ?>
                <option value="<?php echo e($choice_arr[0]); ?>"
                        <?php if(isset($field->value) && $field->value == $choice_arr[0]): ?>
                        selected
                        <?php endif; ?>
                ><?php echo e($choice_arr[1]); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>