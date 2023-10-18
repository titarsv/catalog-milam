<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Модули
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1><?php echo $module->name; ?></h1>

    <?php if(session('message-success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message-success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif(session('message-error')): ?>
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
                        <h4>Настройки модуля</h4>
                    </div>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Статус</label>
                                <div class="form-element col-sm-10">
                                    <select name="status" class="form-control">
                                        <?php if($module->status): ?>
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        <?php else: ?>
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Слайды</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table slideshow-images">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="success">
                                        <th align="center" class="col-md-2">Изображение</th>
                                        <th align="center" class="col-md-2">Ссылка/Кнопка</th>
                                        <th align="center" class="col-md-2">Порядок/Статус</th>
                                        <th align="center" class="col-md-3">Заголовок/Описание</th>
                                        <th align="center" class="col-md-1">Действия</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-table">
                                    <?php $__empty_1 = true; $__currentLoopData = $slideshow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php $data = $slide->data(); ?>
                                        <tr>
                                            <td class="col-md-2">
                                                <div class="image-container">
                                                    <input type="hidden" name="slide[<?php echo e($key); ?>][file_id]" value="<?php echo e($slide->file_id); ?>">
                                                    <div>
                                                        <div>
                                                            <i class="remove-image">-</i>
                                                            <?php if(!empty($slide->image)): ?>
                                                            <img src="<?php echo e($slide->image->url()); ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="upload_image_button" data-type="single" style="display: none;">
                                                        <div class="add-btn"></div>
                                                    </div>
                                                </div>
                                                
                                                    
                                                    
                                                        
                                                            
                                                            
                                                            
                                                            
                                                        
                                                    
                                                    
                                                        
                                                    
                                                
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Ссылка</b>
                                                    <input type="text" name="slide[<?php echo $key; ?>][link]" class="form-control" value="<?php echo $slide->link; ?>" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Текст кнопки</b>
                                                    <input type="text" name="slide[<?php echo $key; ?>][button_text]" class="form-control" value="<?php echo $data->button_text; ?>" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Отображать кнопку</b>
                                                    <select name="slide[<?php echo $key; ?>][enable_link]" class="form-control">
                                                        <?php if($slide->enable_link): ?>
                                                            <option value="1" selected>Да</option>
                                                            <option value="0">Нет</option>
                                                        <?php elseif(!$slide->enable_link): ?>
                                                            <option value="1">Да</option>
                                                            <option value="0" selected>Нет</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Порядок сортировки*</b>
                                                    <input type="text" name="slide[<?php echo $key; ?>][sort_order]" class="form-control" value="<?php echo $slide->sort_order; ?>" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Статус</b>
                                                    <select name="slide[<?php echo $key; ?>][status]" class="form-control">
                                                        <?php if($slide->status): ?>
                                                            <option value="1" selected>Отображать</option>
                                                            <option value="0">Скрыть</option>
                                                        <?php elseif(!$slide->status): ?>
                                                            <option value="1">Отображать</option>
                                                            <option value="0" selected>Скрыть</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Язык:</b>
                                                    <select name="slide[<?php echo $key; ?>][lang]" class="form-control">
                                                        <option value="ru"<?php echo e(isset($data->lang) && $data->lang == 'ru' ? ' selected' : ''); ?>>Русский</option>
                                                        <option value="ua"<?php echo e(isset($data->lang) && $data->lang == 'ua' ? ' selected' : ''); ?>>Українська</option>
                                                        
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div>
                                                    <b>Заголовок</b>
                                                    <input type="text" name="slide[<?php echo $key; ?>][slide_title]" class="form-control" value="<?php echo json_decode($slide->slide_data)->slide_title; ?>" />
                                                    <span style="color: red">
                                                        <?php if($errors->has('slide.' . $key . '.slide_title')): ?>
                                                            <?php echo e($errors->first('slide.' . $key . '.slide_title',':message')); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Описание</b>
                                                    <textarea type="text" name="slide[<?php echo $key; ?>][slide_description]" class="form-control"><?php echo $data->slide_description; ?></textarea>
                                                    <span style="color: red">
                                                        <?php if($errors->has('slide.' . $key . '.slide_description')): ?>
                                                            <?php echo e($errors->first('slide.' . $key . '.slide_description',':message')); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                
                                                
                                                    
                                                    
                                                    
                                                        
                                                            
                                                        
                                                    
                                                
                                            </td>
                                            <td class="col-md-1" align="center">
                                                <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                <?php if($key == count($slideshow) - 1): ?>
                                                    <input type="hidden" value="<?php echo $key; ?>" id="slideshow-iterator" />
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr class="empty">
                                            <td colspan="5" align="center">Нет добавленных слайдов!</td>
                                        </tr>
                                        <input type="hidden" value="0" id="slideshow-iterator" />
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td align="center"><button type="button" id="button-add-slide" class="btn btn-primary">Добавить слайд</button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if($user->hasAccess(['modules.update'])): ?>
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
        jQuery(document).ready(function($){
            $('#button-add-slide').on('click', function() {
                var iterator = $('#slideshow-iterator');
                var i = iterator.val();
                i++;

                var html = '<tr><td class="col-md-2">';
                html += '<div class="image-container">' +
                    '<input type="hidden" name="slide[' + i + '][file_id]" value="1">' +
                    '<div class="upload_image_button" data-type="single">' +
                    '<div class="add-btn"></div>' +
                    '</div>' +
                    '</div>';
                // html += '<div class="image-container">' +
                //     '<input type="hidden" name="slide[' + i + '][file_xs_id]" value="1">' +
                //     '<div class="upload_image_button" data-type="single">' +
                //     '<div class="add-btn"></div>' +
                //     '</div>' +
                //     '</div>';
                html += '</td><td class="col-md-2"><b>Ссылка</b>';
                html += '<input type="text" name="slide[' + i + '][link]" class="form-control" value="" />';
                html += '</div><br><div><b>Текст кнопки</b>';
                html += '<input type="text" name="slide[' + i + '][button_text]" class="form-control" value="" />';
                html += '</div><br><div><b>Отображать кнопку</b>';
                html += '<select name="slide[' + i + '][enable_link]" class="form-control">';
                html += '<option value="1" selected>Да</option><option value="0">Нет</option>';
                html += '</select></div></td>';
                html += '<td class="col-md-2"><div><b>Порядок сортировки*</b>';
                html += '<input type="text" name="slide[' + i + '][sort_order]" class="form-control" value="" />';
                html += '</div><br><div><b>Статус</b><select name="slide[' + i + '][status]" class="form-control">';
                html += '<option value="1" selected>Отображать</option><option value="0">Скрыть</option>';
                html += '</select></div>';
                html += '<br><div>\n' +
                    '<b>Язык:</b>\n' +
                    '<select name="slide[' + i + '][lang]" class="form-control">\n' +
                    '<option value="ru">Русский</option>\n' +
                    '<option value="ua">Українська</option>\n' +
                    '</select>\n' +
                    '</div>';
                html += '</td>';
                html += '<td class="col-md-2">';
                html += '<div><b>Заголовок</b>';
                html += '<input type="text" name="slide[' + i + '][slide_title]" class="form-control" value="" />';
                html += '<span style="color: red"></span></div><br>';
                html += '<div><b>Описание</b>';
                html += '<textarea name="slide[' + i + '][slide_description]" class="form-control"></textarea>';
                html += '<span style="color: red"></span></div>';
                // html += '<br><div><b>Цвет подложки</b>';
                // html += '<input type="text" name="slide[' + i + '][slide_color]" class="form-control" value="" />';
                // html += '<span style="color: red">';
                // html += '</span></div>';
                html += '</td>';
                html += '<td class="col-md-1" align="center">';
                html += '<button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>';
                html += '</td></tr>';

                if ($('#modules-table tr.empty').length) {
                    $('#modules-table tr.empty').remove();
                }
                $('#modules-table').append(html);
                iterator.val(i);
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>