<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    События
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>События</h1>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Тип события</td>
                            <td>Тип объекта</td>
                            <td>Инициатор</td>
                            <td>Дата</td>
                            <td align="center">Подробнее</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->action_name); ?></td>
                                <td><?php echo e($item->entity_name); ?></td>
                                <td><?php echo e($item->user->first_name); ?> <?php echo e($item->user->last_name); ?></td>
                                <td><?php echo e($item->created_at); ?></td>
                                <td class="actions" align="center">
                                    <?php if($user->hasAccess(['actions.view'])): ?>
                                    <a class="btn btn-primary" href="/admin/actions/show/<?php echo $item->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" align="center">Нет событий!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($actions->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>