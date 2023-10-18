<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e($export->name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1><?php echo e($export->name); ?></h1>

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
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Название</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" data-translit="input" class="form-control" name="name" value="<?php echo old('name') ? old('name') : $export->name; ?>" />
                                    <?php if($errors->has('name')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('name',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тип</label>
                                <div class="form-element col-sm-10">
                                    <select name="type" class="form-control">
                                        <?php $__currentLoopData = ['csv', 'xls', 'xml', 'rss', 'json']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type); ?>"
                                                    <?php if($type == (old('type') ? old('type') : $export->type)): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo e($type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Url</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="url" value="<?php echo old('url') ? old('url') : $export->url; ?>" />
                                    <?php if($errors->has('url')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('url',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Экспортируемые поля</h4>
                    </div>
                    <div class="panel-body" id="export_fields">
                        <div class="form-group">
                            <div class="row">
                                <div class="form-element col-sm-3">
                                    <label for="">Название</label>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label for="">Поле</label>
                                </div>
                                <div class="form-element col-sm-3">
                                    <label for="">Модификаторы</label>
                                </div>
                            </div>
                        </div>
                        <?php $__currentLoopData = $export->structure; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fi => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <div class="row export_field" data-id="<?php echo e($fi); ?>">
                                    <div class="form-element col-sm-3">
                                        <input type="text" class="form-control" name="fields[<?php echo e($fi); ?>][name]" placeholder="Название" value="<?php echo old('fields['.$fi.'][name]') ? old('fields['.$fi.'][name]') : $field->name; ?>" autocomplete="off" />
                                    </div>
                                    <div class="form-element col-sm-3">
                                        <select class="form-control field_type" name="fields[<?php echo e($fi); ?>][field][type]" autocomplete="off">
                                            <?php $__currentLoopData = $export->getFieldTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field_type => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($field_type); ?>"<?php echo e($field->field->type == $field_type ? ' selected' : ''); ?>><?php echo e($name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if(!empty($field->field->custom)): ?>
                                            <input type="text" class="form-control" name="fields[<?php echo e($fi); ?>][field][custom]" placeholder="Введите своё значение" value="<?php echo e($field->field->custom); ?>">
                                        <?php elseif(!empty($field->field->attribute)): ?>
                                            <select class="form-control" name="fields[<?php echo e($fi); ?>][field][attribute]">
                                                <?php $__currentLoopData = $all_attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($attr['id']); ?>"<?php echo e($attr['id'] == $field->field->attribute ? ' selected' : ''); ?>><?php echo e($attr['name']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-element col-sm-4 modifications">
                                        <?php $__currentLoopData = $field->modifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $field_modification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($field_modification->type)): ?>
                                                <div class="modification">
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <select class="form-control" name="fields[<?php echo e($fi); ?>][modifications][<?php echo e($i); ?>][type]" autocomplete="off">
                                                                <?php $__currentLoopData = $export->getModifications(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $modification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($key); ?>"<?php echo e($field_modification->type == $key ? ' selected' : ''); ?>><?php echo e($modification); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php if(isset($field_modification->value)): ?>
                                                                <input type="text" class="form-control" name="fields[<?php echo e($fi); ?>][modifications][<?php echo e($i); ?>][value]" placeholder="Введите значение" value="<?php echo e($field_modification->value); ?>">
                                                            <?php elseif(isset($field_modification->from) && isset($field_modification->to)): ?>
                                                                <input type="text" class="form-control" name="fields[<?php echo e($fi); ?>][modifications][<?php echo e($i); ?>][from]" placeholder="Что заменить" value="<?php echo e($field_modification->from); ?>">
                                                                <input type="text" class="form-control" name="fields[<?php echo e($fi); ?>][modifications][<?php echo e($i); ?>][to]" placeholder="Чем заменить" value="<?php echo e($field_modification->to); ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <?php if($i == 0): ?>
                                                                <button type="button" class="btn btn-primary add_export_field_modification">
                                                                    <i class="glyphicon glyphicon-plus"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-primary remove_export_field_modification">
                                                                    <i class="glyphicon glyphicon-minus"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="modification" data-id="0">
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <select class="form-control" name="fields[<?php echo e($fi); ?>][modifications][0][type]">
                                                                <option value=""></option>
                                                                <option value="replace_all">Замена всего содержимого</option>
                                                                <option value="replace_part">Замена части содержимого</option>
                                                                <option value="add_prefix">Добавление перед</option>
                                                                <option value="add_suffix">Добавление после</option>
                                                                <option value="add_num">Увеличение на</option>
                                                                <option value="multiple">Увеличение в</option>
                                                                <option value="translit">Транслит</option>
                                                                <option value="strip_tags">Удалить HTML</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <button type="button" class="btn btn-primary add_export_field_modification">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="form-element col-sm-1 text-right">

                                    </div>
                                    <div class="form-element col-sm-1 text-right">
                                        <button type="button" class="btn btn-danger remove_export_field">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="add_export_field">Добавить поле</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Фильтр товаров</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="filter-popup">
                                    <div id="filter-form">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <?php $__currentLoopData = $export->filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ig => $filter_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-group" data-group-id="<?php echo e($ig); ?>">
                                                        <?php $__currentLoopData = $filter_group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $if => $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="row condition" data-id="<?php echo e($if); ?>">
                                                                <div class="form-element <?php echo e($filter->criterion == 'status' ? 'col-sm-6' : 'col-sm-4'); ?>">
                                                                    <label class="text-right">Критерий фильтрации:</label>
                                                                    <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][criterion]" class="form-control criterion" autocomplete="off">
                                                                        <?php $__currentLoopData = [""=>"","category"=>"Категория","attribute"=>"Атрибут","status"=>"Наличие","price"=>"Цена"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($key); ?>"<?php echo e($key == $filter->criterion ? ' selected' : ''); ?>><?php echo e($val); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                                <?php if($filter->criterion == 'category'): ?>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][value]" class="form-control criterion">
                                                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($category->id); ?>"<?php echo e($filter->value == $category->id ? ' selected' : ''); ?>><?php echo e($category->name); ?></option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][condition]" class="form-control criterion">
                                                                            <option value="with_child"<?php echo e($filter->condition == 0 ? ' selected' : ''); ?>>Включая дочерние</option>
                                                                            <option value="without_child"<?php echo e($filter->condition == 0 ? ' selected' : ''); ?>>Без дочерних</option>
                                                                        </select>
                                                                    </div>
                                                                <?php elseif($filter->criterion == 'attribute'): ?>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Атрибут:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][attribute]" class="form-control criterion">
                                                                            <?php $__currentLoopData = $all_attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($attribute->id); ?>"<?php echo e($filter->attribute == $attribute->id ? ' selected' : ''); ?>><?php echo e($attribute->name); ?></option>
                                                                                <?php
                                                                                    if($filter->attribute == $attribute->id){
                                                                                        $current_attribute = $filter->attribute;
                                                                                    }
                                                                                ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                if(!isset($current_attribute)){
                                                                                    $current_attribute = $all_attributes[0];
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][value]" class="form-control criterion">
                                                                            <?php $__currentLoopData = $current_attribute['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($value['id']); ?>"<?php echo e($filter->value == $value['id'] ? ' selected' : ''); ?>><?php echo e($value['name']); ?></option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </div>
                                                                <?php elseif($filter->criterion == 'status'): ?>
                                                                    <div class="form-element col-sm-6">
                                                                        <label class="text-right">Значение:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][value]" class="form-control criterion">
                                                                            <option value="0"<?php echo e($filter->value == 0 ? ' selected' : ''); ?>>Нет в наличии</option>
                                                                            <option value="1"<?php echo e($filter->value == 1 ? ' selected' : ''); ?>>В наличии</option>
                                                                        </select>
                                                                    </div>
                                                                <?php elseif($filter->criterion == 'price'): ?>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                        <select name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][condition]" class="form-control criterion">
                                                                            <option value="="<?php echo e($filter->condition == '=' ? ' selected' : ''); ?>>Равна</option>
                                                                            <option value=">"<?php echo e($filter->condition == '>' ? ' selected' : ''); ?>>Больше</option>
                                                                            <option value="<"<?php echo e($filter->condition == '<' ? ' selected' : ''); ?>>Меньше</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение</label>
                                                                        <input type="number" name="filter[<?php echo e($ig); ?>][<?php echo e($if); ?>][value]" step="0.01" class="form-control value" value="<?php echo e($filter->value); ?>">
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Значение</label>

                                                                    </div>
                                                                    <div class="form-element col-sm-4">
                                                                        <label class="text-right">Условие:</label>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="row">
                                                            <div class="col-sm-12 text-center buttons">
                                                                <button type="button" class="btn btn-primary add_sub_condition">Добавить условие</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                
                                                <button type="button" class="btn btn-primary" id="add_condition_group">Добавить группу условий</button>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Частота обновления</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Частота обновления</label>
                                <div class="form-element col-sm-10">
                                    <select class="form-control field_type" name="schedule" autocomplete="off">
                                        <option value="">не обновлять</option>
                                        <?php $__currentLoopData = $export->getSchedulesNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($schedule); ?>"<?php echo e(isset($export->schedule->method) && $schedule == $export->schedule->method ? ' selected' : ''); ?>><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($user->hasAccess(['export.update'])): ?>
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

    <script>
        window.categories = <?php echo json_encode($categories); ?>;
        window.attributes = <?php echo json_encode($all_attributes); ?>;
        window.export = {
            field_types: <?php echo json_encode($export->getFieldTypes()); ?>,
            modifications: <?php echo json_encode($export->getModifications()); ?>

        };
        jQuery(document).ready(function(){
            $('#add_export_field').click(function(){
                if($('.export_field').length){
                    var field_id = $('.export_field').last().data('id') + 1;
                }else{
                    var field_id = 0;
                }

                $(this).parents('.form-group').before('<div class="form-group">\n' +
                    '    <div class="row export_field" data-id="'+field_id+'">\n' +
                    '        <div class="form-element col-sm-3">\n' +
                    '            <input type="text" class="form-control" name="fields['+field_id+'][name]" placeholder="Название" value="" />\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-3">\n' +
                    '            <select class="form-control field_type" name="fields['+field_id+'][field][type]">\n' +
                    field_type_options() +
                    '            </select>\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-4 modifications">\n' +
                    '            <div class="modification" data-id="0">\n' +
                    '               <div class="row">\n' +
                    '                   <div class="col-xs-9">\n' +
                    '                       <select class="form-control" name="fields['+field_id+'][modifications][0][type]">\n' +
                    modifications_options() +
                    '                       </select>\n' +
                    '                   </div>\n' +
                    '                   <div class="col-xs-3">\n' +
                    '                       <button type="button" class="btn btn-primary add_export_field_modification">\n' +
                    '                           <i class="glyphicon glyphicon-plus"></i>\n' +
                    '                       </button>\n' +
                    '                   </div>\n' +
                    '               </div>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-1 text-right">\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-1 text-right">\n' +
                    '            <button type="button" class="btn btn-danger remove_export_field">\n' +
                    '                <i class="glyphicon glyphicon-trash"></i>\n' +
                    '            </button>\n' +
                    '        </div>\n' +
                    '    </div>\n' +
                    '</div>');
            });
            $('#export_fields').on('click', '.add_export_field_modification', function(){
                let modifications = $(this).parents('.export_field').find('.modifications');
                let field_id = $(this).parents('.export_field').data('id');
                let modification_id = modifications.find('.modification').length;
                modifications.append('<div class="modification" data-id="'+modification_id+'">\n' +
                    '<div class="row">\n' +
                    '<div class="col-xs-9">\n' +
                    '    <select class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][type]">\n' +
                    modifications_options() +
                    '    </select>\n' +
                    '</div>\n' +
                    '<div class="col-xs-3">\n' +
                    '   <button type="button" class="btn btn-primary remove_export_field_modification">\n' +
                    '       <i class="glyphicon glyphicon-minus"></i>\n' +
                    '   </button>\n' +
                    '</div>\n' +
                    '</div>\n' +
                    '</div>');
            });
            function field_type_options() {
                let html = '';
                for(var value in window.export.field_types){
                    html += '<option value="'+value+'">'+window.export.field_types[value]+'</option>\n';
                }
                return html;
            }
            function modifications_options() {
                let html = '';
                for(var value in window.export.modifications){
                    html += '<option value="'+value+'">'+window.export.modifications[value]+'</option>\n';
                }
                return html;
            }
            $('#export_fields').on('click', '.remove_export_field_modification', function(){
                $(this).parents('.modification').remove();
            });
            $('#export_fields').on('change', '.field_type', function(){
                let field_id = $(this).parents('.export_field').data('id');
                if($(this).val() == 'custom') {
                    $(this).next().remove();
                    $(this).after('<input type="text" class="form-control" name="fields[' + field_id + '][field][custom]" placeholder="Введите своё значение" value="">');
                }else if($(this).val() == 'product.attribute'){
                    $(this).next().remove();
                    let select = '<select class="form-control" name="fields[' + field_id + '][field][attribute]">';
                    for(let attr in window.attributes) {
                        select += '<option value="'+window.attributes[attr].id+'"'+(attr?'':' selected')+'>'+window.attributes[attr].name+'</option>\n';
                    }
                    select += '</select>';
                    $(this).after(select);
                }else{
                    $(this).next().remove();
                }
            });
            $('#export_fields').on('change', '.modification select', function(){
                let field_id = $(this).parents('.export_field').data('id');
                let modification = $(this).parents('.modification');
                let modification_id = modification.data('id');
                let val = $(this).val();
                modification.find('input').remove();
                if(val == 'replace_all' || val == 'replace_part'){
                    modification.find('.col-xs-9').append('<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][from]" placeholder="Что заменить" value="">' +
                        '<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][to]" placeholder="Чем заменить" value="">');
                }else if(val == 'add_prefix' || val == 'add_suffix' || val == 'add_num' || val == 'multiple'){
                    modification.find('.col-xs-9').append('<input type="text" class="form-control" name="fields['+field_id+'][modifications]['+modification_id+'][value]" placeholder="Введите значение" value="">');
                }
            });
            $('#export_fields').on('click', '.remove_export_field', function(){
                $(this).parents('.form-group').remove();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>