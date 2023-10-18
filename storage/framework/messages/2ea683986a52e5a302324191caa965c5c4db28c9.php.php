
<?php $__env->startSection('meta'); ?>
    <title><?php echo e(__('Авторизация')); ?></title>
    <meta name="description" content="<?php echo $settings->meta_description; ?>">
    <meta name="keywords" content="<?php echo $settings->meta_keywords; ?>">
    <meta name="robots" content="noindex, nofollow" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => __('Авторизация'),
     'description' => $settings->meta_description,
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main>
        <div class="container">
            <div class="admin">
                <div class="admin__inner">
                    <p class="admin__title"><?php echo e(__('Вход в админ панель')); ?></p>
                    <form class="admin-form" method="POST">
                        <?php echo csrf_field(); ?>

                        <input class="input" type="text" name="email" placeholder="<?php echo e(__('Логин')); ?>" style="color: #000; border: 1px solid #000">
                        <?php if($errors->has('email')): ?>
                            <p class="warning" role="alert"><?php echo e($errors->first('email',':message')); ?></p>
                        <?php endif; ?>
                        <input class="input" type="password" name="password" placeholder="<?php echo e(__('Пароль')); ?>" style="color: #000; border: 1px solid #000">
                        <?php if($errors->has('password')): ?>
                            <p class="warning" role="alert"><?php echo e($errors->first('password',':message')); ?></p>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-w"><?php echo e(__('Войти')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/login.blade.php ENDPATH**/ ?>