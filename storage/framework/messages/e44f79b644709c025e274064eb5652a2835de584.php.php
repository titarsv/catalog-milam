<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
  <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(!empty($breadcrumb->url)): ?>
      <?php echo e($i>0?',':''); ?>

      {
        "@type": "ListItem",
        "position": <?php echo e($i+1); ?>,
        "item":
        {
          "@id": "<?php echo e($breadcrumb->url); ?>",
          "@type": "Thing",
          "name": "<?php echo e($breadcrumb->title); ?>"
        }
      }
    <?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  ]
}
</script>