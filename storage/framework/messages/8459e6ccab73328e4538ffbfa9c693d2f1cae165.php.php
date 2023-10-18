<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Настройки магазина
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Настройки доставки и оплаты</h1>
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

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Доступные методы доставки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Методы доставки</label>
                                <div class="form-element col-sm-10">
                                    <select name="delivery_methods[]" class="form-control chosen-select" multiple>
                                        <?php $__currentLoopData = $settings->delivery_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($method); ?>"
                                                    <?php if($status): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo e($delivery_names[$method]); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <?php if($user->hasAccess(['settings.update'])): ?>
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Доступные методы оплаты</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Методы оплаты</label>
                                <div class="form-element col-sm-10">
                                    <select name="payment_methods[]" class="form-control chosen-select" multiple>
                                        <?php $__currentLoopData = $settings->payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($method); ?>"
                                                    <?php if($status): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo e($payment_names[$method]); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <?php if($user->hasAccess(['settings.update'])): ?>
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки API Новая Почта</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Ключ API</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="newpost_api_key" value="<?php echo old('newpost_api_key', isset($settings->newpost_api_key) ? $settings->newpost_api_key : ''); ?>" />
                                    <?php if($errors->has('newpost_api_key')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('newpost_api_key',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления областей Украины</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_regions_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        <?php $__currentLoopData = $update_period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $period['value']; ?>"
                                                    <?php if(isset($settings->newpost_regions_update_period) && $period['value'] == $settings->newpost_regions_update_period): ?>)
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo $period['period']; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления областей Украины</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo !empty($settings->newpost_regions_last_update) ? date('d.m.Y', $settings->newpost_regions_last_update) . ' г' : 'Нет данных, необходимо обновить!'; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления городов Украины</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_cities_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        <?php $__currentLoopData = $update_period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $period['value']; ?>"
                                                    <?php if(isset($settings->newpost_cities_update_period) && $period['value'] == $settings->newpost_cities_update_period): ?>)
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo $period['period']; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления городов Украины</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo !empty($settings->newpost_cities_last_update) ? date('d.m.Y', $settings->newpost_cities_last_update) . ' г' : 'Нет данных, необходимо обновить!'; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Период обновления отделений НП</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_warehouses_update_period" class="form-control">
                                        <option value="0">Не выбрано</option>
                                        <?php $__currentLoopData = $update_period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $period['value']; ?>"
                                                    <?php if(isset($settings->newpost_warehouses_update_period) && $period['value'] == $settings->newpost_warehouses_update_period): ?>)
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo $period['period']; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Дата последнего обновления отделений НП</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo !empty($settings->newpost_warehouses_last_update) ? date('d.m.Y', $settings->newpost_warehouses_last_update) . ' г' : 'Нет данных, необходимо обновить!'; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отправитель</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_sender_id" class="form-control">
                                        <?php $__currentLoopData = $np_senders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sender): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sender['Ref']); ?>"<?php echo e(!empty($settings->newpost_sender_id) && $sender['Ref'] == $settings->newpost_sender_id); ?>><?php echo e($sender['Description']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Отделение отправки</label>
                                <div class="form-element col-sm-10">
                                    <select name="newpost_warehouse_sender_id" class="form-control">
                                        <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($warehouse->warehouse_id); ?>"<?php echo e(!empty($settings->newpost_warehouse_sender_id) && $warehouse->warehouse_id == $settings->newpost_warehouse_sender_id); ?>><?php echo e($warehouse->address_ru); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php if($user->hasAccess(['settings.update'])): ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <a href="/admin/delivery-and-payment/newpost-update" class="btn btn-primary">Обновить</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <?php if($user->hasAccess(['settings.update'])): ?>
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                    <?php endif; ?>
                                    <a href="/admin" class="btn btn-primary">На главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки WayForPay</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Аккаунт</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="wayforpay_account" value="<?php echo old('wayforpay_account', !empty($settings->wayforpay_account) ? $settings->wayforpay_account : ''); ?>" />
                                    <?php if($errors->has('wayforpay_account')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('wayforpay_account',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Токен</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="wayforpay_secret" value="<?php echo old('wayforpay_secret', !empty($settings->wayforpay_secret) ? $settings->wayforpay_secret : ''); ?>" />
                                    <?php if($errors->has('wayforpay_secret')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('wayforpay_secret',':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тестовый режим</label>
                                <div class="form-element col-sm-10">
                                    <select name="wayforpay_sandbox" class="form-control">
                                        <?php if(old('wayforpay_sandbox') || !empty($settings->wayforpay_sandbox)): ?>
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        <?php elseif(!old('wayforpay_sandbox') || empty($settings->wayforpay_sandbox)): ?>
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <?php if($user->hasAccess(['settings.update'])): ?>
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                    <?php endif; ?>
                                    <a href="/admin" class="btn btn-primary">На главную</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    
        
            
            
                
                    
                        
                    
                    
                        
                            
                                
                                
                                    
                                    
                                        
                                    
                                
                            
                        
                        
                            
                                
                                
                                    
                                    
                                        
                                    
                                
                            
                        
                        
                            
                                
                                
                                    
                                        
                                        
                                            
                                                    
                                                    
                                                    
                                            
                                        
                                    
                                
                            
                        
                        
                            
                                
                                
                                    
                                        
                                            
                                            
                                        
                                            
                                            
                                        
                                    
                                
                            
                        
                        
                            
                                
                                    
                                    
                                    
                                    
                                
                            
                        
                    
                
            
        
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>