<form action="/admin/orders/edit/<?php echo $order->id; ?>" method="post" id="edit_form" data-order-id="<?php echo e($order->id); ?>">
    <?php echo csrf_field(); ?>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Статус заказа</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <?php if($user->hasAccess(['orders.update'])): ?>
                            <div class="form-element col-sm-4">
                                <select name="status" class="form-control">
                                    <?php $__currentLoopData = $orders_statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($status->id == $order->status_id): ?>
                                            <option value="<?php echo e($status->id); ?>" selected><?php echo e($status->status); ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo e($status->id); ?>"><?php echo e($status->status); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-element col-sm-8 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a href="/admin/orders" class="btn btn-info">Назад</a>
                            </div>
                        <?php else: ?>
                            <div class="form-element col-sm-4">
                                <select name="status" class="form-control">
                                    <?php $__currentLoopData = $orders_statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($status->id == $order->status_id): ?>
                                            <option value="<?php echo e($status->id); ?>" selected><?php echo e($status->status); ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo e($status->id); ?>"><?php echo e($status->status); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td align="center">Артикул</td>
                        <td>Изображение</td>
                        <td>Наименование</td>
                        <td>Наличие</td>
                        <td>Количество</td>
                        <td align="center">Сумма</td>
                        <td align="center">Действие</td>
                    </tr>
                    </thead>
                    <tbody id="products_table">
                    <?php $__currentLoopData = $order->getProducts(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php if(!empty($item['product'])): ?>
                            <td align="center">
                                <input type="hidden" name="products[<?php echo e($key); ?>][code]" value="<?php echo e($item['product_code']); ?>">
                                <?php echo $item['product']->sku; ?>

                            </td>
                            <td>
                                <?php if(!empty($item['product']->image)): ?>
                                <img src="<?php echo $item['product']->image->url(); ?>" class="img-thumbnail">
                                <?php endif; ?>
                                <div><?php echo e($item['price']); ?> грн</div>
                            </td>
                            <td>
                                <?php echo $item['product']->name; ?>

                                <?php if(!empty($item['variations'])): ?>
                                    (
                                    <?php $__currentLoopData = $item['variations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($name); ?>: <?php echo e($val); ?>;
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    )
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($item['product']->stock == -2): ?>
                                    Нет в наличии
                                <?php elseif($item['product']->stock == -1): ?>
                                    Под заказ
                                <?php elseif($item['product']->stock == 0): ?>
                                    Ожидается
                                <?php elseif($item['product']->stock > 0): ?>
                                    В наличии <?php echo e($item['product']->stock); ?> шт.
                                <?php endif; ?>
                            </td>
                            <?php else: ?>
                                <td align="center"></td>
                                <td></td>
                                <td>Товар был удалён с сайта</td>
                            <?php endif; ?>
                            <td>
                                <div class="input-group" style="max-width: 120px;">
                                    <input type="number" class="form-control count_field" step="1" min="1" value="<?php echo $item['quantity']; ?>" size="5" name="products[<?php echo e($key); ?>][qty]" data-id="<?php echo e($item['product_code']); ?>">
                                    <span class="input-group-addon">шт</span>
                                </div>
                            </td>
                            <td align="center"><?php echo e(round($item['product_sum'], 2)); ?> грн</td>
                            <td align="center">
                                <?php if($user->hasAccess(['orders.update'])): ?>
                                <button type="button" class="btn btn-primary update_order_product" data-order-id="<?php echo e($order->id); ?>" data-key="<?php echo e($item['product_code']); ?>">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </button>
                                <a class="btn btn-primary" target="_blank" href="/admin/products/edit/<?php echo e($item['product']->id); ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger remove-product-from-order" data-order-id="<?php echo e($order->id); ?>" data-key="<?php echo e($item['product_code']); ?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3">
                            <?php if($user->hasAccess(['orders.update'])): ?>
                            <button type="button" class="btn btn-primary" id="add_to_order">Добавить товар к заказу</button>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(!empty($order->total_sale) ? 'Скидка: '.$order->total_sale.' грн' : ''); ?></td>
                        <td class="right">Итого:</td>
                        <td><?php echo $order->total_quantity; ?> шт</td>
                        <td align="center"><?php echo round($order->total_price - $order->total_sale, 2); ?> грн</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Информация о заказе</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="table table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td colspan="2" class="colspan">
                                        Покупатель
                                    </td>
                                </tr>
                                </thead>
                                <tr>
                                    <td>Покупатель</td>
                                    <td>
                                        <input  class="form-control" type="text" name="user_name" value="<?php echo e(isset($order->user->name) ? $order->user->name : ''); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>
                                        <input  class="form-control" type="text" name="user_phone" value="<?php echo e(isset($order->user->phone) ? $order->user->phone : ''); ?>">
                                        <p><?php echo !empty($order->user->is_callback_off) ? '<br>Не перезванивайте мне, я уверен в заказе' : ''; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Почта</td>
                                    <td>
                                        <input  class="form-control" type="email" name="user_email" value="<?php echo e(isset($order->user->email) && strpos($order->user->email, '@placeholder.com') === false ? $order->user->email : ''); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата заказа</td>
                                    <td><?php echo e($order->created_at->timezone('Europe/Kiev')->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <td>Комментарий к заказу</td>
                                    <td>
                                        <textarea class="form-control" name="comment" cols="30" rows="5"><?php echo e(!empty($order->user->comment) ? $order->user->comment : ''); ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <?php if(!empty($delivery_info)): ?>
                        <div class="table table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td colspan="2" class="colspan">
                                        Доставка и оплата
                                    </td>
                                </tr>
                                </thead>
                                <?php $__currentLoopData = $delivery_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($key == 'method'): ?>
                                        <tr>
                                            <td>Способ доставки</td>
                                            <td>
                                                <select id="js_delivery_method" data-order-id="<?php echo e($order->id); ?>" class="form-control" name="delivery" autocomplete="off">
                                                    <option value=""></option>
                                                    <option value="pickup"<?php echo e($value == 'Самовывоз' ? ' selected' : ''); ?>>Самовывоз</option>
                                                    <option value="newpost"<?php echo e($value == 'Новая Почта' ? ' selected' : ''); ?>>Новая Почта</option>
                                                    <option value="justin"<?php echo e($value == 'Самовывоз из "Justin"' ? ' selected' : ''); ?>>Justin</option>
                                                    <option value="courier"<?php echo e($value == 'Курьер по вашему адресу' ? ' selected' : ''); ?>>Адресная доставка в г. Северодонецк</option>
                                                    <option value="other"<?php echo e(!empty($value) && !in_array($value, ['Самовывоз', 'Новая Почта', 'Самовывоз из "Justin"', 'Курьер по вашему адресу']) ? ' selected' : ''); ?>>Другая служба доставки</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'region'): ?>
                                        <tr class="delivery">
                                            <td>Область</td>
                                            <td>
                                                <?php if(is_array($value)): ?>
                                                    <select name="region" id="region" class="form-control" onchange="window.<?php echo e($delivery_info['method'] == 'Новая Почта' ? 'newpost' : 'justin'); ?>Update('region', jQuery(this).val())">
                                                        <?php $__currentLoopData = $value['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option->id); ?>"<?php echo e($option->id == $value['selected'] ? ' selected' : ''); ?>><?php echo e($option->name_ru); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input name="region" class="form-control" value="<?php echo e($value); ?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'city'): ?>
                                        <tr class="delivery">
                                            <td>Город</td>
                                            <td>
                                                <?php if(is_array($value)): ?>
                                                    <select name="city" id="city" class="form-control" onchange="window.<?php echo e($delivery_info['method'] == 'Новая Почта' ? 'newpost' : 'justin'); ?>Update('city', jQuery(this).val())">
                                                        <?php $__currentLoopData = $value['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option->id); ?>"<?php echo e($option->id == $value['selected'] ? ' selected' : ''); ?>><?php echo e($option->name_ru); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input name="region" class="form-control" value="<?php echo e($value); ?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'warehouse'): ?>
                                        <tr class="delivery">
                                            <td>Отделение почтовой службы</td>
                                            <td>
                                                <?php if(is_array($value)): ?>
                                                    <select name="warehouse" id="warehouse" class="form-control">
                                                        <?php $__currentLoopData = $value['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option->id); ?>"<?php echo e($option->id == $value['selected'] ? ' selected' : ''); ?>><?php echo e($option->address_ru); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input name="region" class="form-control" value="<?php echo e($value); ?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'index' || $key == 'post_code'): ?>
                                        <tr class="delivery">
                                            <td>Почтовый индекс</td>
                                            <td><input name="post_code" class="form-control" value="<?php echo e($value); ?>"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'street'): ?>
                                        <tr class="delivery">
                                            <td>Улица</td>
                                            <td><input name="street" class="form-control" value="<?php echo e($value); ?>"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'house'): ?>
                                        <tr class="delivery">
                                            <td>Дом</td>
                                            <td><input name="house" class="form-control" value="<?php echo e($value); ?>"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'apartment'): ?>
                                        <tr class="delivery">
                                            <td>Квартира</td>
                                            <td><input name="apartment" class="form-control" value="<?php echo e($value); ?>"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'error'): ?>
                                        <tr class="delivery">
                                            <td colspan="2" class="colspan"><?php echo $value; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if($key == 'ttn'): ?>
                                        <tr class="delivery">
                                            <td>Экспресс-накладная</td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="js_ttn" value="<?php echo e(!empty($value) ? $value : ''); ?>" placeholder="Ввести вручную" autocomplete="off">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" id="js_save_ttn" type="button"><i class="glyphicon glyphicon-refresh"></i></button>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($delivery_info['method']) && $delivery_info['method'] == 'Новая Почта' && empty($delivery_info['ttn'])): ?>
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
                                <?php endif; ?>
                                <tr>
                                    <td>Способ оплаты</td>
                                    <td>
                                        <select class="form-control" name="payment" autocomplete="off">
                                            <option value=""></option>
                                            <option value="cash"<?php echo e($order->payment == 'cash' ? ' selected' : ''); ?>>Предоплата от 50 грн на карту (Остаток на наложенный платеж)</option>
                                            <option value="online"<?php echo e($order->payment == 'online' ? ' selected' : ''); ?>>Оплата онлайн</option>
                                            <option value="card"<?php echo e($order->payment == 'card' ? ' selected' : ''); ?>>Оплата на карту</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="table table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <td colspan="2" class="colspan">
                                            Доставка и оплата
                                        </td>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td>Способ доставки</td>
                                        <td>
                                            <select id="js_delivery_method" data-order-id="<?php echo e($order->id); ?>" class="form-control" name="delivery" autocomplete="off">
                                                <option value=""></option>
                                                <option value="pickup" selected>Самовывоз</option>
                                                <option value="newpost">Новая Почта</option>
                                                <option value="justin">Justin</option>
                                                <option value="courier">Адресная доставка в г. Северодонецк</option>
                                                <option value="other">Другая служба доставки</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Способ оплаты</td>
                                        <td>
                                            <select class="form-control" name="payment" autocomplete="off">
                                                <option value=""></option>
                                                <option value="cash">Предоплата от 50 грн на карту (Остаток на наложенный платеж)</option>
                                                <option value="online">Оплата онлайн</option>
                                                <option value="card">Оплата на карту</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Настройки</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Примечания</label>
                        <div class="form-element col-sm-10">
                            <textarea name="notes" class="form-control" rows="6"><?php echo $order->notes; ?></textarea>
                        </div>
                    </div>
                </div>
                <?php if($user->hasAccess(['orders.update'])): ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10 col-sm-push-2 text-left">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                            <a href="/admin/orders" class="btn btn-info">Назад</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>История</h4>
            </div>
            <div class="panel-body">
                <div class="form-group" style="margin-bottom: 0;">
                    <div class="row">
                        <label class="col-sm-2 text-right"><?php echo e($order->created_at->timezone('Europe/Kiev')->format('Y-m-d H:i:s')); ?></label>
                        <div class="form-element col-sm-10">
                            <p style="margin-top: 6px;">Создание заказа</p>
                        </div>
                    </div>
                </div>
                <?php $__currentLoopData = $order->history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $date = new \Carbon\Carbon($time);
                    ?>
                    <div class="form-group" style="margin-bottom: 0;">
                        <div class="row">
                            <label class="col-sm-2 text-right"><?php echo e($date->timezone('Europe/Kiev')->format('Y-m-d H:i:s')); ?></label>
                            <div class="form-element col-sm-10">
                                <p style="margin-top: 6px;"><?php echo e($history['msg']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

    </div>
</form>