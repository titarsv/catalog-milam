<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Модули
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1><?php echo $module->name; ?></h1>

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
    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr class="success">
                                <td>Название бренда</td>
                                <td>Логотип</td>
                                <td>Рекомендованные</td>
                                <td>В меню</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($brand->name); ?></td>
                                    <td>
                                        <?php if(!empty($brand->image)): ?>
                                        <img src="<?php echo e($brand->image->url()); ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td><input type="checkbox" name="home[]" value="<?php echo e($brand->id); ?>"<?php echo e(!empty($settings['home']) && in_array($brand->id, $settings['home']) ? ' checked' : ''); ?>></td>
                                    <td><input type="checkbox" name="menu[]" value="<?php echo e($brand->id); ?>"<?php echo e(!empty($settings['menu']) && in_array($brand->id, $settings['menu']) ? ' checked' : ''); ?>></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" align="center">Нет брендов!</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($user->hasAccess(['modules.update'])): ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>