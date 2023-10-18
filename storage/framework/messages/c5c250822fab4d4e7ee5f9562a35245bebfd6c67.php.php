
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main class="main">
    <div class="container">
        <div class="policy">
            <h1><?php echo e(!empty($seo->name) ? $seo->name : $page->name); ?></h1>
            <?php echo html_entity_decode($page->body); ?>

        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>