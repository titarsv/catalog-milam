<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Категории
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Добавление категории</h1>

    <?php if(session('message-error')): ?>
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
                         'required' => true
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                        
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Родительская категория',
                         'key' => 'parent_id',
                         'options' => $categories,
                         'selected' => [old('parent_id') ? old('parent_id') : '']
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Выберите изображение</label>
                                <div class="form-element col-sm-3">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'file_id'
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Связанные атрибуты (для фильтрации товаров)',
                         'key' => 'related_attribute_ids',
                         'options' => $attributes,
                         'multiple' => true,
                         'selected' => old('related_attribute_ids') ? old('related_attribute_ids') : []
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Порядок сортировки',
                         'key' => 'sort_order',
                         'languages' => null
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : 0]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>