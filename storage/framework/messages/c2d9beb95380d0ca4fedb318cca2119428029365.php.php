<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Настройки магазина
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Настройки продвижения</h1>
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
                        <h4>Шаблоны</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Переменные товаров</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p><b>[product_name]</b> - название товара</p>
                                            <p><b>[product_brand]</b> - производитель товара</p>
                                            <p><b>[product_color]</b> - цвет товара</p>
                                            <p><b>[product_category]</b> - категория товара</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Title товаров</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="products_meta_title_<?php echo e($lang_key); ?>" value="<?php echo e(old('products_meta_title_'.$lang_key, isset($settings->{'products_meta_title_'.$lang_key}) ? $settings->{'products_meta_title_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('products_meta_title_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('products_meta_title_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="products_meta_title<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('products_meta_title'.(isset($locale) ? '_products_meta_title' : ''), $settings->{'products_meta_title'.(isset($locale) ? '_products_meta_title' : '')})); ?>" />
                                                <?php if($errors->has('products_meta_title')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('products_meta_title',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Description товаров</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="products_meta_description_<?php echo e($lang_key); ?>" value="<?php echo e(old('products_meta_description_'.$lang_key, isset($settings->{'products_meta_description_'.$lang_key}) ? $settings->{'products_meta_description_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('products_meta_description_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('products_meta_description_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="products_meta_description<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('products_meta_description'.(isset($locale) ? '_products_meta_description' : ''), $settings->{'products_meta_description'.(isset($locale) ? '_products_meta_description' : '')})); ?>" />
                                                <?php if($errors->has('products_meta_description')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('products_meta_description',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Keywords товаров</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="products_meta_keywords_<?php echo e($lang_key); ?>" value="<?php echo e(old('products_meta_keywords_'.$lang_key, isset($settings->{'products_meta_keywords_'.$lang_key}) ? $settings->{'products_meta_keywords_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('products_meta_keywords_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('products_meta_keywords_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="products_meta_keywords<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('products_meta_keywords'.(isset($locale) ? '_products_meta_keywords' : ''), $settings->{'products_meta_keywords'.(isset($locale) ? '_products_meta_keywords' : '')})); ?>" />
                                                <?php if($errors->has('products_meta_keywords')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('products_meta_keywords',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Переменные категорий</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p><b>[category_name]</b> - название категории</p>
                                            <p><b>[parent_category_name]</b> - название родительской категории</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Title категорий</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="categories_meta_title_<?php echo e($lang_key); ?>" value="<?php echo e(old('categories_meta_title_'.$lang_key, isset($settings->{'categories_meta_title_'.$lang_key}) ? $settings->{'categories_meta_title_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('categories_meta_title_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_title_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="categories_meta_title<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('categories_meta_title'.(isset($locale) ? '_categories_meta_title' : ''), $settings->{'categories_meta_title'.(isset($locale) ? '_categories_meta_title' : '')})); ?>" />
                                                <?php if($errors->has('categories_meta_title')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_title',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Description категорий</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="categories_meta_description_<?php echo e($lang_key); ?>" value="<?php echo e(old('categories_meta_description_'.$lang_key, isset($settings->{'categories_meta_description_'.$lang_key}) ? $settings->{'categories_meta_description_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('categories_meta_description_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_description_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="categories_meta_description<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('categories_meta_description'.(isset($locale) ? '_categories_meta_description' : ''), $settings->{'categories_meta_description'.(isset($locale) ? '_categories_meta_description' : '')})); ?>" />
                                                <?php if($errors->has('categories_meta_description')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_description',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Keywords категорий</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <?php if(!empty($languages) && count($languages) > 1): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang_key => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xs-6">
                                                    <input type="text" class="form-control" name="categories_meta_keywords_<?php echo e($lang_key); ?>" value="<?php echo e(old('categories_meta_keywords_'.$lang_key, isset($settings->{'categories_meta_keywords_'.$lang_key}) ? $settings->{'categories_meta_keywords_'.$lang_key} : '')); ?>" placeholder="<?php echo e($lang_name); ?>" />
                                                    <?php if($errors->has('categories_meta_keywords_'.$lang_key)): ?>
                                                        <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_keywords_'.$lang_key,':message')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-xs-12">
                                                <input type="text" class="form-control" name="categories_meta_keywords<?php echo e(isset($locale) ? '_'.$locale : ''); ?>"
                                                       value="<?php echo e(old('categories_meta_keywords'.(isset($locale) ? '_categories_meta_keywords' : ''), $settings->{'categories_meta_keywords'.(isset($locale) ? '_categories_meta_keywords' : '')})); ?>" />
                                                <?php if($errors->has('categories_meta_keywords')): ?>
                                                    <p class="warning" role="alert"><?php echo e($errors->first('categories_meta_keywords',':message')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Google Tag Manager</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Базовый код GTM</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('gtm') !== null): ?>
                                        <textarea name="gtm" class="form-control" rows="6"><?php echo old('gtm'); ?></textarea>
                                        <?php if($errors->has('gtm')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('gtm',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <textarea name="gtm" class="form-control" rows="6"><?php echo $settings->gtm; ?></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">noscript код GTM</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('gtm') !== null): ?>
                                        <textarea name="gtm_noscript" class="form-control" rows="6"><?php echo old('gtm_noscript'); ?></textarea>
                                        <?php if($errors->has('gtm')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('gtm_noscript',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <textarea name="gtm_noscript" class="form-control" rows="6"><?php echo $settings->gtm_noscript; ?></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Токен</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?php if(old('ga_token[access_token]') !== null): ?>
                                                <input type="text" class="form-control" name="ga_token[access_token]" value="<?php echo old('ga_token[access_token]'); ?>" placeholder="Токен"/>
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="ga_token[access_token]" value="<?php echo isset($settings->ga_token->access_token) ? $settings->ga_token->access_token : ''; ?>" placeholder="Токен"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php if(old('ga_token[token_type]') !== null): ?>
                                                <input type="text" class="form-control" name="ga_token[token_type]" value="<?php echo old('ga_token[token_type]'); ?>" placeholder="Тип"/>
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="ga_token[token_type]" value="<?php echo isset($settings->ga_token->token_type) ? $settings->ga_token->token_type : ''; ?>" placeholder="Тип"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php if(old('ga_token[created]') !== null): ?>
                                                <input type="text" class="form-control" name="ga_token[created]" value="<?php echo old('ga_token[created]'); ?>" placeholder="Создан"/>
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="ga_token[created]" value="<?php echo isset($settings->ga_token->created) ? $settings->ga_token->created : ''; ?>" placeholder="Создан"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php if(old('ga_token[expires_in]') !== null): ?>
                                                <input type="text" class="form-control" name="ga_token[expires_in]" value="<?php echo old('ga_token[expires_in]'); ?>" placeholder="Действителен"/>
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="ga_token[expires_in]" value="<?php echo isset($settings->ga_token->expires_in) ? $settings->ga_token->expires_in : ''; ?>" placeholder="Действителен"/>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Facebook Pixel</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Код Facebook Pixel</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('fb_pixel') !== null): ?>
                                        <textarea name="fb_pixel" class="form-control" rows="6"><?php echo old('fb_pixel'); ?></textarea>
                                        <?php if($errors->has('fb_pixel')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('fb_pixel',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <textarea name="fb_pixel" class="form-control" rows="6"><?php echo $settings->fb_pixel; ?></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Микроразметка</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Тип</label>
                                <div class="form-element col-sm-10">
                                    <select name="ld_type" class="form-control">
                                        <?php $__currentLoopData = [
                                            'Store' => 'Магазин',
                                            'AutoPartsStore' => 'Магазин автозапчастей',
                                            'BikeStore' => 'Мото магазин',
                                            'BookStore' => 'Книжный магазин',
                                            'ClothingStore' => 'Магазин одежды',
                                            'ComputerStore' => 'Компьютерный магазин',
                                            'ConvenienceStore' => 'Супермаркет',
                                            'DepartmentStore' => 'Универмаг',
                                            'ElectronicsStore' => 'Магазин электроники',
                                            'Florist' => 'Магазин растений / Цветочный магазин',
                                            'FurnitureStore' => 'Магазин фурнитуры',
                                            'GardenStore' => 'Магазин сад / огород',
                                            'GroceryStore' => 'Продуктовый магазин',
                                            'HobbyShop' => 'Хобби магазин',
                                            'HardwareStore' => 'Магазин ПО',
                                            'HomeGoodsStore' => 'Магазин домашней утвари',
                                            'JewelryStore' => 'Ювелирный магазин',
                                            'MensClothingStore' => 'Магазин мужской одежды',
                                            'MovieRentalStore' => 'Прокат фильмов',
                                            'MusicStore' => 'Музыкальный магазин',
                                            'OfficeEquipmentStore' => 'Магазин офисного оборудования',
                                            'OutletStore' => 'Фирменный магазин',
                                            'PetStore' => 'Зоомагазин',
                                            'ShoeStore' => 'Обувной магазин',
                                            'SportingGoodsStore' => 'Магазин спортивных товаров',
                                            'TireShop' => 'Магазин шин',
                                            'ToyStore' => 'Магазин игрушек',
                                            'WholesaleStore' => 'Оптовый магазин',
                                            'MobilePhoneStore' => 'Магазин мобильных телефонов / гаджетов',
                                            'LiquorStore' => 'Ликеро-водочный магазин',
                                            'PawnShop' => 'Ломбард',
                                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $id; ?>"
                                                    <?php if((!empty(old('ld_type')) && $id == old('ld_type')) || (empty(old('ld_type')) && $id == $settings->ld_type)): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo $name; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Название организации</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_name') !== null): ?>
                                        <input type="text" class="form-control" name="ld_name" value="<?php echo old('ld_name'); ?>" />
                                        <?php if($errors->has('ld_name')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_name',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_name" value="<?php echo $settings->ld_name; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Описание</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_description') !== null): ?>
                                        <textarea name="ld_description" class="form-control" rows="6"><?php echo old('ld_description'); ?></textarea>
                                        <?php if($errors->has('ld_description')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_description',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <textarea name="ld_description" class="form-control" rows="6"><?php echo $settings->ld_description; ?></textarea>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Логотип</label>
                                <div class="form-element col-sm-3">
                                    <div class="image-container">
                                        <input type="hidden" name="ld_image" value="<?php echo old('ld_image', $settings->ld_image); ?>" />
                                        <?php if(!empty($settings->ld_image) && !empty($imag)): ?>
                                            <div>
                                                <div>
                                                    <i class="remove-image">-</i>
                                                    <img src="<?php echo e($image->url); ?>" />
                                                </div>
                                            </div>
                                            <div class="upload_image_button" data-type="single" style="display: none;">
                                                <div class="add-btn"></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="upload_image_button" data-type="single">
                                                <div class="add-btn"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($errors->has('ld_image')): ?>
                                        <p class="warning" role="alert"><?php echo $errors->first('ld_image', ':message'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Область</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_region') !== null): ?>
                                        <input type="text" class="form-control" name="ld_region" value="<?php echo old('ld_region'); ?>" />
                                        <?php if($errors->has('ld_region')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_region',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_region" value="<?php echo $settings->ld_region; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Город</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_city') !== null): ?>
                                        <input type="text" class="form-control" name="ld_city" value="<?php echo old('ld_city'); ?>" />
                                        <?php if($errors->has('ld_city')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_city',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_city" value="<?php echo $settings->ld_city; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Улица, дом</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_street') !== null): ?>
                                        <input type="text" class="form-control" name="ld_street" value="<?php echo old('ld_street'); ?>" />
                                        <?php if($errors->has('ld_street')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_street',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_street" value="<?php echo $settings->ld_street; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right ">Почтовый код</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_postcode') !== null): ?>
                                        <input type="text" class="form-control" name="ld_postcode" value="<?php echo old('ld_postcode'); ?>" />
                                        <?php if($errors->has('ld_postcode')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_postcode',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_postcode" value="<?php echo $settings->ld_postcode; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Основной телефон</label>
                                <div class="form-element col-sm-10">
                                    <?php if(old('ld_phone') !== null): ?>
                                        <input type="text" class="form-control" name="ld_phone" value="<?php echo old('ld_phone'); ?>" />
                                        <?php if($errors->has('ld_phone')): ?>
                                            <p class="warning" role="alert"><?php echo $errors->first('ld_phone',':message'); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="ld_phone" value="<?php echo $settings->ld_phone; ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Способы оплаты</label>
                                <div class="form-element col-sm-10">
                                    <select name="ld_payments[]" class="form-control chosen-select" multiple>
                                        <?php $__currentLoopData = ['cash' => 'Наличными', 'credit card' => 'Картой', 'invoice' => 'Счётом']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_id => $payment_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo $payment_id; ?>"
                                                    <?php if((is_array(old('ld_payments')) && in_array($payment_id, old('ld_payments'))) || in_array($payment_id, $settings->ld_payments)): ?>
                                                    selected
                                                    <?php endif; ?>
                                            ><?php echo $payment_name; ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">График работы</label>
                                <div class="form-element col-sm-10">
                                    <?php $__currentLoopData = ['Mo' => 'Пн','Tu' => 'Вт','We' => 'Ср','Th' => 'Чт','Fr' => 'Пт','Sa' => 'Сб','Su' => 'Вс']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row" style="max-width: 500px; display: flex; align-items: center;">
                                            <div class="col-xs-2"><input type="checkbox" id="ld_<?php echo e($id); ?>" name="ld_opening_hours[<?php echo e($id); ?>][trigger]" style="margin-right: 5px;"<?php echo e(!empty($settings->ld_opening_hours->$id->trigger) ? ' checked' : ''); ?>><label for="ld_<?php echo e($id); ?>"><?php echo e($name); ?></label></div>
                                            <div class="col-xs-2"><label for="ld_opening_hours_<?php echo e($id); ?>_from">From:</label></div>
                                            <div class="col-xs-3">
                                                <select name="ld_opening_hours[<?php echo e($id); ?>][hours_from]" id="ld_opening_hours_<?php echo e($id); ?>_from">
                                                    <?php $__currentLoopData = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($h); ?>"<?php echo e(!empty($settings->ld_opening_hours->$id->hours_from) && $settings->ld_opening_hours->$id->hours_from == $h ? ' selected' : ''); ?>><?php echo e($h); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <select name="ld_opening_hours[<?php echo e($id); ?>][minutes_from]" id="ld_opening_minutes_<?php echo e($id); ?>_from">
                                                    <?php $__currentLoopData = ['00', 15, 30, 45]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($m); ?>"<?php echo e(!empty($settings->ld_opening_hours->$id->minutes_from) && $settings->ld_opening_hours->$id->minutes_from == $m ? ' selected' : ''); ?>><?php echo e($m); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-2"><label for="ld_opening_hours_<?php echo e($id); ?>_to">To:</label></div>
                                            <div class="col-xs-3">
                                                <select name="ld_opening_hours[<?php echo e($id); ?>][hours_to]" id="ld_opening_hours_<?php echo e($id); ?>_to">
                                                    <?php $__currentLoopData = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($h); ?>"<?php echo e(!empty($settings->ld_opening_hours->$id->hours_to) && $settings->ld_opening_hours->$id->hours_to == $h ? ' selected' : ''); ?>><?php echo e($h); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <select name="ld_opening_hours[<?php echo e($id); ?>][minutes_to]" id="ld_opening_minutes_<?php echo e($id); ?>_to">
                                                    <?php $__currentLoopData = ['00', 15, 30, 45]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($m); ?>"<?php echo e(!empty($settings->ld_opening_hours->$id->minutes_to) && $settings->ld_opening_hours->$id->minutes_to == $m ? ' selected' : ''); ?>><?php echo e($m); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Координаты</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="ld_latitude">Широта:</label>
                                            <?php if(old('ld_latitude') !== null): ?>
                                                <input type="text" id="ld_latitude" name="ld_latitude" value="<?php echo old('ld_latitude'); ?>" />
                                                <?php if($errors->has('ld_latitude')): ?>
                                                    <p class="warning" role="alert"><?php echo $errors->first('ld_latitude',':message'); ?></p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <input type="text" id="ld_latitude" name="ld_latitude" value="<?php echo $settings->ld_latitude; ?>" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <label for="ld_longitude">Долгота:</label>
                                            <?php if(old('ld_longitude') !== null): ?>
                                                <input type="text" id="ld_longitude" name="ld_longitude" value="<?php echo old('ld_longitude'); ?>" />
                                                <?php if($errors->has('ld_longitude')): ?>
                                                    <p class="warning" role="alert"><?php echo $errors->first('ld_longitude',':message'); ?></p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <input type="text" id="ld_longitude" name="ld_longitude" value="<?php echo $settings->ld_longitude; ?>" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Социальные сети</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <?php if(old('social[0]') !== null): ?>
                                                <input type="text" class="form-control" name="social[0]" value="<?php echo e(old('social[0]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[0]" value="<?php echo e(isset($settings->social[0]) ? $settings->social[0] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php if(old('social[1]') !== null): ?>
                                                <input type="text" class="form-control" name="social[1]" value="<?php echo e(old('social[1]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[1]" value="<?php echo e(isset($settings->social[1]) ? $settings->social[1] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php if(old('social[2]') !== null): ?>
                                                <input type="text" class="form-control" name="social[2]" value="<?php echo e(old('social[2]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[2]" value="<?php echo e(isset($settings->social[2]) ? $settings->social[2] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right"></label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <?php if(old('social[3]') !== null): ?>
                                                <input type="text" class="form-control" name="social[3]" value="<?php echo e(old('social[3]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[3]" value="<?php echo e(isset($settings->social[3]) ? $settings->social[3] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php if(old('social[4]') !== null): ?>
                                                <input type="text" class="form-control" name="social[4]" value="<?php echo e(old('social[4]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[4]" value="<?php echo e(isset($settings->social[4]) ? $settings->social[4] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php if(old('social[5]') !== null): ?>
                                                <input type="text" class="form-control" name="social[5]" value="<?php echo e(old('social[5]')); ?>" />
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="social[5]" value="<?php echo e(isset($settings->social[5]) ? $settings->social[5] : ''); ?>" />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($user->hasAccess(['seo.settings'])): ?>
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