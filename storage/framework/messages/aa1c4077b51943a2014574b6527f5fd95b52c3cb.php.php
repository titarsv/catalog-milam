<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Наши работы
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1>Список наших работ</h1>
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
                <span class="btn btn-primary" id="add_page">Добавить нашу работу</span>
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

                    <?php $__empty_1 = true; $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($work->id); ?></td>
                            <td>
                                <p><?php echo e($work->name); ?></p>
                            </td>
                            <td>
                                <?php if(!empty($work->image_id) && !empty($work->image)): ?>
                                <img class="img-thumbnail" src="<?php echo e($work->image->url()); ?>" alt="<?php echo $work->image->title; ?>">
                                <?php endif; ?>
                            </td>
                            <td>
                                <p><?php echo e($work->link()); ?></p>
                            </td>
                            <td class="status">
                                <span class="<?php echo $work->visible ? 'on' : 'off'; ?>">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                <?php if($user->hasAccess(['works.view'])): ?>
                                <a class="btn btn-primary" href="/admin/works/edit/<?php echo $work->id; ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <?php endif; ?>
                                <?php if($user->hasAccess(['works.delete'])): ?>
                                <a class="btn btn-danger" onclick="confirmDelete('<?php echo $work->id; ?>', '<?php echo $work->name; ?>')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" align="center">Нет добавленных работ!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($works->links()); ?>

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
                    <p>Удалить работу <span id="name"></span>?</p>
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
                    title: 'Введите название выполненной работы',
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
                                url:"/admin/works/create",
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
            $('#delete-modal #confirm').attr('href', '/admin/works/delete/' + id);
            $('#delete-modal #name').html(name);
            $('#delete-modal').modal();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>