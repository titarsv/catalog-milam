<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Заказы
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Заказы пользователя <?php echo e($user->email); ?>. <a href="/admin/users">К списку покупателей</a></h1>

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
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr class="success">
                                <td>ID</td>
                                <td>Статус</td>
                                <td>Имя пользователя</td>
                                <td>Почта пользователя</td>
                                <td>Дата заказа</td>
                                <td>Действия</td>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($order->id); ?></td>
                                    <td><?php echo e($order->status->status); ?></td>
                                    <td class="description">
                                        <p><?php echo e($order->user->first_name); ?></p>
                                    </td>
                                    <td class="description">
                                        <p><?php echo e($order->user->email); ?></p>
                                    </td>
                                    <td class="description">
                                        <p><?php echo e($order->created_at); ?></p>
                                    </td>
                                    <td class="actions">
                                        <a class="btn btn-primary" href="/admin/orders/edit/<?php echo $order->id; ?>">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        
                                        
                                        
                                        
                                        
                                        
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" align="center">Пока нет заказов!</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>