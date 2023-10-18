<div class="image-container">
    <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e(old($key) ? old($key) : (!empty($image) ? $image->id : '')); ?>" />
    <?php if(!empty(old($key.'_link')) || !empty($image)): ?>
        <div>
            <div>
                <i class="remove-image">-</i>
                <img src="<?php echo e(old($key.'_link') ? old($key.'_link') : ($image->type == 'video' ? '/images/larchik/video.png' : $image->url())); ?>" />
            </div>
        </div>
        <div class="upload_image_button" data-type="single" style="display: none;">
            <div class="add-btn"></div>
        </div>
    <?php else: ?>
        <div class="upload_image_button" data-type="single">
            <div class="add-btn"></div>
        </div>
    <?php endif; ?>
</div>