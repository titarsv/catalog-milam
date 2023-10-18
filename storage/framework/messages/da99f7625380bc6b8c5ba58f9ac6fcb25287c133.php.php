

<?php $__env->startSection('breadcrumbs'); ?>
    <?php echo Breadcrumbs::render('history'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                <?php echo Breadcrumbs::render('history'); ?>

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
                                <li class="active"><span><?php echo e(trans('app.Order_history')); ?></span></li>
                                <li><a href="<?php echo e(base_url('/user/recommend')); ?>"><?php echo e(trans('app.Recommendations')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/payment')); ?>"><?php echo e(trans('app.Payment_and_delivery')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/contacts')); ?>"><?php echo e(trans('app.Contacts')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/logout')); ?>"><?php echo e(trans('app.Exit')); ?></a></li>
                            </ul>

                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title"><?php echo e(trans('app.Order_history')); ?></span>
                                <span class="account-descr"><?php echo e(trans('app.Choose_the_ordered_goods_and_add_them_back_to_the_cart')); ?></span>
                                <ul class="orders-history">
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <div class="orders-history__head">
                                                <div class="orders-history__main">
                                                    <div class="orders-history__num"><span><?php echo e(trans('app.Order')); ?>: </span>
                                                        <?php if(!empty($order->external_id)): ?>
                                                            #<?php echo e($order->external_id); ?>

                                                        <?php else: ?>
                                                            #0000<?php echo e($order->id); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if(!empty($order->status_id)): ?>
                                                    <div class="orders-history__status"><span><?php echo e(trans('app.Status')); ?>: </span><?php echo e($order->status->status); ?></div>
                                                    <?php endif; ?>
                                                    <?php
                                                        $ids = [];
                                                        foreach($order->getProducts() as $key => $product){
                                                            if(!is_null($product['product']) && $product['product']->stock){
                                                                $ids[] = $product['product_code'];
                                                            }
                                                        }
                                                    ?>
                                                    <a href="javascript:void(0)" class="orders-history__one-more order-again" data-id="<?php echo e(json_encode($ids)); ?>"><span><?php echo e(trans('app.order_again')); ?></span></a>
                                                </div>
                                                <div class="orders-history__date">
                                                    <strong><?php echo e(trans('app.Date_of_registration')); ?>:</strong>
                                                    <span><?php echo e($order->created_at); ?></span>
                                                </div>
                                                <div class="orders-history__sum">
                                                    <strong><?php echo e(trans('app.Sum')); ?>:</strong>
                                                    <span><?php echo e($order->total_price); ?> ₴</span>
                                                </div>
                                                <div class="orders-history__details">
                                                    <div class="orders-history__details-btn"><?php echo e(trans('app.DETAILS')); ?></div>
                                                </div>
                                            </div>
                                            <div class="orders-history__body">
                                                <div class="orders-history__items">
                                                    <?php $__currentLoopData = $order->getProducts(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!empty($product['product']) && is_object($product['product'])): ?>
                                                            <a href="javascript:void(0)" class="orders-history__item">
                                                                <?php echo $product['product']->image == null ? '<picture class="lazy-hidden">
        <source data-src="/images/larchik/no_image.webp" srcset="/images/pixel.webpwebp" type="image/webp">
        <source data-src="/images/larchik/no_image.jpg" srcset="/images/pixel.jpg" type="image/jpeg">
        <img src="/images/pixel.jpg" alt="'.$product['product']->name.' ">
        </picture>' : $product['product']->image->webp([92, 100], ['alt' => $product['product']->name]); ?>

                                                                <div>
                                                                    <div class="orders-history__item-top">
                                                                        <span class="orders-history__item-name"><?php echo e($product['product']->name); ?></span>
                                                                        <?php if(!empty($product['variations'])): ?>
                                                                            <?php $__currentLoopData = $product['variations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <small class="orders-history__item-vol"><?php echo e($val); ?></small>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="orders-history__item-bot">
                                                                        <p class="orders-history__item-price"><?php echo e($product['price']); ?> грн</p>
                                                                        <?php if($product['product']->stock): ?>
                                                                        <span class="orders-history__item-more order-again" data-id="[<?php echo e($product['product_code']); ?>]"><?php echo e(trans('app.order_again')); ?></span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        <?php else: ?>
                                                            <div class="orders-history__item">
                                                                <p><?php echo e(trans('app.Product_is_no_longer_available')); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                            <div class="orders-history__details hidden-details">
                                                <div class="orders-history__details-btn"><?php echo e(trans('app.DETAILS')); ?></div>
                                                <a href="javascript:void(0);" class="orders-history__one-more order-again" data-id="[<?php echo e($product['product_code']); ?>]"><span><?php echo e(trans('app.order_again')); ?></span></a>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>