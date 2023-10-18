<?php
    $id = str_replace(['[', ']'], '', $editor_id);
?>
<div id="wp-<?php echo e($id); ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
    <div id="wp-<?php echo e($id); ?>-editor-tools" class="wp-editor-tools hide-if-no-js">
        <div id="wp-<?php echo e($id); ?>-media-buttons" class="wp-media-buttons">
            <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="<?php echo e($id); ?>"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
        </div>
        <div class="wp-editor-tabs">
            <button type="button" id="<?php echo e($id); ?>-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="<?php echo e($id); ?>">Визуально</button>
            <button type="button" id="<?php echo e($id); ?>-html" class="wp-switch-editor switch-html" data-wp-editor-id="<?php echo e($id); ?>">Текст</button>
        </div>
    </div>
    <div id="wp-<?php echo e($id); ?>-editor-container" class="wp-editor-container">
        <div id="qt_<?php echo e($id); ?>_toolbar" class="quicktags-toolbar"></div>
        <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="<?php echo e($editor_id); ?>" id="<?php echo e($id); ?>" placeholder="<?php echo e($placeholder); ?>"><?php echo $content; ?></textarea>
    </div>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/layouts/texteditor.blade.php ENDPATH**/ ?>