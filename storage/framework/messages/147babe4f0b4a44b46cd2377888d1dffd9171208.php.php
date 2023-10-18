<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Событие
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1><?php echo e($action->action_name); ?> сущности "<?php echo e($action->entity_name); ?>" <?php echo e($action->created_at); ?></h1>

    <div class="form">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="table table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="success">
                            <td>Поле</td>
                            <?php if($action->action == 'update'): ?>
                                <td>До обновления</td>
                                <td>После обновления</td>
                            <?php else: ?>
                                <td>Значение</td>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $action->result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($field['name']); ?></td>
                                <?php if($action->action == 'update'): ?>
                                    <td><?php echo $field['old']; ?></td>
                                    <td><?php echo $field['new']; ?></td>
                                <?php else: ?>
                                    <td><?php echo $field['value']; ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table img{
            height: 50px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>