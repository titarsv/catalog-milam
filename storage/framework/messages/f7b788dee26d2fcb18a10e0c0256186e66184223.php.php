<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Фотогаллерея
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование фотогаллереи <?php echo e($gallery->name); ?></h1>

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
                            <?php $__currentLoopData = $gallery->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row photo" data-id="<?php echo e($i); ?>">
                                <label class="col-sm-2 text-right">Фото</label>
                                <div class="form-element col-sm-2">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => "photos[$i][file_id]",
                                     'image' => $photo->image
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-element col-sm-8">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-sm-2 text-right">Название</label>
                                            <div class="form-element col-sm-10">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <input type="text" class="form-control" name="photos[<?php echo e($i); ?>][name_ru]" value="<?php echo e(old("photos[$i][name_ru]") ? old("photos[$i][name_ru]") : $photo->localize('ru', 'name')); ?>" placeholder="На русском">
                                                        <?php if($errors->has("photos[$i][name_ru]")): ?>
                                                            <p class="warning" role="alert"><?php echo e($errors->first("photos[$i][name_ru]",':message')); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <input type="text" class="form-control" name="photos[<?php echo e($i); ?>][name_ua]" value="<?php echo e(old("photos[$i][name_ua]") ? old("photos[$i][name_ua]") : $photo->localize('ua', 'name')); ?>" placeholder="Українською">
                                                        <?php if($errors->has("photos[$i][name_ua]")): ?>
                                                            <p class="warning" role="alert"><?php echo e($errors->first("photos[$i][name_ua]",':message')); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-sm-2 text-right">Описание</label>
                                            <div class="form-element col-sm-10">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <textarea class="form-control" rows="6" autocomplete="off" name="photos[<?php echo e($i); ?>][description_ru]" placeholder="На русском">
                                                            <?php echo e(old("photos[$i][description_ru]") ? old("photos[$i][description_ru]") : $photo->localize('ru', 'description')); ?>

                                                        </textarea>
                                                        <?php if($errors->has("photos[$i][description_ru]")): ?>
                                                            <p class="warning" role="alert"><?php echo e($errors->first("photos[$i][description_ru]",':message')); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <textarea class="form-control" rows="6" autocomplete="off" name="photos[<?php echo e($i); ?>][description_ua]" placeholder="Українською">
                                                            <?php echo e(old("photos[$i][description_ua]") ? old("photos[$i][description_ua]") : $photo->localize('ua', 'description')); ?>

                                                        </textarea>
                                                        <?php if($errors->has("photos[$i][description_ua]")): ?>
                                                            <p class="warning" role="alert"><?php echo e($errors->first("photos[$i][description_ua]",':message')); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="button" class="btn btn-primary" id="add_photo">Добавить фото</button>
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
            $('#add_photo').click(function(){
                var id = 0;
                $('.photo').each(function(){
                    var photo_id = parseInt($(this).data('id'));
                    if(photo_id >= id){
                        id = photo_id + 1;
                    }
                });
                $(this).parents('.row').before('<div class="form-group">\n' +
                    '    <div class="row photo" data-id="'+id+'">\n' +
                    '        <label class="col-sm-2 text-right">Фото</label>\n' +
                    '        <div class="form-element col-sm-2">\n' +
                    '            <div class="image-container">\n' +
                    '                <input type="hidden" name="photos['+id+'][file_id]" value="">\n' +
                    '                <div class="upload_image_button" data-type="single" style="">\n' +
                    '                    <div class="add-btn"></div>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '        <div class="form-element col-sm-8">\n' +
                    '            <div class="form-group">\n' +
                    '                <div class="row">\n' +
                    '                    <label class="col-sm-2 text-right">Название</label>\n' +
                    '                    <div class="form-element col-sm-10">\n' +
                    '                        <div class="row">\n' +
                    '                            <div class="col-xs-6">\n' +
                    '                                <input type="text" class="form-control" name="photos['+id+'][name_ru]" value="" placeholder="На русском">\n' +
                    '                            </div>\n' +
                    '                            <div class="col-xs-6">\n' +
                    '                                <input type="text" class="form-control" name="photos['+id+'][name_ua]" value="" placeholder="Українською">\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '            <div class="form-group">\n' +
                    '                <div class="row">\n' +
                    '                    <label class="col-sm-2 text-right">Описание</label>\n' +
                    '                    <div class="form-element col-sm-10">\n' +
                    '                        <div class="row">\n' +
                    '                            <div class="col-xs-6">\n' +
                    '                                <textarea class="form-control" rows="6" autocomplete="off" name="photos['+id+'][description_ru]" placeholder="На русском"></textarea>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-xs-6">\n' +
                    '                                <textarea class="form-control" rows="6" autocomplete="off" name="photos['+id+'][description_ua]" placeholder="Українською"></textarea>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '    </div>\n' +
                    '</div>');
            });
        });
    </script>
    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>