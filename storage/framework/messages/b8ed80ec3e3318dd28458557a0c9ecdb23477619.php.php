<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    SEO
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>SEO записи</h1>

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

    <form action="/admin/seo/list" method="get" id="settings-form">
        <?php echo csrf_field(); ?>

        <div class="settings row">
            <div class="col-sm-4">

            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <label for="search" class="input-group-addon">Поиск:</label>
                    <input type="text" id="search" name="search" placeholder="Введите текст..." class="form-control input-sm" value="<?php echo e(!empty($current_search) ? $current_search : ''); ?>" />
                </div>
            </div>
            <div class="col-sm-4">

            </div>
        </div>
    </form>

    <div class="panel-group">
        <div class="panel panel-default">
            <?php if($user->hasAccess(['seo.create'])): ?>
            <div class="panel-heading text-right">
                <a href="/admin/seo/create" class="btn btn-primary">Добавить новую</a>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>URL</td>
                            <td>Название записи</td>
                            <td>Описание</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $seo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->url); ?></td>
                                <td><?php echo e($item->name); ?></td>
                                <td class="description">
                                    <p><?php echo e($item->description); ?></p>
                                </td>
                                <td class="actions" align="center">
                                    <?php if($user->hasAccess(['seo.view'])): ?>
                                    <a class="btn btn-primary" href="/admin/seo/edit/<?php echo $item->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($user->hasAccess(['seo.delete'])): ?>
                                    <button type="button" class="btn btn-danger" onclick="confirmSeoDelete('<?php echo $item->id; ?>', '<?php echo $item->name; ?>')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" align="center">Нет СЕО записей!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($seo->links()); ?>

            </div>
        </div>
    </div>

    <div id="seo-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить запись <span id="category-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>