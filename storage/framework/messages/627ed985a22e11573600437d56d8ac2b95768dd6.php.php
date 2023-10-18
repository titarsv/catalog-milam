<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Новый экспорт
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1>Новый экспорт</h1>

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
                                    <input type="text" data-translit="input" class="form-control" name="name" value="<?php echo old('name'); ?>" />
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
                                                    <?php if($type == old('type')): ?>
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
                                    <input type="text" class="form-control" name="url" value="<?php echo old('url'); ?>" />
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
                        <div class="form-group">
                            <div class="row export_field" data-id="0">
                                <div class="form-element col-sm-3">
                                    <input type="text" class="form-control" name="fields[0][name]" placeholder="Название" value="<?php echo old('fields[0][name]'); ?>" autocomplete="off" />
                                </div>
                                <div class="form-element col-sm-3">
                                    <select class="form-control field_type" name="fields[0][field][type]" autocomplete="off">
                                        <?php $__currentLoopData = $field_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field_type => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($field_type); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-element col-sm-4 modifications">
                                    <div class="modification" data-id="0">
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <select class="form-control" name="fields[0][modifications][0][type]" autocomplete="off">
                                                    <?php $__currentLoopData = $modifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $modification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($modification); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-3">
                                                <button type="button" class="btn btn-primary add_export_field_modification">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
                                                <div class="form-group" data-group-id="0">
                                                    <div class="row condition" data-id="0">
                                                        <div class="form-element col-sm-4">
                                                            <label class="text-right">Критерий фильтрации:</label>
                                                            <select name="filter[0][0][criterion]" class="form-control criterion" autocomplete="off">
                                                                <option value="" selected></option>
                                                                <option value="category">Категория</option>
                                                                <option value="attribute">Атрибут</option>
                                                                <option value="status">Наличие</option>
                                                                <option value="price">Цена</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-element col-sm-4">
                                                            <label class="text-right">Значение</label>

                                                        </div>
                                                        <div class="form-element col-sm-4">
                                                            <label class="text-right">Условие:</label>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 text-center buttons">
                                                            <button type="button" class="btn btn-primary add_sub_condition">Добавить условие</button>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
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
                                        <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($schedule); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
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

    <script>
        window.categories = <?php echo json_encode($categories); ?>;
        window.attributes = <?php echo json_encode($all_attributes); ?>;
        window.export = {
            field_types: <?php echo json_encode($field_types); ?>,
            modifications: <?php echo json_encode($modifications); ?>

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