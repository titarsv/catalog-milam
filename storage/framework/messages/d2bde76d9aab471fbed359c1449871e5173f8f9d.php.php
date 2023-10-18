<div class="input-wrapper">
    <label><?php echo e(trans('app.Region')); ?></label>
    <select id="checkout-step__region" class="cart-select" name="justin[region]" onchange="justinUpdate('region', jQuery(this).val());">
        <option value="0"><?php echo e(trans('app.choose_area')); ?></option>
        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uuid => $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($uuid); ?>"<?php echo e(!empty($region_id) && $region_id == $uuid ? ' selected' : ''); ?>><?php echo e($region['name']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div class="input-wrapper">
    <label><?php echo e(trans('app.City')); ?></label>
    <select id="checkout-step__city" class="cart-select" name="justin[city]" onchange="justinUpdate('city', jQuery(this).val());">
        <?php if(!empty($cities)): ?>
            <option value="0"><?php echo e(trans('app.choose_a_city')); ?></option>
            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uuid => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($uuid); ?>"<?php echo e(!empty($city_id) && $city_id == $uuid ? ' selected' : ''); ?>><?php echo e($city['name']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <option value="0"><?php echo e(trans('app.first_choose_an_area')); ?></option>
        <?php endif; ?>
    </select>
</div>
<div class="input-wrapper">
    <label><?php echo e(trans('app.Branch')); ?></label>
    <select id="checkout-step__warehouse" class="cart-select" name="justin[warehouse]">
        <?php if(!empty($warehouses)): ?>
            <option value="0"><?php echo e(trans('app.choose_a_warehouse')); ?></option>
            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>"><?php echo e($warehouse['name_'.$lang]); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <option value="0"><?php echo e(trans('app.first_choose_a_city')); ?></option>
        <?php endif; ?>
    </select>
</div>

    
    


    
    


    
    
