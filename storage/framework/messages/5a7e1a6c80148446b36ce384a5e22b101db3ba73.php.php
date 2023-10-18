<div class="row field">
    <label class="col-sm-1 text-right control-label">Опции</label>
    <div class="form-element col-sm-11">
        <div class="row">
            <div class="col-xs-11">
                <div class="row">
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="<?php echo e(isset($parent) ? $parent : ''); ?>[name]" value="<?php echo e(!empty($field->name) ? $field->name : ''); ?>" placeholder="Название поля" />
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="<?php echo e(isset($parent) ? $parent : ''); ?>[slug]" value="<?php echo e(!empty($field->slug) ? $field->slug : ''); ?>" placeholder="Слаг поля" />
                    </div>
                    <div class="col-xs-4">
                        <select name="<?php echo e(isset($parent) ? $parent : ''); ?>[type]" class="form-control type" autocomplete="off" data-parent="<?php echo e(isset($parent) ? $parent : ''); ?>">
                            <option value="">Тип поля</option>
                            <optgroup label="Основное">
                                <option value="text"<?php echo e(!empty($field->type) && $field->type == 'text' ? ' selected' : ''); ?>>Текст</option>
                                <option value="textarea"<?php echo e(!empty($field->type) && $field->type == 'textarea' ? ' selected' : ''); ?>>Область текста</option>
                                
                                
                                
                                
                            </optgroup>
                            <optgroup label="Содержание">
                                <option value="wysiwyg"<?php echo e(!empty($field->type) && $field->type == 'wysiwyg' ? ' selected' : ''); ?>>Редактор</option>
                                <option value="oembed"<?php echo e(!empty($field->type) && $field->type == 'oembed' ? ' selected' : ''); ?>>Медиа</option>
                                
                            </optgroup>
                            <optgroup label="Выбор">
                                <option value="select"<?php echo e(!empty($field->type) && $field->type == 'select' ? ' selected' : ''); ?>>Выбор (select)</option>
                                
                                
                                
                            </optgroup>
                            <optgroup label="Отношение">
                            <option value="product"<?php echo e(!empty($field->type) && $field->type == 'product' ? ' selected' : ''); ?>>Товар</option>
                            
                            
                            </optgroup>
                            <optgroup label="Блок">
                                
                                
                                <option value="repeater"<?php echo e(!empty($field->type) && $field->type == 'repeater' ? ' selected' : ''); ?>>Повторитель</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-1">
                <span class="btn btn-danger remove-field"><i class="glyphicon glyphicon-trash"></i></span>
            </div>
        </div>
        <div class="row params" style="padding: 15px;">
            <?php if(!empty($field->type) && in_array($field->type, ['text', 'textarea', 'wysiwyg', 'select', 'repeater'])): ?>
                <?php echo $__env->make('admin.pages.templates.fields.'.$field->type, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>