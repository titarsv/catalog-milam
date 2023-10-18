<?php if(!empty($webp)): ?>
<picture class="<?php echo e($lazy == 'editor' ? 'editor-image' : ''); ?><?php echo e(!empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : ''); ?>"><source
    <?php if($lazy == 'slider'): ?>
    data-lazy="<?php echo e($webp); ?>"
    <?php elseif($lazy == 'static'): ?>
    data-src="<?php echo e($webp); ?>" srcset="/images/larchik/pixel.webp"
    <?php elseif($lazy == 'base64'): ?>
    <?php if(is_file(public_path(str_replace(env('APP_URL'), '', $webp)))): ?>
    srcset="data:image/webp;base64,<?php echo e(base64_encode(file_get_contents(public_path(str_replace(env('APP_URL'), '', $webp))))); ?>"
    <?php else: ?>
    srcset="<?php echo e($webp); ?>"
    <?php endif; ?>
    <?php else: ?>
    srcset="<?php echo e($webp); ?>"
    <?php endif; ?>
    type="image/webp"><source
    <?php if($lazy == 'slider'): ?>
    data-lazy="<?php echo e($original); ?>"
    <?php elseif($lazy == 'static'): ?>
    data-src="<?php echo e($original); ?>" srcset="/images/larchik/pixel.<?php echo e(empty(trim($original_mime)) ? 'jpg' : str_replace('image/', '', $original_mime)); ?>"
    <?php else: ?>
    srcset="<?php echo e($original); ?>"
    <?php endif; ?>
    type="<?php echo e(empty(trim($original_mime)) ? 'image/jpeg' : $original_mime); ?>"><img
    <?php if($lazy == 'slider'): ?>
    data-lazy="<?php echo e($original); ?>"
    src="/images/larchik/pixel.jpg"
    <?php elseif($lazy == 'static' || $lazy == 'editor'): ?>
    src="/images/larchik/pixel.jpg"
    <?php else: ?>
    src="<?php echo e($original); ?>"
    <?php endif; ?>
    <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($key != 'picture_class'): ?> <?php echo e($key); ?>="<?php echo e($attr); ?>" <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
</picture>
<?php else: ?>
    <?php if(!empty($original)): ?>
        <picture class="<?php echo e($lazy == 'editor' ? 'editor-image' : ''); ?><?php echo e(!empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : ''); ?>">
            <img src="<?php echo e($original); ?>" <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($key != 'picture_class'): ?> <?php echo e($key); ?>="<?php echo e($attr); ?>" <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
        </picture>
    <?php else: ?>
        <picture class="<?php echo e($lazy == 'editor' ? 'editor-image' : ''); ?><?php echo e(!empty($attributes['picture_class']) ? ' '.$attributes['picture_class'] : ''); ?>">
            <img src="/images/larchik/no_image.jpg" <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($key != 'picture_class'): ?> <?php echo e($key); ?>="<?php echo e($attr); ?>" <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
        </picture>
    <?php endif; ?>
<?php endif; ?>