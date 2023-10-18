<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Запись блога
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование пользователя <?php echo e($u->first_name); ?> <?php echo e($u->last_name); ?></h1>

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
                        
                        
                            
                                
                                
                                    
                                
                            
                        

                        
                            
                                
                                
                                    
                                
                            
                        
                        

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Имя</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="first_name" value="<?php echo old('first_name') ? old('first_name') : $u->first_name; ?>" />
                                    <?php if($errors->has('first_name')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('first_name',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Фамилия</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="last_name" value="<?php echo old('last_name') ? old('last_name') : $u->last_name; ?>" />
                                    <?php if($errors->has('last_name')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('last_name',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отчество</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="patronymic" value="<?php echo old('patronymic') ? old('patronymic') : $u->patronymic; ?>" />
                                    <?php if($errors->has('patronymic')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('patronymic',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Почта</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="email" value="<?php echo old('email') ? old('email') : $u->email; ?>" />
                                    <?php if($errors->has('email')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('email',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Телефон</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="phone" value="<?php echo old('phone') ? old('phone') : (empty($u->user_data) ? '' : $u->user_data->phone); ?>" />
                                    <?php if($errors->has('phone')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('phone',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Город</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="city" value="<?php echo old('city') ? old('city') : (empty($u->user_data) ? '' : $u->user_data->city); ?>" />
                                    <?php if($errors->has('city')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('city',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Пол</label>
                                <div class="form-element col-sm-10">
                                    <select name="gender" class="form-control" autocomplete="off">
                                        <option value="0"<?php echo e(empty($u->user_data) || empty($u->user_data->gender) ? ' selected' : ''); ?>>Женский</option>
                                        <option value="1"<?php echo e(!empty($u->user_data) && !empty($u->user_data->gender) ? ' selected' : ''); ?>>Мужской</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Группа</label>
                                <div class="form-element col-sm-10">
                                    <select name="role" class="form-control" autocomplete="off">
                                        <option value="manager"<?php echo e(isset($u->roles->first()->slug) && $u->roles->first()->slug == 'manager' ? ' selected' : ''); ?>>Менеджеры</option>
                                        <option value="moderator"<?php echo e(isset($u->roles->first()->slug) && $u->roles->first()->slug == 'moderator' ? ' selected' : ''); ?>>Модераторы</option>
                                        <option value="marketerr"<?php echo e(isset($u->roles->first()->slug) && $u->roles->first()->slug == 'marketer' ? ' selected' : ''); ?>>Маркетологи</option>
                                        <option value="user"<?php echo e(isset($u->roles->first()->slug) && ($u->roles->first()->slug == 'user' || $u->roles->first()->slug == 'unregistered') ? ' selected' : ''); ?>>Покупатели</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php echo $__env->make('admin.layouts.form.select', [
                        'label' => 'Персональные разрешения',
                        'key' => 'permissions',
                        'options' => $all_permissions,
                        'multiple' => true,
                        'selected' => $permissions
                       ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    </div>
                </div>
                <?php if($user->hasAccess(['users.update'])): ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>