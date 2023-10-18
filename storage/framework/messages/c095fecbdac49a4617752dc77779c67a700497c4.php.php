

<?php $__env->startSection('meta'); ?>
    <title>Восстановление пароля</title>
    <meta name="description" content="<?php echo $settings->meta_description; ?>">
    <meta name="keywords" content="<?php echo $settings->meta_keywords; ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <?php echo Breadcrumbs::render('forgotten'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main>
        <div class="section-login">
            <div class="container hidden-sm hidden-md hidden-lg">
                <?php echo Breadcrumbs::render('forgotten'); ?>

            </div>
            <div class="container">
                <div class="login-wrapper">
                    <div class="login-inner">
                        <div class="registration">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title"><?php echo e(trans('app.Password_recovery')); ?></div>
                            <p><?php echo e(trans('app.Password_recovery_is_complete_you_can_now_log_in_to_the_site_using_a_new_password')); ?></p>
                            <a href="/login" class="login-link"><?php echo e(trans('app.Login_btn')); ?></a>
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/reset.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/reset.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/reset.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>