<div class="selected-filters js_checked_filters<?php echo e(!empty($selected_filters) ? '' : ' hidden'); ?>">
    <?php $__currentLoopData = $selected_filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected_filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="selected-filters__clear js_remove_filter" href="<?php echo e($selected_filter['url']); ?>" data-id="<?php echo e($selected_filter['id']); ?>" data-type="<?php echo e($selected_filter['type']); ?>">
            <span><?php echo e($selected_filter['name']); ?></span>
            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.49518 0.916504L2.99935 2.41234L1.50352 0.916504L0.916016 1.504L2.41185 2.99984L0.916016 4.49567L1.50352 5.08317L2.99935 3.58734L4.49518 5.08317L5.08268 4.49567L3.58685 2.99984L5.08268 1.504L4.49518 0.916504Z" fill="#004BB3"/>
            </svg>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <a class="selected-filters__clear clear-all js_clear_filters" href="<?php echo e($category->link()); ?>">
        <span><?php echo e(__('Очистить фильтры')); ?></span>
        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.49518 0.916504L2.99935 2.41234L1.50352 0.916504L0.916016 1.504L2.41185 2.99984L0.916016 4.49567L1.50352 5.08317L2.99935 3.58734L4.49518 5.08317L5.08268 4.49567L3.58685 2.99984L5.08268 1.504L4.49518 0.916504Z" fill="#004BB3"/>
        </svg>
    </a>
</div>