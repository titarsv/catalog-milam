<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Просмотр отзыва
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Просмотр отзыва</h1>
            </div>
        </div>
    </div>

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr class="success">
                                <td>Пользователь</td>
                                <td>Товар</td>
                                <td align="center">Оценка</td>
                                <td>Содержание отзыва</td>
                                <td>Дата и время</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td width="14%">
                                    <?php if(!empty($review->user)): ?>
                                        <?php echo e($review->user->first_name); ?> <?php echo e($review->user->last_name); ?>

                                    <?php else: ?>
                                        <?php echo e($review->author); ?>

                                    <?php endif; ?>
                                </td>
                                <td width="20%"><?php echo e($review->product->name); ?></td>
                                <td align="center" width="7%"><?php echo e($review->grade); ?></td>
                                <td width="45%"><?php echo e($review->review); ?></td>
                                <td width="14%"><?php echo e($review->created_at); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Ответ</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="answer" class="form-control" rows="6"><?php echo $review->answer; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Подтверждение покупки</label>
                                <div class="form-element col-sm-10">
                                    <select name="confirmed_purchase" class="form-control" autocomplete="off">
                                        <option value="1"<?php echo e($review->confirmed_purchase ? ' selected' : ''); ?>>Да</option>
                                        <option value="0"<?php echo e($review->confirmed_purchase ? '' : ' selected'); ?>>Нет</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Опубликовать</label>
                                <div class="form-element col-sm-10">
                                    <select name="published" class="form-control" autocomplete="off">
                                        <option value="1"<?php echo e($review->published ? ' selected' : ''); ?>>Да</option>
                                        <option value="0"<?php echo e($review->published ? '' : ' selected'); ?>>Нет</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <input type="hidden" name="product_id" value="<?php echo e($review->product_id); ?>">
                                    <?php if($user->hasAccess(['reviews.update'])): ?>
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                    <?php endif; ?>
                                    <a href="/admin/reviews" class="btn btn-primary">Назад</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>