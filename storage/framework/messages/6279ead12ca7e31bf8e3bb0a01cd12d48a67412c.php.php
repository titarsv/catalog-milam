<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Настройки
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Telegram</h1>

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
                    <div class="panel-heading">
                        <h4>Настройки Telegram</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Токен</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('token') !== null): ?>
                                        <input type="text" class="form-control" name="meta_title" value="<?php echo old('token'); ?>" />
                                        <?php if($errors->has('token')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('token',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="token" value="<?php echo e(!empty($telegram['token']) ? $telegram['token'] : ''); ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Подписчики</h4>
                    </div>
                    <div class="panel-body">
                        <?php if(!empty($telegram['clients'])): ?>
                            <div class="table table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="success">
                                        <td>Имя</td>
                                        <td>Телефон</td>
                                        <td align="center">Отправлять уведомления</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $telegram['clients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($client->name); ?></td>
                                            <td><?php echo e($client->phone); ?></td>
                                            <td><input type="checkbox" name="clients[<?php echo e($id); ?>]" value="1"<?php echo e($client->moderated ? ' checked' : ''); ?>></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" align="center">Нет запросов на рассылку!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($user->hasAccess(['settings.update'])): ?>
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