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
                <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><?php echo e($name); ?></h4>
                        </div>
                        <div class="panel-body">
                            <?php if(isset($settings->{$part})): ?>
                                <?php $__currentLoopData = $settings->{$part}; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-sm-3 form-element">
                                            <input class="form-control" type="text" name="menu[<?php echo e($part); ?>][<?php echo e($i); ?>][name]" placeholder="Название*" value="<?php echo e($item->name); ?>">
                                        </div>
                                        <div class="col-sm-5 form-element">
                                            <input class="form-control" type="text" name="menu[<?php echo e($part); ?>][<?php echo e($i); ?>][href]" placeholder="Ссылка*" value="<?php echo e($item->href); ?>">
                                        </div>
                                        <div class="col-sm-3 form-element">
                                            <input class="form-control" type="text" name="menu[<?php echo e($part); ?>][<?php echo e($i); ?>][class]" placeholder="Клас" value="<?php echo e($item->class); ?>">
                                        </div>
                                        <div class="col-sm-1 form-element">
                                            <div class="menu-image">
                                                <?php if(empty($item->image)): ?>
                                                    <i class="add-image" data-open="menu">+</i>
                                                <?php else: ?>
                                                    <i class="remove-image">-</i>
                                                <?php endif; ?>
                                                <input name="menu[<?php echo e($part); ?>][<?php echo e($i); ?>][image]" value="<?php echo e($item->image or ''); ?>" type="hidden">
                                                <img src="<?php echo e(!empty($item->image) ?  $item->image : '/uploads/no_image.jpg'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="button" class="btn button-add-menu-item" data-id="<?php echo e($part); ?>">Добавить пункт меню</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <style>
        .menu-image{
            position: relative;
            cursor: pointer;
        }
        .menu-image img{
            height: 34px;
            width: 100%;
            object-fit: contain;
        }
        .menu-image .remove-image, .menu-image .add-image{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: center;
            line-height: 34px;
        }
    </style>
    <script>
        jQuery(document).ready(function($){
            $('.button-add-menu-item').click(function () {
                var id = $(this).data('id');
                var i = $(this).parents('.panel-body').find('.row').length - 1;
                $(this).parents('.row').before('<div class="row" style="margin-bottom: 15px;">' +
                    '                            <div class="col-sm-3 form-element">' +
                    '                                <input class="form-control" type="text" name="menu['+id+']['+i+'][name]" placeholder="Название*" value="">' +
                    '                            </div>' +
                    '                            <div class="col-sm-5 form-element">' +
                    '                                <input class="form-control" type="text" name="menu['+id+']['+i+'][href]" placeholder="Ссылка*" value="">' +
                    '                            </div>' +
                    '                            <div class="col-sm-3 form-element">' +
                    '                                <input class="form-control" type="text" name="menu['+id+']['+i+'][class]" placeholder="Клас" value="">' +
                    '                            </div>' +
                    '                            <div class="col-sm-1 form-element">' +
                    '                                 <div class="menu-image">' +
                    '                                   <i class="add-image" data-open="menu">+</i>' +
                    '                                   <input name="menu['+id+']['+i+'][image]" value="" type="hidden">' +
                    '                                   <img src="/uploads/no_image.jpg">' +
                    '                                 </div>' +
                    '                            </div>'+
                    '                        </div>');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.layouts.imagesloader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>