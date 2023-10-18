<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="<?php echo e(env('APP_URL')); ?>" width="228" height="60" />

    <?php if(!empty($email) && isset($comment)): ?>
        <p style="font-size: 20px;">Новое сообщение на сайте <?php echo e(env('APP_URL')); ?>!</p>
    <?php else: ?>
        <p style="font-size: 20px;">Поступил заказ обратного звонка на сайте <?php echo e(env('APP_URL')); ?>!</p>
    <?php endif; ?>

    <p style="font-size: 20px;">Имя:<b><?php echo e($name); ?></b></p>
    <?php if(!empty($phone)): ?>
    <p style="font-size: 20px;">Телефон:<b><?php echo e($phone); ?></b></p>
    <?php endif; ?>
    <?php if(!empty($email)): ?>
        <p style="font-size: 20px;">Email:<b><?php echo e($email); ?></b></p>
    <?php endif; ?>
    <?php if(isset($product)): ?>
        <p>Заявка пришла по следующему товару: <a href="<?php echo e(url('/product/'.$product->link())); ?>"><?php echo e($product->name); ?> (Код товара: <?php echo e($product->sku); ?>)</a></p>
    <?php endif; ?>
    <?php if(isset($comment)): ?>
        <p><b>Пользователь оставил следующий комментарий:</b><br><?php echo e($comment); ?></p>
    <?php endif; ?>
</div>