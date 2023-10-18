<div class="input-wrapper">
    <label><?php echo e(__('Область')); ?></label>
    <select id="checkout-step__region" class="cart-select" name="newpost[region]" onchange="newpostUpdate('region', jQuery(this).val());">
        <option value="0"><?php echo e(__('Выберите область')); ?></option>
        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($region->id); ?>"<?php echo e(!empty($region_id) && $region_id == $region->region_id ? ' selected' : ''); ?>><?php echo e($region->{'name_'.$lang}); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div class="input-wrapper">
    <label><?php echo e(__('Город')); ?></label>
    <select id="checkout-step__city" class="cart-select" name="newpost[city]" onchange="newpostUpdate('city', jQuery(this).val());">
        <?php if(!empty($cities)): ?>
            <option value="0"><?php echo e(__('Выберите город')); ?></option>
            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($city->city_id); ?>"<?php echo e(!empty($city_id) && $city_id == $city->city_id ? ' selected' : ''); ?>><?php echo e($city->{'name_'.$lang}); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <option value="0"><?php echo e(__('Сначала выберите область')); ?></option>
        <?php endif; ?>
    </select>
</div>
<div class="input-wrapper">
    <label><?php echo e(__('Отделение')); ?></label>
    <select id="checkout-step__warehouse" class="cart-select" name="newpost[warehouse]">
        <?php if(!empty($warehouses)): ?>
            <option value="0"><?php echo e(__('Выберите отделение')); ?></option>
            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($warehouse->warehouse_id); ?>"><?php echo e($warehouse->{'address_'.$lang}); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <option value="0"><?php echo e(__('Сначала выберите город')); ?></option>
        <?php endif; ?>
    </select>
</div>