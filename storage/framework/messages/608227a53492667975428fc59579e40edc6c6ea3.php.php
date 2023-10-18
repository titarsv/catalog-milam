<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Видеогаллерея
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование видеогаллереи <?php echo e($gallery->name); ?></h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $gallery,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Превью</label>
                                <div class="form-element col-sm-3">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'file_id',
                                     'image' => $gallery->image
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php $__currentLoopData = $gallery->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row video" data-id="<?php echo e($i); ?>">
                                    <label class="col-sm-2 text-right">Видео</label>
                                    <div class="form-element col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[<?php echo e($i); ?>][link]" placeholder="Ссылка" value="<?php echo e(old("videos[$i][link]") ? old("videos[$i][link]") : $video->link); ?>">
                                                <?php if($errors->has("videos[$i][link]")): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first("videos[$i][link]",':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[<?php echo e($i); ?>][name_ru]" value="<?php echo e(old("videos[$i][name_ru]") ? old("videos[$i][name_ru]") : $video->localize('ru', 'name')); ?>" placeholder="На русском">
                                                <?php if($errors->has("videos[$i][name_ru]")): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first("videos[$i][name_ru]",':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="videos[<?php echo e($i); ?>][name_ua]" value="<?php echo e(old("videos[$i][name_ua]") ? old("videos[$i][name_ua]") : $video->localize('ua', 'name')); ?>" placeholder="Українською">
                                                <?php if($errors->has("videos[$i][name_ua]")): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first("videos[$i][name_ua]",':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-element col-sm-1">
                                        <?php echo $__env->make('admin.layouts.form.image', [
                                         'key' => "videos[$i][file_id]",
                                         'image' => $video->image
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="add_video">Добавить видео</button>
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
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'visible',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('visible') ? old('visible') : $gallery->visible]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if($user->hasAccess(['news.update'])): ?>
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
            $('#add_video').click(function(){
                var id = 0;
                $('.video').each(function(){
                    var video_id = parseInt($(this).data('id'));
                    if(video_id >= id){
                        id = video_id + 1;
                    }
                });
                $(this).parents('.row').before('<div class="row video" data-id="'+id+'">\n' +
                    '  <label class="col-sm-2 text-right">Видео</label>\n' +
                    '  <div class="form-element col-sm-9">\n' +
                    '    <div class="row">\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][link]" placeholder="Ссылка" value="">\n' +
                    '      </div>\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][name_ru]" value="" placeholder="На русском">\n' +
                    '      </div>\n' +
                    '      <div class="col-sm-4">\n' +
                    '        <input type="text" class="form-control" name="videos['+id+'][name_ua]" value="" placeholder="Українською">\n' +
                    '      </div>\n' +
                    '    </div>\n' +
                    '  </div>\n' +
                    '  <div class="form-element col-sm-1">\n' +
                    '    <div class="image-container">\n' +
                    '      <input type="hidden" name="videos['+id+'][file_id]" value="">\n' +
                    '      <div class="upload_image_button" data-type="single">\n' +
                    '        <div class="add-btn"></div>\n' +
                    '      </div>\n' +
                    '    </div>' +
                    '  </div>\n' +
                    '</div>');
            });
        });
    </script>
    <style>
        .video .image-container{
            max-width: 36px;
        }
        .video .image-container .remove-image, .video .image-container > div > div.add-btn::before {
            font-size: 24px;
            line-height: 22px;
        }
    </style>
    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>