<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Шаблон страницы
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Шаблон страницы "<?php echo e($template->name); ?>.blade.php"</h1>

    <?php if(session('message-error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('message-error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>Поля</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="btn-group">
                                    <span class="btn btn-success" id="add_field" data-key="<?php echo e(count($template->fields)); ?>">Добавить поле</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" id="fields">
                        <?php $__currentLoopData = $template->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.pages.templates.field', ['parent' => "fields[$key]"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="hidden">
        <?php echo $__env->make('admin.pages.templates.field', ['field' => null, 'parent' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.pages.templates.fields.select', ['field' => null, 'parent' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('admin.pages.templates.fields.repeater', ['field' => null, 'parent' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <script>
        jQuery(document).ready(function($){
            $('#add_field').click(function(e){
                e.preventDefault();
                var $this = $(this);
                var field = $('.hidden > .field').clone();
                var key = $this.data('key');
                field.find('input, select').each(function(){
                    $(this).attr('name', 'fields['+key+']'+$(this).attr('name')).attr('data-parent', 'fields['+key+']');
                });
                $('#fields').append(field);
                $this.data('key', key + 1);
            });

            $(document).on('change', '.field .type', function(){
                var $this = $(this);
                if($.inArray($this.val(), ['select', 'repeater']) !== -1){
                    var field = $('.hidden > .panel.'+$this.val()).clone();
                    var parent = $this.data('parent');
                    field.find('input, textarea, select').each(function(){
                        $(this).attr('name', parent+$(this).attr('name'));
                    });
                    field.find('.add-field').attr('data-parent', parent);
                    $this.closest('.field').find('.params').html(field);
                }else{
                    $this.closest('.field').find('.params').html('');
                }
            });

            $(document).on('click', '.field .add-field', function(e){
                e.preventDefault();
                var $this = $(this);
                var field = $('.hidden > .field').clone();
                var key = $this.data('key');
                var parent = $this.data('parent');
                field.find('input, select').each(function(){
                    $(this).attr('name', parent+'[fields]['+key+']'+$(this).attr('name')).attr('data-parent', parent+'[fields]['+key+']');
                });
                $this.closest('.panel').children('.fields').append(field);
                $this.data('key', key + 1);
            });

            $(document).on('click', '.field .remove-field', function(e){
                e.preventDefault();
                var $this = $(this);
                $this.closest('.field').remove();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>