<form action="/admin/orders/create" method="post" id="create_form">
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
                                        <option value="<?php echo e($status->id); ?>"><?php echo e($status->status); ?></option>
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
                                        <option value="<?php echo e($status->id); ?>"><?php echo e($status->status); ?></option>
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
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3">
                            <?php if($user->hasAccess(['orders.update'])): ?>
                            <button type="button" class="btn btn-primary" id="add_to_order">Добавить товар к заказу</button>
                            <?php endif; ?>
                        </td>
                        <td></td>
                        <td class="right">Итого:</td>
                        <td>0 шт</td>
                        <td align="center">0 грн</td>
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
                                        <input  class="form-control" type="text" name="user_name" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>
                                        <input  class="form-control" type="text" name="user_phone" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Почта</td>
                                    <td>
                                        <input  class="form-control" type="email" name="user_email" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Комментарий к заказу</td>
                                    <td>
                                        <textarea class="form-control" name="comment" cols="30" rows="5"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
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
                                        <select id="js_delivery_method" class="form-control" name="delivery" autocomplete="off">
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
                                            <option value="cash" selected>Предоплата от 50 грн на карту (Остаток на наложенный платеж)</option>
                                            <option value="online">Оплата онлайн</option>
                                            <option value="card">Оплата на карту</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
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
                            <textarea name="notes" class="form-control" rows="6"></textarea>
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
    </div>
</form>