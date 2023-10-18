<?php if(isset($filter['categories'])): ?>
    <?php if(!empty($filter['categories']['values'])): ?>
        <div class="categories-filter__block">
            <div class="categories-filter__head">
                <?php echo e(__('Продукция')); ?>

            </div>
            <div class="categories-filter__body">
                <?php $__currentLoopData = $filter['categories']['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value_id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($value['url']); ?>" class="filter<?php echo e($value['checked'] ? ' checked' : ''); ?>" data-id="<?php echo e($value_id); ?>" data-name="<?php echo e($value['name']); ?>"><?php echo e($value['name']); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if(isset($filter['attributes'])): ?>
    <?php $__currentLoopData = $filter['attributes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute_id => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!empty($attribute['values'])): ?>
            <div class="categories-filter__block">
                <div class="categories-filter__head">
                    <?php echo e($attribute['name']); ?>

                </div>
                <div class="categories-filter__body">
                    <?php $__currentLoopData = $attribute['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value_id => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($value['url']); ?>" class="filter check<?php echo e($value['checked'] ? ' checked' : ''); ?>" data-id="<?php echo e($value_id); ?>" data-name="<?php echo e($value['name']); ?>"><?php echo e($value['name']); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>