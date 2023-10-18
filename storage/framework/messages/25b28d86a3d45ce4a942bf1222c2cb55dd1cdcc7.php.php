<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="<?php echo e(str_replace(['http://', 'https://'], '', env('APP_URL'))); ?>" width="228" height="60" />

    <p style="font-size: 20px;">Новое сообщение на сайте <?php echo e(env('APP_URL')); ?>!</p>

    <?php if(!empty($$phone)): ?>
    <p style="font-size: 20px;">Телефон:<b><?php echo e($phone); ?></b></p>
    <?php endif; ?>
    <?php if(!empty($email)): ?>
        <p style="font-size: 20px;">Email:<b><?php echo e($email); ?></b></p>
    <?php endif; ?>

    <p>Хочу получать информацию о скидках и специальных предложениях.</p>
</div>