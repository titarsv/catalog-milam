<div class="panel panel-default select">
    <div class="panel-body fields">
        <div class="row">
            <label class="col-sm-3">Мультиязычное поле</label>
            <div class="form-element col-sm-3">
                <select class="form-control" autocomplete="off" name="<?php echo e(isset($parent) ? $parent : ''); ?>[langs]">
                    <option value="0"<?php echo e(!empty($field->langs) ? '' : ' selected'); ?>>Нет</option>
                    <option value="1"<?php echo e(!empty($field->langs) ? ' selected' : ''); ?>>Да</option>
                </select>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/pages/templates/fields/text.blade.php ENDPATH**/ ?>