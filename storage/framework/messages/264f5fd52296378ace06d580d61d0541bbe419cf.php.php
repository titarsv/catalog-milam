<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Отзывы
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Список отзывов</h1>

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
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Пользователь</td>
                        <td>Товар</td>
                        <td>Оценка</td>
                        <td align="center">Опубликован</td>
                        <td align="center">Дата и время добавления</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="info">
                            <td>
                                <?php if(!empty($review->user)): ?>
                                    <?php echo e($review->user->first_name); ?> <?php echo e($review->user->last_name); ?>

                                <?php else: ?>
                                    <?php echo e($review->author); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo $review->product->name or ''; ?></td>
                            <td>
                                <?php echo $review->grade; ?>

                            </td>
                            <td class="status" align="center">
                                <span class="<?php echo $review->published ? 'on' : 'off'; ?>">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center">
                                <?php echo $review->created_at; ?>

                            </td>
                            <td class="actions" align="center">
                                <?php if($user->hasAccess(['reviews.view'])): ?>
                                <a class="btn btn-primary" href="/admin/reviews/show/<?php echo $review->id; ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($user->hasAccess(['reviews.delete'])): ?>
                                <button type="button" class="btn btn-danger" onclick="confirmReviewDelete('<?php echo $review->id; ?>')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($reviews)): ?>
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if(!empty($review->user)): ?>
                                        <?php echo e($review->user->first_name); ?> <?php echo e($review->user->last_name); ?>

                                    <?php else: ?>
                                        <?php echo e($review->author); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo $review->product->name; ?></td>
                                <td>
                                    <?php echo $review->grade; ?>

                                </td>
                                <td class="status" align="center">
                                <span class="<?php echo $review->published ? 'on' : 'off'; ?>">
                                    <span class="runner"></span>
                                </span>
                                </td>
                                <td align="center">
                                    <?php echo $review->created_at; ?>

                                </td>
                                <td class="actions" align="center">
                                    <a class="btn btn-primary" href="/admin/reviews/show/<?php echo $review->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="confirmReviewDelete('<?php echo $review->id; ?>')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif(empty($reviews) && empty($new)): ?>
                        <tr>
                            <td colspan="6">Еще не добавлено ни одного отзыва!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer text-right">
            <?php echo $all_reviews->links(); ?>

            </div>
        </div>
    </div>

    <div id="reviews-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить отзыв?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>