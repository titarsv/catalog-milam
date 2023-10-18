

<?php $__env->startSection('content'); ?>
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                <?php echo Breadcrumbs::render('recommend'); ?>

            </div>
            <div class="container">
                <div class="col">
                    <div class="account-wrapper tabs-wrapper">
                        <aside class="account-sidebar">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="account-hello"><?php echo e(trans('app.Hello')); ?>,
                                <span><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>!</span>
                            </div>
                            <ul class="account-tabs">
                                <li><a href="<?php echo e(base_url('/user')); ?>"><?php echo e(trans('app.Personal_information')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/wishlist')); ?>"><?php echo e(trans('app.Wish_list')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/history')); ?>"><?php echo e(trans('app.Order_history')); ?></a></li>
                                <li class="active"><span><?php echo e(trans('app.Recommendations')); ?></span></li>
                                <li><a href="<?php echo e(base_url('/user/payment')); ?>"><?php echo e(trans('app.Payment_and_delivery')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/contacts')); ?>"><?php echo e(trans('app.Contacts')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/logout')); ?>"><?php echo e(trans('app.Exit')); ?></a></li>
                            </ul>
                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title"><?php echo e(trans('app.Recommendations')); ?></span>
                                <span class="account-descr"><span><?php echo e(trans('app.recommend_descr')); ?></span></span>
                                <div class="wishlist">
                                    <div class="row wishlist-wrapper">
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo $__env->make('public.layouts.product', ['product' => $product, 'size' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>