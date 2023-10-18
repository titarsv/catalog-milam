<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Каталог экспортов
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Каталог экспортов</h1>

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
            <?php if($user->hasAccess(['export.create'])): ?>
            <div class="panel-heading text-right">
                <a href="/admin/products/export/create" class="btn btn-primary">Добавить новый</a>
            </div>
            <?php endif; ?>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название экспорта</td>
                            <td>Тип</td>
                            <td>URL</td>
                            <td>Последнее обновление</td>
                            <td>Следующее обновление</td>
                            <td>Статус генерации</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $exports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $export): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($export->name); ?></td>
                                <td><?php echo e($export->type); ?></td>
                                <td>
                                    <?php if(is_file(public_path('exports/'.$export->url.'.'.$export->type))): ?>
                                        <a href="<?php echo e(env('APP_URL')); ?>/exports/<?php echo e($export->url.'.'.$export->type); ?>"<?php echo e($export->type == 'rss' ? ' target="_blank"' : ''); ?>><?php echo e($export->url.'.'.$export->type); ?></a>
                                    <?php else: ?>
                                        <?php echo e($export->url.'.'.$export->type); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(isset($export->schedule->updated_at) ? date('Y-m-d H:i:s', $export->schedule->updated_at) : 'никогда'); ?></td>
                                <td>
                                    <?php if(isset($export->schedule->status) && $export->schedule->status != 1): ?>
                                        происходит сейчас
                                    <?php else: ?>
                                        <?php echo e(isset($export->schedule->nextRun) ? date('Y-m-d H:i:s', $export->schedule->nextRun) : 'не запланировано'); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(isset($export->schedule->status) ? round($export->schedule->status*100).'%' : '-'); ?></td>
                                <td class="actions" align="center" style="width: 240px;">
                                    <?php if($user->hasAccess(['import.view'])): ?>
                                    <a class="btn btn-warning fast_export" href="javascript:void(0);" data-id="<?php echo e($export->id); ?>">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                    </a>
                                    <?php if(is_file(public_path('exports/'.$export->url.'.'.$export->type))): ?>
                                        <a class="btn btn-success" href="<?php echo e(env('APP_URL')); ?>/exports/<?php echo e($export->url.'.'.$export->type); ?>" download>
                                            <i class="glyphicon glyphicon-floppy-save"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-success" href="/admin/products/export/download/<?php echo $export->id; ?>" data-toggle="tooltip" data-placement="top" title="Скачать">
                                            <i class="glyphicon glyphicon-floppy-save"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn btn-primary" href="/admin/products/export/edit/<?php echo $export->id; ?>">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if($user->hasAccess(['import.delete'])): ?>
                                    <button type="button" class="btn btn-danger" onclick="confirmExportDelete('<?php echo $export->id; ?>', '<?php echo $export->name; ?>')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" align="center">Нет записей экспорта!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
                    <p>Удалить запись <span id="delete-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function(){
            $('.fast_export').click(function(e){
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    title: 'Происходит генерация файла.',
                    html: '<p>Ожидайте окончания процесса.</p><div id="export_progress"></div>',
                    onBeforeOpen: () => {
                        swal.showLoading();
                    }
                });
                next_export_step(id, 1);
            });
            function next_export_step(id, start = 0){
                $.post("/admin/products/export/refresh/"+id, {start: start}, function(response){
                    console.log(response);
                    if(response.saved != response.total) {
                        let percent = Math.round(response.saved / response.total * 100);
                        $('#export_progress').html('<p>Обработано ' + response.saved + ' из ' + response.total + ' товаров</p>' +
                            '<div class="progress progress-striped active">\n' +
                            '<div class="progress-bar"  role="progressbar" aria-valuenow="' + percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percent + '%">\n' +
                            percent + '%\n' +
                            '</div>\n' +
                            '</div>');
                        next_export_step(id);
                    }else{
                        $('#export_progress').html('<p>Экспорт окончен!</p>' +
                            '<div class="progress progress-striped active">\n' +
                            '<div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">\n' +
                            '100%\n' +
                            '</div>\n' +
                            '</div>');
                        swal({
                            type: 'success',
                            title: 'Экспорт окончен!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        });

        function confirmExportDelete(id, name){
            $('#delete-modal #confirm').attr('href', '/admin/products/export/delete/' + id);
            $('#delete-modal #delete-name').html(name);
            $('#delete-modal').modal();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>