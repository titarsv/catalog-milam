

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
                        <div class="registration">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title"><?php echo e(trans('app.Password_recovery')); ?></div>
                            <?php if(!empty($errors->all())): ?>
                                <div class="error-message">
                                    <div class="error-message__text">
                                        <?php echo $errors->first(); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <form class="registration-form" method="post">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="code" value="<?php echo e($code); ?>">
                                <div class="input-wrapper">
                                    <label><?php echo e(trans('app.New_password')); ?></label>
                                    <input type="password" name="password" id="password" class="registration-form__input <?php if($errors->has('password')): ?> input-error <?php endif; ?>" placeholder="">
                                </div>
                                <div class="input-wrapper">
                                    <label><?php echo e(trans('app.Confirm_password')); ?></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="registration-form__input <?php if($errors->has('password_confirmation')): ?> input-error <?php endif; ?>" placeholder="">
                                </div>
                                <button type="submit" class="btn"><?php echo e(trans('app.Change_password')); ?></button>
                            </form>
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