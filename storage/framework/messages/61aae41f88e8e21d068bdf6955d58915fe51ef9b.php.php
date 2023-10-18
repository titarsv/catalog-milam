<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Акции
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Список акций</h1>

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
            <?php if($user->hasAccess(['sales.create'])): ?>
            <div class="panel-heading text-right">
                <a href="/admin/sales/create" class="btn btn-primary">Добавить новую</a>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название акции</td>
                            <td>Описание</td>
                            <td>Период действия</td>
                            <td>Статус</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($sale->name); ?></td>
                                <td class="description">
                                    <?php echo $sale->body; ?>

                                </td>
                                <td class="description">
                                    <?php echo e($sale->show_from); ?> - <?php echo e($sale->show_to); ?>

                                </td>
                                <td>
                                    <span class="<?php echo $sale->status ? 'on' : 'off'; ?>">
                                        <span class="runner"></span>
                                    </span>
                                </td>
                                <td class="actions" align="center">
                                    <?php if($user->hasAccess(['sales.view'])): ?>
                                    <a class="btn btn-primary" href="/admin/sales/edit/<?php echo $sale->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($user->hasAccess(['sales.delete'])): ?>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete('<?php echo e($sale->id); ?>', '<?php echo e($sale->name); ?>')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" align="center">Нет акционных предложений!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($sales->links()); ?>

            </div>
        </div>
    </div>

    <div id="delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить акцию <span id="delete-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm-delete">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            $('#confirm-delete').attr('href', '/admin/sales/delete/' + id);
            $('#delete-name').html(name);
            $('#delete-modal').modal();
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>