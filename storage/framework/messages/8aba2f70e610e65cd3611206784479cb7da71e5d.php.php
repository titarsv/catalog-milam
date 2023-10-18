<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-title">
        <div class="row">
            <div class="col-sm-6">
                <h1><?php echo e($title); ?></h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if($user->hasAccess(['users.create'])): ?>
                    <button id="add_import" class="btn btn-primary">Импорт покупателей</button>
                <?php endif; ?>
                <?php if($user->hasAccess(['users.view'])): ?>
                    <a href="/admin/users/export" class="btn btn-primary">Экспорт покупателей</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

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

    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-sm-3">
                <div class="panel-group user-info">
                    <div class="panel panel-default">
                        <div class="panel-avatar">
                            <div class="avatar-container">
                                <img src="<?php echo e(in_array('unregistered', $u->role()) ? '/public/images/larchik/unreg.png' : '/public/images/larchik/reg.png'); ?>" alt="user-avatar" />
                            </div>
                        </div>
                        <div class="panel-heading">
                            <p class="name"><?php echo !empty($user) ? $user->first_name : ''; ?> <?php echo !empty($u) ? $u->last_name : ''; ?></p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4"><label>Телефон:</label></div>
                                <div class="col-sm-8"><p class="info"><?php echo !empty($u) ? $u->user_data['phone']: ''; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"><label>E-mail:</label></div>
                                <div class="col-sm-8"><p class="info"><?php echo !empty($u) ? $u->email : ''; ?></p></div>
                            </div>
                            <ul class="nav row">
                                <li class="col-xs-4">
                                    <?php if($user->hasAccess(['orders.list'])): ?>
                                    <a href="/admin/users/stat/<?php echo $u->id; ?>" data-toggle="tooltip" data-placement="top" title="Список заказов">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span class="badge"><?php echo e(count($u->orders)); ?></span>
                                    </a>
                                    <?php endif; ?>
                                </li>
                                <li class="col-xs-4">
                                    <?php if($user->hasAccess(['reviews.list'])): ?>
                                    <a href="/admin/users/reviews/<?php echo $u->id; ?>" data-toggle="tooltip" data-placement="top" title="Список отзывов о товарах">
                                        <i class="fa fa-comments" aria-hidden="true"></i>
                                        <span class="badge"><?php echo e(count($u->reviews)); ?></span>
                                    </a>
                                    <?php endif; ?>
                                </li>
                                <li class="col-xs-4">
                                    <?php if($user->hasAccess(['shopreviews.list'])): ?>
                                    <a href="/admin/users/shopreviews/<?php echo $u->id; ?>"  data-toggle="tooltip" data-placement="top" title="Список отзывов о сайте">
                                        <i class="fa fa-comments" aria-hidden="true"></i>
                                        <span class="badge"><?php echo e(count($u->shopreviews)); ?></span>
                                    </a>
                                    <?php endif; ?>
                                </li>
                            </ul>
                            <?php if($user->hasAccess(['users.view'])): ?>
                            <a class="btn btn-primary" href="/admin/users/edit/<?php echo $u->id; ?>">Редактировать</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Пока нет пользователей.</h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if(!$users->isEmpty() && $users->total() > $users->perPage()): ?>
        <div class="panel-footer text-right">
            <?php echo e($users->links()); ?>

        </div>
    <?php endif; ?>

    <script>
        jQuery(document).ready(function(){
            $('#add_import').click(function(){
                swal({
                    title: 'Новый импорт',
                    html:
                    '<div class="swal2-content">' +
                    '<div class="form-group">' +
                    '<label for="import_file">Файл с данными для импорта:</label>' +
                    '<input id="import_file" name="import_file" type="file" accept=".xlsx,.xls,.csv" aria-label="Файл импорта" class="swal2-file" style="display: flex;" placeholder="Файл импорта">' +
                    '</div>' +
                    '</div>',
                    focusConfirm: false,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            let formData = new FormData();
                            let import_file = $('#import_file').get(0);
                            formData.append('import_file', import_file.files[0]);
                            $.ajax({
                                type:"POST",
                                url:"/admin/users/import",
                                data: formData,
                                processData: false,
                                contentType: false,
                                async:true,
                                success: function(response){
                                    if(response.result === 'success'){
                                        resolve(response);
                                    }else{
                                        reject(response.errors);
                                    }
                                }
                            });
                        });
                    }
                }).then(function() {
                    swal(
                        'Импорт окончен!',
                        'Все пользователи импортированы',
                        'success'
                    );
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

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>