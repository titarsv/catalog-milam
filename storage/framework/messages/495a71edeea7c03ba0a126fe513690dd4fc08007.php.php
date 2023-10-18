<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Статьи
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1>Список статей</h1>
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
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="add_page">Создать статью</span>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>ID</td>
                        <td>Заголовок</td>
                        <td>Изображение</td>
                        <td>URL</td>
                        <td>Опубликовано</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($article->id); ?></td>
                            <td>
                                <p><?php echo e($article->name); ?></p>
                            </td>
                            <td>
                                <?php if(!empty($article->image_id) && !empty($article->image)): ?>
                                <img class="img-thumbnail" src="<?php echo e($article->image->url()); ?>" alt="<?php echo $article->image->title; ?>">
                                <?php endif; ?>
                            </td>
                            <td>
                                <p><?php echo e($article->link()); ?></p>
                            </td>
                            <td class="status">
                                <span class="<?php echo $article->status ? 'on' : 'off'; ?>">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                <a class="btn btn-primary" href="/admin/blog/edit/<?php echo $article->id; ?>">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="/admin/blog/delete/<?php echo $article->id; ?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" align="center">Нет добавленных статей!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <?php echo e($articles->links()); ?>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#add_page').click(function(e){
                e.preventDefault();
                swal({
                    title: 'Введите название статьи',
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
                                url:"/admin/blog/create",
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
        function confirmPageDelete(id, name) {
            $('#html-delete-modal #confirm').attr('href', '/admin/blog/delete/' + id);
            $('#html-delete-modal #html-name').html(name);
            $('#html-delete-modal').modal();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>