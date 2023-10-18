<div class="input-wrapper">
    <label><?php echo e(trans('app.region')); ?>:</label>
    <input name="ukrpost[region]" class="input" value="<?php echo e(!empty($region) ? $region : ''); ?>">
</div>

    
    

<div class="input-wrapper">
    <label><?php echo e(trans('app.index')); ?>:</label>
    <input name="ukrpost[index]" class="input">
</div>
<div class="input-wrapper">
    <label><?php echo e(trans('app.street')); ?>:</label>
    <input name="ukrpost[street]" class="input">
</div>
<div class="input-wrapper">
    <label><?php echo e(trans('app.house')); ?>:</label>
    <input name="ukrpost[house]" class="input">
</div>
<div class="input-wrapper">
    <label><?php echo e(trans('app.sq')); ?>:</label>
    <input name="ukrpost[apart]" class="input">
</div>