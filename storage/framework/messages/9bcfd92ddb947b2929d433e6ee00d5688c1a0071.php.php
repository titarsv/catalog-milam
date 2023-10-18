<meta property="og:locale" content="ru_RU" />
<meta name="twitter:card" content="summary">
<meta name="twitter:creator" content="makeupforever.com@gmail.com">
<?php if(!empty($title)): ?>
<meta property="og:title" content="<?php echo e($title); ?>" />
<meta name="twitter:title" content="<?php echo e($title); ?>">
<?php endif; ?>
<?php if(!empty($description)): ?>
    <meta property="og:description" content="<?php echo e($description); ?>" />
    <meta name="twitter:description" content="<?php echo e($description); ?>">
<?php endif; ?>
<meta property="og:site_name" content="Milam" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo e(env('APP_URL')); ?>/<?php echo e(Request::path()); ?>" />
<?php if(!empty($image)): ?>
    <meta property="og:image" content="<?php echo e($image); ?>" />
    <meta name="twitter:image" content="<?php echo e($image); ?>">
<?php endif; ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/microdata/open_graph.blade.php ENDPATH**/ ?>