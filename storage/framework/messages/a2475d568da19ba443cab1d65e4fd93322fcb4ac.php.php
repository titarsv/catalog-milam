<!DOCTYPE html>
<html lang="<?php echo e(App::getLocale() == 'ua' ? 'uk' : 'ru'); ?>" prefix="og: http://ogp.me/ns#">
<?php echo $__env->make('public.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$value = '';
if (!empty($settings->{'site_message_'.App::getLocale()})){
    $value = 'alert-message';
}
else if(!empty($somethingelse)){
    $value = '';
}
?>

<body class="<?php echo e(Request::path()=='/' ? ' home' : ''); ?> <?php echo $value; ?>" id="top">
<?php if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false): ?>
<?php if(!empty(env('GOOGLE_ADS'))): ?>
<script>
    /* <![CDATA[ */
    var google_conversion_id = <?php echo e(env('GOOGLE_ADS')); ?>;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script>
    if(typeof window.google_tag_params !== 'undefined' && typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'remarketingTriggered',
            'google_tag_params': window.google_tag_params
        });
    }
</script>
<script src="//www.googleadservices.com/pagead/conversion.js"></script>
<?php endif; ?>
<!-- Google Tag Manager (noscript) -->
<?php echo $settings->gtm_noscript; ?>

<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
<?php echo $__env->make('public.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->make('public.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('public.layouts.footer-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/main.blade.php ENDPATH**/ ?>