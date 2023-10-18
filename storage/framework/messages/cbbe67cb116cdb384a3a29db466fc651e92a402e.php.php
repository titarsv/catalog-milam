<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="Milam" width="228" height="60" />
    <p style="font-size: 20px;">Новый заказ № <?php echo e($order->id); ?> на сайте Milam!</p>
</div>

<table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse" width="100%">
    <tbody>
        <tr style="background:#1185c2; color: #fff; text-transform:uppercase;">
            <td align="center" height="40px" width="20%">Изображение товара</td>
            <td align="center" height="40px" width="40%">Наименование товара</td>
            <td align="center" height="40px" width="20%">Количество</td>
            <td align="center" height="40px" width="20%">Цена</td>
        </tr>
            <?php $__currentLoopData = $order->getProducts(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center" width="20%" height="150px">
                        <a href="<?php echo $item['product']->link(); ?>">
                            <img src="<?php echo !empty($item['product']->image) ? url($item['product']->image->url([100, 100])) : url('/uploads/no_image.jpg'); ?>" alt="product-image" width="100px" height="100px" style="object-fit: contain;" title="<?php echo $item['product']->name; ?>">
                        </a>
                    </td>
                    <td align="center" width="40%" height="150px">
                        <a href="<?php echo $item['product']->link(); ?>" style="color: #333;" onmouseover="this.style.color='#333'">
                            <?php echo $item['product']->name; ?>

                            <?php if(!empty($item['variations'])): ?>
                                (
                                <?php $__currentLoopData = $item['variations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($name); ?>: <?php echo e($val); ?>;
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                )
                            <?php endif; ?>
                        </a>
                    </td>
                    <td align="center" width="20%" height="150px">
                        <?php echo $item['quantity']; ?>

                    </td>
                    <td align="center" width="20%" height="150px">
                        <?php echo $item['product']->price * $item['quantity']; ?> грн
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td colspan="4" height="30px" align="right"><p style="font-size:16px;"><strong>Количество:</strong> <?php echo $order->total_quantity; ?></p></td>
        </tr>
        <tr>
            <td colspan="4" height="30px" align="right"><p style="font-size:16px;"><strong>Стоимость:</strong> <?php echo $order->total_price; ?> грн</p></td>
        </tr>
    </tbody>
</table>

<?php if($admin): ?>
    <p><strong>Заказчик:</strong> <?php echo $user['name']; ?></p>
    <?php if(strpos($user['email'], '@placeholder.com') === false): ?>
        <p><strong>E-mail:</strong> <?php echo $user['email']; ?></p>
    <?php endif; ?>
    <p><strong>Телефон:</strong> <?php echo $user['phone']; ?></p>
    <?php if(!empty($user['comment'])): ?>
    <p><strong>Комментарий к заказу:</strong> <?php echo $user['comment']; ?></p>
    <?php endif; ?>
<?php else: ?>
    <p style="font-size: 16px; color: #333;">Уважаемый <?php echo $user['name']; ?>! Благодарим Вас за заказ в интернет-магазине Milam! В ближайшее время с Вами свяжется наш менеджер для уточнения деталей заказа!</p>
<?php endif; ?>

<p style="font-size:16px; color: #333;">Информация о доставке:</p>

<?php $__currentLoopData = $order->getDeliveryInfo(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($key == 'method'): ?> <p><strong>Способ доставки: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'region'): ?> <p><strong>Область: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'city'): ?> <p><strong>Город: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'warehouse'): ?> <p><strong>Отделение: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'index' || $key == 'post_code'): ?> <p><strong>Почтовый индекс: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'street'): ?> <p><strong>Улица: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'house'): ?> <p><strong>Дом: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'apartment'): ?> <p><strong>Квартира: </strong><?php echo $value; ?></p> <?php endif; ?>
    <?php if($key == 'error'): ?> <p><strong><?php echo $value; ?></strong></p> <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($order->payment == 'cash'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Наличными при доставке</p>
<?php elseif($order->payment == 'prepayment'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Предоплата</p>
<?php elseif($order->payment == 'card'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Оплата картой</p>
<?php elseif($order->payment == 'online'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Wayforpay</p>
<?php elseif($order->payment == 'privat'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>На расчетный счет Приват Банка</p>
<?php elseif($order->payment == 'nal_delivery'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Наличными курьеру</p>
<?php elseif($order->payment == 'nal_samovivoz'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Оплата при самовывозе</p>
<?php elseif($order->payment == 'nalogenniy'): ?>
    <p style="font-size:16px; color: #333;"><strong>Оплата: </strong>Оплата наложенным платежом</p>
<?php endif; ?>