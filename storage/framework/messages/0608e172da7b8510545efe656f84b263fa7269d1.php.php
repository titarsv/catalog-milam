<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Атрибуты
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Список атрибутов</h1>

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

    <form action="/admin/attributes" method="post" id="settings-form">
        <?php echo csrf_field(); ?>

        <div class="settings row">
            <div class="col-sm-12">
                <div class="input-group">
                    <label for="search" class="input-group-addon">Поиск:</label>
                    <input type="text" id="search" name="search" placeholder="Введите текст..." class="form-control input-sm" value="<?php echo e($current_search); ?>" />
                </div>
            </div>
        </div>
    </form>

    <div class="panel-group">
        <div class="panel panel-default">
            <?php if($user->hasAccess(['attributes.create'])): ?>
            <div class="panel-heading text-right">
                <a href="javascript:void(0)" class="btn btn-primary" id="add_attribute">Добавить новый</a>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Название</td>
                        <td>Українською</td>
                        <td>Значения</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($attribute->name); ?></td>
                            <td><?php echo e($attribute->localize('ua', 'name')); ?></td>
                            <td>
                                <ul class="nav">
                                    <?php $__empty_2 = true; $__currentLoopData = $attribute->values()->limit(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <li><?php echo $value->name; ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <li>Нет добавленных значений!</li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                            <td class="actions" align="center">
                                <?php if($user->hasAccess(['attributes.view'])): ?>
                                <a class="btn btn-primary" href="/admin/attributes/edit/<?php echo $attribute->id; ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($user->hasAccess(['attributes.delete'])): ?>
                                <button type="button" class="btn btn-danger" onclick="confirmAttributesDelete('<?php echo $attribute->id; ?>', '<?php echo e($attribute->name); ?>')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" align="center">Нет добавленных атрибутов!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($attributes->links()); ?>

            </div>
        </div>
    </div>

    <div id="attributes-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить атрибут <span id="attribute-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#add_attribute').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название атрибута',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    focusConfirm: false,
                    preConfirm: (name) => {
                    return new Promise((resolve, reject) => {
                        let formData = new FormData();
                        formData.append('name_<?php echo e(Config::get('app.locale')); ?>', name);
                        $.ajax({
                            type:"POST",
                            url:"/admin/attributes/create",
                            data: formData,
                            processData: false,
                            contentType: false,
                            async:true,
                            success: function(response){
                                console.log(response);
                                if(response.result === 'success'){
                                    resolve(response.redirect);
                                }else{
                                    reject(response.errors);
                                }
                            }
                        });
                    })
                }
            }).then(function(redirect) {
                    location = redirect;
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/attributes/index.blade.php ENDPATH**/ ?>