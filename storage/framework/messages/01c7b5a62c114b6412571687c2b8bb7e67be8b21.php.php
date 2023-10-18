
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main">
        <?php echo Breadcrumbs::render('page', $page); ?>

        <div class="section about-section">
            <div class="section-title">
                <div><?php echo e($seo->name); ?></div>
            </div>
            <div class="container">
                <div class="about-item row">
                    <div class="col-md-7">
                        <div class="about-text">
                            <span><?php echo e($fields['screen_1_title']); ?></span>
                            <?php echo $fields['screen_1_text']; ?>

                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="about-pic">
                            <?php echo $fields['screen_1_image']['image']->webp([890, 800], [], 'static'); ?>

                        </div>
                    </div>
                </div>
                <div class="about-item row">
                    <div class="col-md-5">
                        <div class="about-pic">
                            <?php echo $fields['screen_2_image_1']['image']->webp([658, 550], ['picture_class' => 'about-pic-logo-milam'], 'static'); ?>

                            <?php echo $fields['screen_2_image_2']['image']->webp([890, 800], [], 'static'); ?>

                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="about-text">
                            <span><?php echo e($fields['screen_2_title']); ?></span>
                            <?php echo $fields['screen_2_text']; ?>

                        </div>
                    </div>
                </div>
                <div class="about-item row">
                    <div class="col-md-7">
                        <div class="about-text">
                            <span><?php echo e($fields['screen_3_title']); ?></span>
                            <?php echo $fields['screen_3_text']; ?>

                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="about-pic">
                            <?php echo $fields['screen_3_image_1']['image']->webp([743, 352], ['picture_class' => 'about-pic-logo-milam-chemical'], 'static'); ?>

                            <?php echo $fields['screen_3_image_2']['image']->webp([890, 800], [], 'static'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/pages/about.blade.php ENDPATH**/ ?>