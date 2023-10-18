<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Категории
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Список категорий</h1>

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
            <?php if($user->hasAccess(['categories.create'])): ?>
            <div class="panel-heading text-right">
                <a href="/admin/categories/create" class="btn btn-primary">Добавить новую</a>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название категории</td>
                            <td>Українською</td>
                            <td>English</td>
                            <td>Порядок сортировки</td>
                            <td>Статус</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="<?php echo e($category->link()); ?>" target="_blank"><?php echo e($category->name); ?></a></td>
                                <td><?php echo e($category->localize('ua', 'name')); ?></td>
                                <td><?php echo e($category->localize('en', 'name')); ?></td>
                                <td><?php echo e($category->sort_order); ?></td>
                                <td class="status">
                                    <span class="<?php echo $category->status ? 'on' : 'off'; ?>">
                                        <span class="runner"></span>
                                    </span>
                                </td>
                                <td class="actions" align="center">
                                    <?php if($user->hasAccess(['categories.view'])): ?>
                                    <a class="btn btn-primary" href="/admin/categories/edit/<?php echo $category->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($user->hasAccess(['categories.delete']) && $category->id > 1): ?>
                                        <button type="button" class="btn btn-danger" onclick="confirmCategoriesDelete('<?php echo e($category->id); ?>', '<?php echo e($category->name); ?>')">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" align="center">Нет добавленных категорий!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($categories->links()); ?>

            </div>
        </div>
    </div>

    <div id="categories-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить категорию <span id="category-name"></span>?</p>
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