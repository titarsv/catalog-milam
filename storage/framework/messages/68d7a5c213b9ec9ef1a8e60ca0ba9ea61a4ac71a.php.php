
<?php $__env->startSection('meta'); ?>
    <title><?php echo e(__('Карта товаров')); ?></title>
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
     'title' => __('Карта товаров'),
     'description' => __('Карта товаров'),
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <main>
        <div class="container">
            <div class="sitemap products">
                <h1 class="sitemap__title"><?php echo e(__('ТОВАРЫ')); ?></h1>
                <a class="sitemap__top-link" href="<?php echo e(base_url('/sitemap')); ?>"><?php echo e(__('Карта категорий')); ?></a>
                <p class="amount"><span><?php echo e(__('Показано')); ?> <?php echo e(1 + $products->perPage() * ($products->currentPage() - 1)); ?>-<?php echo e($products->currentPage() == $products->lastPage() ? $products->total() : $products->perPage() * $products->currentPage()); ?> <?php echo e(__('из')); ?> <?php echo e($products->total()); ?></span></p>
                <?php echo $__env->make('public.layouts.pagination', ['paginator' => $products], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <ul class="sitemap">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e($product->link()); ?>"><?php echo e($product->name); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a class="sitemap__top-link" href="<?php echo e(base_url('/sitemap')); ?>"><?php echo e(__('Карта категорий')); ?></a>
                <p class="amount"><span><?php echo e(__('Показано')); ?> <?php echo e(1 + $products->perPage() * ($products->currentPage() - 1)); ?>-<?php echo e($products->currentPage() == $products->lastPage() ? $products->total() : $products->perPage() * $products->currentPage()); ?> <?php echo e(__('из')); ?> <?php echo e($products->total()); ?></span></p>
                <?php echo $__env->make('public.layouts.pagination', ['paginator' => $products], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </main>
    <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>