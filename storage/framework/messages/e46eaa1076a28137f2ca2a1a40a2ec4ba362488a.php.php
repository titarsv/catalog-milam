<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "product",
  <?php if(!empty($product->brand)): ?>
  "brand": {
    "@type": "Brand",
    "name": "<?php echo e($product->brand->name); ?>"
  },
  <?php endif; ?>
  "logo": "<?php echo e(env('APP_URL')); ?>/images/logo.png",
  "name": "<?php echo e($product->name); ?>",
  "sku": "<?php echo e($product->sku); ?>",
  <?php if(!empty($product->gtin)): ?>
  "gtin": "<?php echo e($product->gtin); ?>",
  <?php endif; ?>
  <?php if(!empty($category = $product->main_category())): ?>
  "category": "<?php echo e($category->name); ?>",
  <?php endif; ?>
  <?php if(!empty($product->image)): ?>
  "image": "<?php echo e($product->image->url()); ?>",
  <?php endif; ?>
  "description": "<?php echo e(empty($product->description) ? $product->name : strip_tags($product->description)); ?>",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "UAH",
    "price": "<?php echo e($product->price); ?>",
    "priceValidUntil": "<?php echo e(date('Y-m-d', time() + 86400 * 30)); ?>",
    "itemCondition": "http://schema.org/UsedCondition",
    "availability": "http://schema.org/InStock",
    "url": "<?php echo e(env('APP_URL')); ?>/product/<?php echo e($product->url_alias); ?>",
    "seller": {
      "@type": "Organization",
      "name": "Milam"
    }
  }
  <?php if(isset($reviews)): ?>
    <?php
        $bestRating = 0;
        $sumRating = 0;
        $reviewCount = 0;
        foreach($reviews as $review){
            if($review->grade > $bestRating){
                $bestRating = $review->grade;
            }
            $sumRating += $review->grade;
            $reviewCount++;
        }
    ?>
  <?php if($reviewCount > 0): ?>
  ,
  "aggregateRating": {
    "@type": "aggregateRating",
    "worstRating": "1",
    "ratingValue": "<?php echo e(round($sumRating/$reviewCount, 2)); ?>",
    "bestRating": "<?php echo e($bestRating); ?>",
    "reviewCount": "<?php echo e($reviewCount); ?>"
  }
  <?php else: ?>
  ,
  "aggregateRating": {
    "@type": "aggregateRating",
    "worstRating": "1",
    "ratingValue": "4.9",
    "bestRating": "5",
    "reviewCount": "48"
  }
  <?php endif; ?>
<?php endif; ?>
}
</script><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/microdata/product.blade.php ENDPATH**/ ?>