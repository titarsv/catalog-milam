<?php
    if(isset($locale) && (empty($languages) || count($languages) < 2)){
        $key .= '_'.$locale;
    }
    $id = str_replace(['[', ']'], '', $key);
?>
<div class="form-group<?php echo e(!empty($languages) ? ' js_langs' : ''); ?>">
    <?php if(!empty($languages)): ?>
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row js_lang lng_<?php echo e($lang_key); ?><?php echo e($main_lang == $lang_key ? ' active_lang' : ''); ?>">
                <label class="col-sm-2 text-right<?php echo e(!empty($required) ? ' control-label' : ''); ?>"><?php echo e($label); ?></label>
                <div class="form-element col-sm-10">
                    <div id="wp-<?php echo e($id); ?>_<?php echo e($lang_key); ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                        <div id="wp-<?php echo e($id); ?>_<?php echo e($lang_key); ?>-editor-tools" class="wp-editor-tools hide-if-no-js">
                            <div id="wp-<?php echo e($id); ?>_<?php echo e($lang_key); ?>-media-buttons" class="wp-media-buttons">
                                <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="<?php echo e($id); ?>_<?php echo e($lang_key); ?>"><span class="wp-media-buttons-icon"></span> Добавить медиафайл</button>
                            </div>
                            <div class="wp-editor-tabs">
                                <button type="button" id="<?php echo e($id); ?>_<?php echo e($lang_key); ?>-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="<?php echo e($id); ?>_<?php echo e($lang_key); ?>">Визуально</button>
                                <button type="button" id="<?php echo e($id); ?>_<?php echo e($lang_key); ?>-html" class="wp-switch-editor switch-html" data-wp-editor-id="<?php echo e($id); ?>_<?php echo e($lang_key); ?>">Текст</button>
                            </div>
                        </div>
                        <div id="wp-<?php echo e($id); ?>_<?php echo e($lang_key); ?>-editor-container" class="wp-editor-container">
                            <div id="qt_<?php echo e($id); ?>_<?php echo e($lang_key); ?>_toolbar" class="quicktags-toolbar"></div>
                            <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="<?php echo e($key); ?>_<?php echo e($lang_key); ?>" id="<?php echo e($id); ?>_<?php echo e($lang_key); ?>" placeholder="<?php echo e($lang_name); ?>"><?php echo e(old($key.'_'.$lang_key) ? old($key.'_'.$lang_key) : (isset($item) ? $item->localize($lang_key, $key) : '')); ?></textarea>
                        </div>
                    </div>
                    <?php if($errors->has($key.'_'.$lang_key)): ?>
                        <p class="warning" role="alert"><?php echo e($errors->first($key.'_'.$lang_key,':message')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="row">
            <label class="col-sm-2 text-right<?php echo e(!empty($required) ? ' control-label' : ''); ?>"><?php echo e($label); ?></label>
            <div class="form-element col-sm-10">
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
                        <textarea class="wp-editor-area" rows="20" autocomplete="off" cols="40" name="<?php echo e($key); ?>" id="<?php echo e($id); ?>">
                                    <?php echo e(old($key) ? old($key) : (isset($locale) ? (isset($item) ? $item->localize($locale, isset($locale) ? substr($key, 0, -3) : $key) : '') : (isset($item) && !empty($item->$key) ? $item->$key : ''))); ?>

                                </textarea>
                    </div>
                </div>
                <?php if($errors->has($key)): ?>
                    <p class="warning" role="alert"><?php echo e($errors->first($key,':message')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/layouts/form/editor.blade.php ENDPATH**/ ?>