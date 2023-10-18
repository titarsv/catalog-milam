<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Каталог товаров
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование товара</h1>

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

            <input type="hidden" name="prev" value="<?php echo e($prev); ?>">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="<?php echo e($prev); ?>" style="float: left;display: block;width: 15px;font-size: 18px;line-height: 34px;color: #f00;text-align: center;margin-right: 10px;"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'required' => true,
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображение товара</label>
                                <div class="form-element col-sm-10">
                                    <?php echo $__env->make('admin.layouts.form.gallery', [
                                     'key' => 'gallery',
                                     'gallery' => $product->gallery
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
                                     'gallery' => $product->documents
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Описание товара',
                         'key' => 'description',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Инструкция по применению',
                         'key' => 'instructions',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Меры безопасности',
                         'key' => 'security',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Состав',
                         'key' => 'compound',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Срок годности',
                         'key' => 'shelf_life',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Условия хранения',
                         'key' => 'storage_conditions',
                         'item' => $product
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                         
                         
                         
                        
                        
                         
                         
                         
                         
                        
                        
                         
                         
                         
                         
                        
                        
                            
                                
                                
                                    
                                        
                                            
                                        
                                        
                                            
                                                
                                                
                                            
                                            
                                                
                                                
                                            
                                            
                                                
                                                
                                            
                                        
                                    
                                
                            
                        
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Отображение на сайте',
                         'key' => 'visible',
                         'required' => true,
                         'options' => [(object)['id' => 0, 'name' => 'Скрыть'], (object)['id' => 1, 'name' => 'Отображать']],
                         'selected' => [old('visible', $product->visible)]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                         
                         
                         
                        
                        
                         
                         
                         
                         
                         
                        
                        
                         
                         
                         
                         
                        
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связи</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Категории товара',
                         'key' => 'product_category_id',
                         'multiple' => true,
                         'required' => true,
                         'options' => $categories,
                         'selected' => $added_categories
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Связанные товары',
                         'key' => 'related',
                         'required' => false,
                         'multiple' => true,
                         'options' => $sets,
                         'selected' => (array)old('related', $related)
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
                                            <?php if($user->hasAccess(['products.update'])): ?>
                                            <td align="center">Действия</td>
                                            <?php endif; ?>
                                        </tr>
                                        </thead>
                                        <tbody id="product-attributes">
                                        <?php if(old('product_attributes') !== null): ?>
                                            <?php if(session('attributes_error')): ?>
                                                <tr>
                                                    <td colspan="3">
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
                                                        <select class="form-control" name="product_attributes[<?php echo $key; ?>][value]">
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
                                                    <?php if($user->hasAccess(['products.update'])): ?>
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <input type="hidden" value="<?php echo $key; ?>" id="attributes-iterator" />
                                        <?php else: ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $product_attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td>
                                                        <select class="form-control" onchange="getAttributeValues($(this).val(), '<?php echo $key; ?>')">
                                                            <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo $attribute->id; ?>"
                                                                        <?php if($attribute->id == $attr['attribute_id']): ?>
                                                                        selected
                                                                        <?php endif; ?>
                                                                ><?php echo $attribute->name; ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </td>
                                                    <td align="center" id="attribute-<?php echo $key; ?>-values">
                                                        <input type="hidden" name="product_attributes[<?php echo $key; ?>][id]" value="<?php echo $attr['attribute_id']; ?>"/>
                                                        <select class="form-control" name="product_attributes[<?php echo $key; ?>][value]">
                                                            <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if($attribute->id == $attr['attribute_id']): ?>
                                                                    <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo $value->id; ?>"
                                                                                <?php if($value->id == $attr['attribute_value_id']): ?>
                                                                                selected
                                                                                <?php endif; ?>
                                                                        ><?php echo $value->name; ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </td>
                                                    <?php if($user->hasAccess(['products.update'])): ?>
                                                    <td align="center">
                                                        <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>

                                                <?php if($key == count($product->attributes) - 1): ?>
                                                    <input type="hidden" value="<?php echo $key; ?>" id="attributes-iterator" />
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <input type="hidden" value="0" id="attributes-iterator" />
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        </tbody>
                                        <?php if($user->hasAccess(['products.update'])): ?>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td align="center">
                                                <button type="button" id="add-attribute" onclick="getAttributes();" class="btn btn-primary">Добавить</button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                    
                        
                    
                    
                        
                            
                                
                                    
                                    
                                        
                                        
                                            
                                        
                                    
                                    
                                    
                                        
                                            
                                                
                                                
                                            
                                                
                                                
                                            
                                        
                                    
                                
                            

                            
                                
                                    
                                        
                                            
                                            
                                                
                                                
                                                
                                            
                                            
                                            
                                            
                                                
                                                
                                                    
                                                        
                                                            
                                                                
                                                                
                                                                        
                                                                        
                                                                        
                                                                
                                                                
                                                            
                                                        
                                                    
                                                    
                                                        
                                                        
                                                            
                                                                
                                                                    
                                                                        
                                                                                
                                                                                
                                                                                
                                                                        
                                                                    
                                                                
                                                            
                                                        
                                                    
                                                    
                                                        
                                                        
                                                        
                                                        
                                                            
                                                        
                                                    
                                                
                                            
                                                
                                                    
                                                        
                                                    
                                                
                                            
                                            
                                            
                                            
                                            
                                                
                                                
                                                    
                                                
                                            
                                            
                                            
                                        
                                    
                                
                            
                        
                        
                        
                            
                                
                                    
                                
                            
                        
                        
                    
                
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if($user->hasAccess(['products.update'])): ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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
            $('[type="submit"]').click(function(){
                console.log($(this).parents('form'));
                $(this).parents('form').submit();
            });

            jQuery.datetimepicker.setLocale('ru');
            $('.from, .to').datetimepicker({
                datepicker:true,
                step:30
            });

            $('.search-products').each(function(){
                var select = $(this);
                select.chosen({
                    placeholder_text_multiple: "Введите название товара",
                    no_results_text: "Ничего не найдено!"
                });
                var input = select.next().find('input');
                input.autocomplete({
                    source: function (request, response) {
                        $search_param = input.val();
                        var data = {
                            search: $search_param
                        };
                        if ($search_param.length > 3) { //отправлять поисковой запрос к базе, если введено более трёх символов
                            $.post('/admin/products/livesearch', data, function onAjaxSuccess(data) {
                                if (typeof data[0].empty !== 'undefined') {
                                    data = [];
                                }
                                if (data.length != 0) {
                                    select.next().find('ul.chosen-results').find('li').each(function () {
                                        $(this).remove();//отчищаем выпадающий список перед новым поиском
                                    });
                                    select.find('option').not(':selected').each(function () {
                                        $(this).remove(); //отчищаем поля перед новым поисков
                                    });
                                }
                                for (var id in data) {
                                    select.append('<option value="' + data[id].product_id + '">' + data[id].name + '</option>');
                                }
                                select.trigger("chosen:updated");
                                input.val($search_param);
                                anSelected = select.val();
                            });
                        }
                    }
                });
            });
        });
    </script>

    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>