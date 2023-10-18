
<?php $__env->startSection('meta'); ?>
    <title><?php echo e(__('Карта категорий')); ?></title>
    <meta name="robots" content="noindex, follow, noarchive" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_vars'); ?>
    <?php if(!isset($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false): ?>
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1172440259605977');
            fbq('track', 'PageView');
        </script>
        <!-- End Facebook Pixel Code -->
    <?php endif; ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => __('Карта категорий'),
     'description' => __('Карта категорий'),
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <main>
        <div class="container">
            <div class="sitemap">
                <h1 class="sitemap__title"><?php echo e(__('СТРАНИЦЫ')); ?></h1>
                <a class="sitemap__top-link" href="<?php echo e(base_url('/sitemap-products')); ?>"><?php echo e(__('Карта товарного каталога')); ?></a>
                <ul class="sitemap__list top">
                    <li class="sitemap__item lvl-0"><a href="<?php echo e(rtrim(base_url('/'), '/')); ?>"><?php echo e(__('Главная')); ?></a></li>
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="sitemap__item lvl-0"><a href="<?php echo e($page->link()); ?>"><?php echo e($page->name); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <h1 class="sitemap__title"><?php echo e(__('КАТЕГОРИИ')); ?></h1>
                <p class="amount"><?php echo e(__('Показано')); ?> <?php echo e($categories_count); ?></p>
                <ul class="sitemap__list">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="sitemap__item lvl-0"><a href="<?php echo e($cat->link()); ?>"><?php echo e($cat->name); ?></a></li>
                        <?php $__currentLoopData = $cat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="sitemap__item lvl-1"><a href="<?php echo e($cat->link()); ?>/<?php echo e($attr->slug); ?>-<?php echo e($val->value); ?>"><?php echo e($cat->name); ?> <?php echo e($val->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <ul class="sitemap__list">
                    <?php $__currentLoopData = $categories[0]->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="sitemap__item lvl-1"><a href="<?php echo e($cat->link()); ?>"><?php echo e($cat->name); ?></a></li>
                        <?php $__currentLoopData = $cat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="sitemap__item lvl-2"><a href="<?php echo e($cat->link()); ?>/<?php echo e($attr->slug); ?>-<?php echo e($val->value); ?>"><?php echo e($cat->name); ?> <?php echo e($val->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($cat->children)): ?>
                            <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="sitemap__item lvl-2"><a href="<?php echo e($subcat->link()); ?>"><?php echo e($subcat->name); ?></a></li>
                                <?php $__currentLoopData = $subcat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="sitemap__item lvl-3"><a href="<?php echo e($subcat->link()); ?>/<?php echo e($attr->slug); ?>-<?php echo e($val->value); ?>"><?php echo e($subcat->name); ?> <?php echo e($val->name); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a class="sitemap__top-link" href="<?php echo e(base_url('/sitemap-products')); ?>"><?php echo e(__('Карта товарного каталога')); ?></a>
                <p class="amount"><?php echo e(__('Показано')); ?> <?php echo e($categories_count); ?></p>
            </div>
        </div>
    </main>
    <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>