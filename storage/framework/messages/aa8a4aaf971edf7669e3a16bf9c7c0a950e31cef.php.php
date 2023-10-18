<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Библиотека файлов
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="upload-php">
    <div class="wrap" id="wp-media-grid" data-search="">
        <h1 class="wp-heading-inline">Библиотека файлов</h1>

        <?php if($user->hasAccess(['media.create'])): ?>
        <a href="/admin/media-new" class="page-title-action aria-button-if-js" role="button"
           aria-expanded="false">Добавить новый</a>
        <?php endif; ?>
        <hr class="wp-header-end">
        <ul class="subsubsub">
            <li class="all"><a href="/admin/media"<?php echo empty($trash) ? ' class="current"' : ''; ?> aria-current="page">Опубликованные <span class="count">(<?php echo e($active); ?>)</span></a> |</li>
            <li class="trash"><a href="/admin/media/trash"<?php echo !empty($trash) ? ' class="current"' : ''; ?>>Удалённые <span class="count">(<?php echo e($trashed); ?>)</span></a></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.media.assets', ['query_vars' => ['trash' => $is_trash]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('after_footer'); ?>
    
    
    
    
    
    
    <script type='text/javascript' src='/js/larchik/media-grid.js'></script>
    <script type='text/javascript' src='/js/larchik/media.js'></script>
    <script type='text/javascript' src='/js/larchik/svg-painter.js'></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>