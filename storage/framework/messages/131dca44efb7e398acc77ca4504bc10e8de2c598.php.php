<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="Milam" width="228" height="60" />
    <p style="font-size: 20px;"><?php echo e(__('Скидочный купон на сайте Milam')); ?></p>
</div>

<p><?php echo e(trans('app.Your_promo_code')); ?>: <b><?php echo e($coupon->code); ?></b></p>
<?php if(!empty($coupon->price)): ?>
<p><?php echo e(trans('app.Discount_by_promo_code')); ?>: <b><?php echo e($coupon->price); ?>грн.</b></p>
<?php elseif(!empty($coupon->percent)): ?>
<p><?php echo e(trans('app.Discount_by_promo_code')); ?>: <b><?php echo e($coupon->percent); ?>%</b></p>
<?php endif; ?>