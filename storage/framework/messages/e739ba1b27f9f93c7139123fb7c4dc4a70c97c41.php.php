<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Наша работа
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование нашей работы <?php echo e($work->name); ?></h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $work,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображение</label>
                                <div class="form-element col-sm-3">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'file_id',
                                     'image' => $work->image
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-element col-sm-7">
                                    <label class="gallery-label">Галлерея</label>
                                    <?php echo $__env->make('admin.layouts.form.gallery', [
                                     'key' => 'gallery',
                                     'gallery' => $work->gallery
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Описание',
                         'key' => 'description',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $work,
                         'languages' => $languages
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Результат работы',
                         'key' => 'result',
                         'locale' => 'ru',
                         'item' => $work,
                         'languages' => $languages
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Имя заказчика',
                         'key' => 'customer',
                         'locale' => 'ru',
                         'item' => $work,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата отзыва</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control review_date" name="review_date" value="<?php echo e(old('review_date') ? old('review_date') : $work->review_date->format('d.m.Y')); ?>">
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Оценка',
                         'key' => 'rating',
                         'options' => [(object)['id' => 1, 'name' => 1], (object)['id' => 2, 'name' => 2], (object)['id' => 3, 'name' => 3], (object)['id' => 24, 'name' => 4], (object)['id' => 5, 'name' => 5]],
                         'selected' => [old('rating') ? old('rating') : $work->rating]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Отзыв',
                         'key' => 'review',
                         'locale' => 'ru',
                         'item' => $work,
                         'languages' => $languages
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Ответ на отзыв',
                         'key' => 'answer',
                         'locale' => 'ru',
                         'item' => $work,
                         'languages' => $languages
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'visible',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('visible') ? old('visible') : $work->visible]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if($user->hasAccess(['news.update'])): ?>
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

    <link rel="stylesheet" href="/css/larchik/jquery.datetimepicker.min.css" />
    <script src="/js/larchik/jquery.datetimepicker.full.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            jQuery.datetimepicker.setLocale('ru');
            $('.review_date').datetimepicker({
                datepicker:true,
                timepicker:false,
                format:'d.m.Y',
                step:30
            });
        });
    </script>
    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>