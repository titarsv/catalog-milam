

<?php $__env->startSection('meta'); ?>
    <title><?php echo e(trans('app.Password_recovery')); ?></title>
    <meta name="description" content="<?php echo $settings->meta_description; ?>">
    <meta name="keywords" content="<?php echo $settings->meta_keywords; ?>">
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
                        <div class="login">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title"><?php echo e(trans('app.specialties')); ?></div>
                            <?php if(!empty($errors->all())): ?>
                                <div class="error-message">
                                    <div class="error-message__text">
                                        <?php echo $errors->first(); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <form class="login-form sign-up-form sign-in-form" method="post">
                                <?php echo csrf_field(); ?>

                                <label>E-mail (<?php echo e(trans('app.Login')); ?>)</label>
                                <input type="text" value="<?php echo old('email'); ?>" name="email" id="email" class="<?php if($errors->has('email')): ?> input-error <?php endif; ?>" placeholder="you@mail.com">
                                <button type="submit" class="btn"><?php echo e(trans('app.REMIND')); ?></button>
                            </form>
                            <a class="login-link" href="<?php echo e(env('APP_URL')); ?><?php echo e(App::getLocale() == 'ua' ? '/ua' : ''); ?>/registration"><?php echo e(trans('app.register')); ?></a>
                            <div class="login-socials">
                                <span><?php echo e(trans('app.login_from')); ?></span>
                                <ul>
                                    <li>
                                        <a class="icon-facebook" href="<?php echo e(env('APP_URL')); ?>/login/facebook"></a>
                                    </li>
                                    <li>
                                        <a class="icon-google" href="<?php echo e(env('APP_URL')); ?>/login/google"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/forgotten.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/forgotten.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/forgotten.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>