<div class="col">
    <a href="<?php echo e($product->link()); ?>" class="cat-item">
        <div class="cat-pic">
            <?php echo $product->image == null ? '<picture class="pic-main">
     <source data-src="/images/larchik/no_image.webp" srcset="/images/pixel.webp" type="image/webp">
     <source data-src="/images/larchik/no_image.jpg" srcset="/images/pixel.jpg" type="image/jpeg">
     <img src="/images/pixel.jpg" alt="'.$product->name.' ">
     </picture>' : $product->image->webp([694, 694], ['picture_class' => 'pic-main', 'alt' => $product->name], !empty($is_slide) ? 'slider' : 'static'); ?>

        </div>
        <span class="cat-title"><?php echo e($product->name); ?></span>
    </a>
</div>