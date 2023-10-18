<div class="input-wrapper select-wrapper">
    <select id="checkout-step__region" class="search-select" name="newpost_courier[region]" onchange="newpostUpdate('region', jQuery(this).val());">
        <option value="0"><?php echo e(trans('app.choose_area')); ?></option>
        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($region->id); ?>"<?php echo e(!empty($region_id) && $region_id == $region->region_id ? ' selected' : ''); ?>><?php echo e($region->{'name_'.$lang}); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div class="input-wrapper select-wrapper">
    <select id="checkout-step__city2" class="search-select" name="newpost_courier[city]">
        <?php if(!empty($cities)): ?>
            <option value="0"><?php echo e(trans('app.choose_a_city')); ?></option>
            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($city->city_id); ?>"<?php echo e(!empty($city_id) && $city_id == $city->city_id ? ' selected' : ''); ?>><?php echo e($city->{'name_'.$lang}); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <option value="0"><?php echo e(trans('app.first_choose_an_area')); ?></option>
        <?php endif; ?>
    </select>
</div>

<div class="input-wrapper">
    <input name="newpost_courier[street]" class="input" placeholder="<?php echo e(trans('app.street')); ?>:">
</div>
<div class="input-wrapper">
    <input name="newpost_courier[house]" class="input" placeholder="<?php echo e(trans('app.house')); ?>:">
</div>
<div class="input-wrapper">
    <input name="newpost_courier[apartment]" class="input" placeholder="<?php echo e(trans('app.flat')); ?>:">
</div>
<div class="input-wrapper">
    <input name="comment" class="input" placeholder="<?php echo e(trans('app.comment_for_courier')); ?>:">
</div>
