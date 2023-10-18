<tr class="delivery">
    <td>Область</td>
    <td>
        <select name="region" id="region" class="form-control" onchange="window.justinUpdate('region', jQuery(this).val())">
            <option value="0">Выберите область</option>
            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uuid => $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($uuid); ?>"<?php echo e(!empty($region_id) && $region_id == $uuid ? ' selected' : ''); ?>><?php echo e($region['name']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Город</td>
    <td>
        <select name="city" id="city" class="form-control" onchange="window.justinUpdate('city', jQuery(this).val())">
            <?php if(!empty($cities)): ?>
                <option value="0">Выберите город!</option>
                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uuid => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($uuid); ?>"<?php echo e(!empty($city_id) && $city_id == $uuid ? ' selected' : ''); ?>><?php echo e($city['name']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <option value="0">Сначала выберите область!</option>
            <?php endif; ?>
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Отделение почтовой службы</td>
    <td>
        <select name="warehouse" id="warehouse" class="form-control">
            <?php if(!empty($warehouses)): ?>
                <option value="0">Выберите отделение!</option>
                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($id); ?>"><?php echo e($warehouse['name_'.$lang]); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <option value="0">Сначала выберите город!</option>
            <?php endif; ?>
        </select>
    </td>
</tr>