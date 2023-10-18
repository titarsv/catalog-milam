<div class="panel panel-default">
    <div class="panel-heading">
        <h4>SEO</h4>
    </div>
    <div class="panel-body">
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Url',
         'required' => !empty($required_url) ? true : false,
         'key' => 'url',
         'item' => isset($seo) ? $seo : null,
         'languages' => null
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Название (H1)',
         'key' => 'seo_name',
         'item' => isset($seo) ? $seo : null,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Title',
         'key' => 'meta_title',
         'item' => isset($seo) ? $seo : null,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.text', [
         'label' => 'Meta description',
         'key' => 'meta_description',
         'item' => isset($seo) ? $seo : null,
         'class' => 'meta-descr'
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.editor', [
         'label' => 'СЕО ТЕКСТ',
         'key' => 'seo_description',
         'item' => isset($seo) ? $seo : null
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Meta keywords',
         'key' => 'meta_keywords',
         'item' => isset($seo) ? $seo : null,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Canonical',
         'key' => 'canonical',
         'item' => isset($seo) ? $seo : null,
         'languages' => null
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.layouts.form.string', [
         'label' => 'Robots',
         'key' => 'robots',
         'item' => isset($seo) ? $seo : null,
         'languages' => null
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/layouts/seo.blade.php ENDPATH**/ ?>