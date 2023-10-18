<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Акции
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Создание акции</h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'required' => true
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                        
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Описание',
                         'key' => 'body'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Превью</label>
                                <div class="form-element col-sm-2">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'preview_id'
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                
                                
                                    
                                     
                                    
                                
                                
                                
                                    
                                     
                                    
                                
                            </div>
                        </div>
                        
                         
                         
                         
                        
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время начала</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control from" name="show_from" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время окончания</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control to" name="show_to" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : 0]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

    <link rel="stylesheet" href="/css/larchik/jquery.datetimepicker.min.css" />
    <script src="/js/larchik/jquery.datetimepicker.full.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            jQuery.datetimepicker.setLocale('ru');
            $('.from, .to').datetimepicker({
                datepicker:true,
                step:30
            });
        });
    </script>

    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>