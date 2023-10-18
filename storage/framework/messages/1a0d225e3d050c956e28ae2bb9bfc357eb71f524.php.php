<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "<?php echo e($settings->ld_type); ?>",
    "name": "<?php echo e($settings->ld_name); ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo e($settings->ld_street); ?>",
        "addressLocality": "<?php echo e($settings->ld_city); ?>",
        "addressRegion": "<?php echo e($settings->ld_region); ?>",
        "postalCode": "<?php echo e($settings->ld_postcode); ?>"
    },
    
    "image": "<?php echo e(env('APP_URL')); ?>/images/logo.png",
    "telePhone": "<?php echo e($settings->ld_phone); ?>",
    "url": "<?php echo e(str_replace(['http://', 'https://'], '', env('APP_URL'))); ?>",
    "paymentAccepted": [
        <?php $__currentLoopData = $settings->ld_payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        "<?php echo e($payment); ?>"<?php echo e($i+1<count($settings->ld_payments) ? ',' : ''); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "sameAs": [
    <?php $__currentLoopData = $settings->social; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!empty($social)): ?>
        "<?php echo e($social); ?>"<?php echo e($i+1<count($settings->social) ? ',' : ''); ?>

        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    <?php
        $openingHours = [];
        foreach($settings->ld_opening_hours as $day => $hours){
            if(!empty($hours->trigger)){
                $openingHours[$hours->hours_from.':'.$hours->minutes_from.'-'.$hours->hours_to.':'.$hours->minutes_to][] = $day;
            }
        }
        $i = 1;
    ?>
    "openingHours": [
       <?php $__currentLoopData = $openingHours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time => $days): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        "<?php echo e(implode(',', $days)); ?> <?php echo e($time); ?>"<?php echo e($i++<count($openingHours) ? ',' : ''); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "<?php echo e($settings->ld_latitude); ?>",
        "longitude": "<?php echo e($settings->ld_longitude); ?>"
    },
    "priceRange":"$$$"
}
</script>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "url": "<?php echo e(str_replace(['http://', 'https://'], '', env('APP_URL'))); ?>",
    "potentialAction": [{
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "<?php echo e(env('APP_URL')); ?>/search?text={text}"
        },
        "query-input": "required name=text"
    }]
}
</script>