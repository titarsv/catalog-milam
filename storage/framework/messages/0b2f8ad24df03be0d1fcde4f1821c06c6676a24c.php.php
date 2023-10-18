<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Наши фотогаллереи
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1>Список наших фотогаллерей</h1>
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

    <div class="panel-group">
        <div class="panel panel-default">
            <?php if($user->hasAccess(['news.create'])): ?>
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="add_page">Добавить фотогаллерею</span>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>ID</td>
                        <td>Название</td>
                        <td>Изображение</td>
                        <td>URL</td>
                        <td>Опубликовано</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($gallery->id); ?></td>
                            <td>
                                <p><?php echo e($gallery->name); ?></p>
                            </td>
                            <td>
                                <?php if(!empty($gallery->image_id) && !empty($gallery->image)): ?>
                                <img class="img-thumbnail" src="<?php echo e($gallery->image->url()); ?>" alt="<?php echo $gallery->image->title; ?>">
                                <?php endif; ?>
                            </td>
                            <td>
                                <p><?php echo e($gallery->link()); ?></p>
                            </td>
                            <td class="status">
                                <span class="<?php echo $gallery->visible ? 'on' : 'off'; ?>">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                <?php if($user->hasAccess(['photos.view'])): ?>
                                <a class="btn btn-primary" href="/admin/photos/edit/<?php echo $gallery->id; ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($user->hasAccess(['photos.delete'])): ?>
                                <a class="btn btn-danger" onclick="confirmDelete('<?php echo $gallery->id; ?>', '<?php echo $gallery->name; ?>')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" align="center">Нет добавленных фотогаллерей!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($galleries->links()); ?>

            </div>
        </div>
    </div>

    <div id="delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить фотогаллерею <span id="name"></span>?</p>
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
            $('#add_page').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название галлереи',
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
                                url:"/admin/photos/create",
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

        function confirmDelete(id, name) {
            $('#delete-modal #confirm').attr('href', '/admin/photos/delete/' + id);
            $('#delete-modal #name').html(name);
            $('#delete-modal').modal();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>