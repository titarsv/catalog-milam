<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Атрибуты
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование атрибута</h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $attribute
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Слаг',
                         'key' => 'slug',
                         'item' => $attribute,
                         'languages' => null
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php if($errors->has('values')): ?>
                            <p class="warning" role="alert"><?php echo $errors->first('values',':message'); ?></p>
                        <?php endif; ?>
                        <div class="form-group attribute-value">
                            <div class="row">
                                <label class="col-sm-2 text-right">Значения</label>
                                <div class="form-element col-sm-10" id="values">
                                    <?php if($attribute->values !== null): ?>
                                        <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo $__env->make('admin.attributes.value', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->hasAccess(['attributes.update'])): ?>
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="add_attribute_value">Добавить</button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($user->hasAccess(['attributes.update'])): ?>
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

    <style>
        .image-container .remove-image, .image-container > div > div.add-btn::before {
            font-size: 22px;
            line-height: 1;
        }
        .image-container > div > div {
            padding: 16px calc(50% - 2px);
        }
    </style>
    <script>
        $(document).ready(function(){
            $('#add_attribute_value').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите значение атрибута',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    focusConfirm: false,
                    preConfirm: (name) => {
                        return new Promise((resolve, reject) => {
                            let formData = new FormData();
                            formData.append('name_<?php echo e(Config::get('app.locale')); ?>', name);
                            formData.append('attribute_id', <?php echo e($attribute->id); ?>);
                            $.ajax({
                                type:"POST",
                                url:"/admin/attributes/values/create",
                                data: formData,
                                processData: false,
                                contentType: false,
                                async:true,
                                success: function(response){
                                    if(response.result === 'success'){
                                        resolve(response.html);
                                    }else{
                                        reject(response.errors);
                                    }
                                }
                            });
                        })
                    }
                }).then(function(html) {
                    $('#values').append(html);
                }, function(errors) {
                    if(typeof errors !== 'string'){
                        var message = '';
                        for(err in errors){
                            message += errors[err] + '<br>';
                        }
                        swal(
                            'Ошибка!',
                            message,
                            'error'
                        );
                    }
                });
            });
        });

        function confirmAttributeValueDelete(id) {
            swal({
                title: 'Вы уверены?',
                text: "Это значение будет удалено!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить его!',
                cancelButtonText: 'Нет, это ошибка.'
            }).then((result) => {
                    addPlaceholder();
                    $.post('/admin/attributes/values/delete/' + id, {}, function(response){
                        $('#value_'+id).remove();
                        removePlaceholder();
                    });
                },
                (cancel) => {}
            );
        }
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/attributes/edit.blade.php ENDPATH**/ ?>