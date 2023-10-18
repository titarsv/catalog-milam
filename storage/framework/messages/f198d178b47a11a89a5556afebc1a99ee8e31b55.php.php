
<?php $__env->startSection('meta'); ?>
    <title><?php echo e(trans('app.registration')); ?></title>
    <meta name="description" content="<?php echo $settings->meta_description; ?>">
    <meta name="keywords" content="<?php echo $settings->meta_keywords; ?>">
    <meta name="robots" content="noindex, nofollow" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => trans('app.registration'),
     'description' => $settings->meta_description,
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main>
        <div class="section-login">
            <div class="container hidden-sm hidden-md hidden-lg">
                <?php echo Breadcrumbs::render('registration'); ?>

            </div>
            <div class="container">
                <div class="login-wrapper">
                    <div class="login-inner">
                        <div class="registration">
                            
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="login-title"><?php echo e(trans('app.registration')); ?></div>
                            <form class="registration-form" method="post">
                                <?php echo csrf_field(); ?>

                                <div class="input-wrapper">
                                    <label>E-mail (<?php echo e(trans('app.Login')); ?>)</label>
                                    <input type="text" name="email" placeholder="">
                                </div>
                                <?php if($errors->has('email')): ?>
                                    <p class="warning" role="alert"><?php echo e($errors->first('email',':message')); ?></p>
                                <?php endif; ?>
                                <div class="input-wrapper">
                                    <label><?php echo e(trans('app.Password')); ?></label>
                                    <input type="password" name="password" placeholder="">
                                </div>
                                <?php if($errors->has('password')): ?>
                                    <p class="warning" role="alert"><?php echo e($errors->first('password',':message')); ?></p>
                                <?php endif; ?>
                                <div class="input-wrapper">
                                    <label><?php echo e(trans('app.confirm_password')); ?></label>
                                    <input type="password" name="password_confirmation" placeholder="">
                                </div>
                                <?php if($errors->has('password_confirmation')): ?>
                                    <p class="warning" role="alert"><?php echo e($errors->first('password_confirmation',':message')); ?></p>
                                <?php endif; ?>
                                <div class="input-wrapper">
                                    <label><?php echo e(trans('app.name')); ?></label>
                                    <input type="text" name="first_name" placeholder="">
                                </div>
                                <?php if($errors->has('first_name')): ?>
                                    <p class="warning" role="alert"><?php echo e($errors->first('first_name',':message')); ?></p>
                                <?php endif; ?>
                                <button type="submit" class="btn"><?php echo e(trans('app.to_register')); ?></button>
                            </form>
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
                        </div>
                    </div>
                    <picture>
                        <source srcset="/images/pixel.webp" data-original="/images/registration.webp" class="lazy-web" type="image/webp">
                        <source srcset="/images/pixel.png" data-original="/images/registration.jpg" class="lazy-web" type="image/jpg">
                        <img src="/images/pixel.png" data-original="/images/registration.jpg"  class="lazy" alt="">
                    </picture>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>