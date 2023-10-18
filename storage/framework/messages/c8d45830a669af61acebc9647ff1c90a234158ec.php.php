<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Каталог товаров
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Добавление товара</h1>

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
                         'locale' => 'ru',
                         'required' => true
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображения товара</label>
                                <div class="form-element col-sm-10">
                                    <?php echo $__env->make('admin.layouts.form.gallery', [
                                     'key' => 'gallery',
                                     'gallery' => null
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Документы / Сертификаты</label>
                                <div class="form-element col-sm-10">
                                    <?php echo $__env->make('admin.layouts.form.gallery', [
                                     'key' => 'documents',
                                     'gallery' => null
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Описание товара',
                         'key' => 'description'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Инструкция по применению',
                         'key' => 'instructions'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Меры безопасности',
                         'key' => 'security'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Состав',
                         'key' => 'compound'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Срок годности',
                         'key' => 'shelf_life'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Условия хранения',
                         'key' => 'storage_conditions'
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                         
                         
                        
                        
                         
                         
                         
                        
                        
                         
                         
                         
                        
                        
                            
                                
                                
                                    
                                        
                                            
                                        
                                        
                                            
                                                
                                                
                                            
                                            
                                                
                                                
                                            
                                            
                                                
                                                
                                            
                                        
                                    
                                
                            
                        
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Отображение на сайте',
                         'key' => 'visible',
                         'required' => true,
                         'options' => [(object)['id' => 0, 'name' => 'Скрыть'], (object)['id' => 1, 'name' => 'Отображать']],
                         'selected' => [1],
                         'languages' => null
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                         
                         
                         
                        
                        
                         
                         
                         
                         
                        
                        
                         
                         
                         
                        
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связи</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Категория товара',
                         'key' => 'product_category_id',
                         'options' => $categories,
                         'multiple' => true,
                         'selected' => old('parent_id') ? old('parent_id') : []
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Атрибуты товара</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="table table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="success">
                                                <td align="center">Выберите атрибут</td>
                                                <td align="center">Выберите значение атрибута</td>
                                                <td align="center">Действия</td>
                                            </tr>
                                        </thead>
                                        <tbody id="product-attributes">
                                            <?php if(old('product_attributes') !== null): ?>
                                                <?php if(session('attributes_error')): ?>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="warning" role="alert"><?php echo session('attributes_error'); ?></p>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php $__currentLoopData = old('product_attributes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" onchange="getAttributeValues($(this).val(), '<?php echo $key; ?>')">
                                                                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo $attribute->id; ?>"
                                                                        <?php if($attribute->id == $attr['id']): ?>
                                                                            selected
                                                                        <?php endif; ?>
                                                                    ><?php echo $attribute->name; ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </td>
                                                        <td align="center" id="attribute-<?php echo $key; ?>-values">
                                                            <input type="hidden" name="product_attributes[<?php echo $key; ?>][id]" value="<?php echo $attr['id']; ?>"/>
                                                            <select class="form-control" name="product_attributes[<?php echo $key; ?>][value]">';
                                                                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if($attribute->id == $attr['id']): ?>
                                                                        <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo $value->id; ?>"
                                                                                <?php if($value->id == $attr['value']): ?>
                                                                                    selected
                                                                                <?php endif; ?>
                                                                            ><?php echo $value->name; ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </td>
                                                        <td align="center">
                                                            <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="hidden" value="<?php echo $key; ?>" id="attributes-iterator" />
                                            <?php else: ?>
                                                <input type="hidden" value="0" id="attributes-iterator" />
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td align="center">
                                                    <button type="button" id="add-attribute" onclick="getAttributes();" class="btn btn-primary">Добавить</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    <style>
        .schedule{
            display: flex;
        }
        .schedule > .input-group{
            margin-bottom: 0;
        }
        .save-panel{
            position: fixed;
            bottom: 0;
            width: calc(83vw - 15px);
            right: 0;
            margin: 0 !important;
            padding-left: 25px;
            padding-right: 25px;
            z-index: 10;
            opacity: 0.75;
            transition: opacity .2s ease-in;
        }
        .save-panel:hover{
            opacity: 1;
        }
        .save-panel .panel-body{
            padding: 10px 15px;
        }
        .image-container > div > div > span{
            display: block;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            background-color: #2d3e50;
            color: #fff;
            z-index: 5;
        }
    </style>
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

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/products/create.blade.php ENDPATH**/ ?>