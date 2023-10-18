<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Список заказов
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Список заказов</h1>
            </div>
        </div>
        
            
                
                
            
        
    </div>

    <?php if(session('message-success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message-success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif(session('message-error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('message-error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading text-right">
                <a href="/admin/orders/create" class="btn btn-primary">Создать заказ</a>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td align="center">№ заказа</td>
                        <td align="center">Фото</td>
                        <td class="left">
                            <div class="btn-group">
                                <button type="button" id="current-cat" class="btn dropdown-toggle product-sort-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="dropdown-selected-name">Статус заказа</span>
                                    <span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/admin/orders" class="sort-buttons">Все</a>
                                    </li>
                                    <?php $__currentLoopData = $order_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a type="button"
                                               data-sort="status"
                                               data-value="<?php echo $status->id; ?>"
                                               class="sort-buttons"
                                               onclick="filterProducts($(this))">
                                                <?php echo $status->status; ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </td>
                        <td>Имя пользователя</td>
                        <td>Телефон пользователя</td>
                        <td>Сумма заказа</td>
                        <td>Дата заказа</td>
                        <td>Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="location='/admin/orders/edit/<?php echo $order->id; ?>'">
                            <td align="center">
                                <?php echo $order->id; ?>

                            </td>
                            <td align="center">
                                <?php echo $order->photo(); ?>

                            </td>
                            <td class="left">
                                <?php if($order->status_id): ?>
                                <span class="order-status <?php echo $order->class; ?>"></span>
                                <span><?php echo $order->status->status; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo isset($order->user['name']) ? $order->user['name'] : ''; ?>

                            </td>
                            <td>
                                <?php echo $order->user['phone']; ?>

                            </td>
                            <td>
                                <?php echo round($order->total_price - $order->total_sale, 2); ?> грн
                            </td>
                            <td>
                                <?php echo e($order->created_at->timezone('Europe/Kiev')->format('Y-m-d H:i:s')); ?>

                            </td>
                            <td class="actions">
                                
                                    
                                
                                <?php if($user->hasAccess(['orders.view'])): ?>
                                <a class="btn btn-primary" href="/admin/orders/edit/<?php echo $order->id; ?>" data-toggle="tooltip" data-placement="top" title="Просмотр заказа">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="colspan">Пока нет заказов!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($orders->links()): ?>
                <div class="panel-footer text-right">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            navigateProductFilter();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>