<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "product",
  "logo": "<?php echo e(env('APP_URL')); ?>/images/logo.png"
  <?php if(!empty($title)): ?>
  ,"name": "<?php echo e($title); ?>"
  <?php endif; ?>
  <?php if(!empty($category->image)): ?>
  ,"image": "<?php echo e($category->image->url()); ?>"
  <?php endif; ?>
  ,"offers": {
    "@type": "AggregateOffer",
    "offerCount": "<?php echo e($total); ?>",
    "highPrice": "<?php echo e($category->max_price($category->id)); ?>",
    "lowPrice": "<?php echo e($category->min_price($category->id)); ?>",
    "priceCurrency": "UAH"
  }
}
</script>