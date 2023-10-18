<div class="checkout-products-wrapper-mob" id="js_checkout_total_block_mob">
    <span class="checkout-products-title"><?php echo e(__('Корзина')); ?> (<?php echo e($cart->total_quantity); ?>)</span>
    <div class="checkout-products__list mobile">
        <?php $__currentLoopData = $cart->get_products(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_object($product['product'])): ?>
                <?php echo $__env->make('public.layouts.checkout_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>