<tr class="delivery">
    <td>Область</td>
    <td>
        <select name="region" id="region" class="form-control" onchange="window.newpostUpdate('region', jQuery(this).val())">
            <option value="0">Выберите область</option>
            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($region->id); ?>"<?php echo e(!empty($region_id) && $region_id == $region->region_id ? ' selected' : ''); ?>><?php echo e($region->{'name_'.$lang}); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Город</td>
    <td>
        <select name="city" id="city" class="form-control" onchange="window.newpostUpdate('city', jQuery(this).val())">
            <?php if(!empty($cities)): ?>
                <option value="0">Выберите город!</option>
                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($city->city_id); ?>"<?php echo e(!empty($city_id) && $city_id == $city->city_id ? ' selected' : ''); ?>><?php echo e($city->{'name_'.$lang}); ?></option>
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
                <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($warehouse->warehouse_id); ?>"><?php echo e($warehouse->{'address_'.$lang}); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <option value="0">Сначала выберите город!</option>
            <?php endif; ?>
        </select>
    </td>
</tr>
<tr class="delivery">
    <td>Номер экспресс-накладной</td>
    <td>
        <div class="input-group">
            <input type="text" class="form-control" id="js_ttn" value="<?php echo e(!empty($ttn) ? $ttn : ''); ?>" placeholder="Ввести вручную" autocomplete="off">
            <span class="input-group-btn">
                <button class="btn btn-primary" id="js_save_ttn" type="button"><i class="glyphicon glyphicon-refresh"></i></button>
            </span>
        </div>
        <span class="or"><span>или</span></span>
        <button type="button" id="js_generate_np_ttn" class="btn btn-success">Сгенерировать ЭН</button>
    </td>
</tr>