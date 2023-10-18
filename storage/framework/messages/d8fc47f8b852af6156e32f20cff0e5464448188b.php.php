<div class="gallery-container">
    <?php if(!is_null($gallery)): ?>
        <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_object($image) && !empty($image->image)): ?>
                <div class="col-sm-3">
                    <div>
                        <i class="remove-gallery-image">-</i>
                        <i class="fa fa-search-plus js_zoom_image"></i>
                        <input name="<?php echo e($key); ?>[]" value="<?php echo e($image->file_id); ?>" type="hidden">
                        <img src="<?php echo e($image->image->type == 'video' ? '/images/larchik/video.png' : $image->url()); ?>">
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <div class="col-sm-3 add-gallery-image upload_image_button" data-type="multiple" data-name="<?php echo e($key); ?>">
        <div class="add-btn"></div>
    </div>
</div>