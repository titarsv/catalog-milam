
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main">
        <?php echo Breadcrumbs::render('page', $page); ?>

        <div class="section contacts-section">
            <div class="section-title">
                <div><?php echo e($seo->name); ?></div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="contacts-top col-md-6">
                        <span><?php echo e($fields['screen_1_title']); ?></span>
                        <div class="contacts-top-inner">
                            <div>
                                <div class="contacts-top-inner__item">
                                    <span><?php echo e(__('Адрес производства')); ?></span>
                                    <?php echo $fields['screen_1_address']; ?>

                                </div>
                                <div class="contacts-top-inner__item">
                                    <span><?php echo e(__('Телефоны')); ?></span>
                                    <?php $__currentLoopData = $fields['screen_1_phones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="tel:<?php echo e($phone->phone); ?>"><?php echo e($phone->phone); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div>
                                <div class="contacts-top-inner__item">
                                    <span><?php echo e(__('Режим работы')); ?></span>
                                    <?php echo $fields['screen_1_schedule']; ?>

                                </div>
                                <div class="contacts-top-inner__item">
                                    <span>Email</span>
                                    <a href="mailto:<?php echo e($fields['screen_1_email']); ?>"><?php echo e($fields['screen_1_email']); ?></a>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="popup-btn" data-mfp-src="#partners-popup"><?php echo e($fields['screen_1_button']); ?></a>
                    </div>
                    <div class="contacts-bot col-md-6">
                        <a href="https://www.instagram.com/<?php echo e($fields['screen_1_instagram']); ?>" target="_blank">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M4.64 0H11.36C13.92 0 16 2.08 16 4.64V11.36C16 12.5906 15.5111 13.7708 14.641 14.641C13.7708 15.5111 12.5906 16 11.36 16H4.64C2.08 16 0 13.92 0 11.36V4.64C0 3.4094 0.488856 2.22919 1.35902 1.35902C2.22919 0.488856 3.4094 0 4.64 0V0ZM4.48 1.6C3.71618 1.6 2.98364 1.90343 2.44353 2.44353C1.90343 2.98364 1.6 3.71618 1.6 4.48V11.52C1.6 13.112 2.888 14.4 4.48 14.4H11.52C12.2838 14.4 13.0164 14.0966 13.5565 13.5565C14.0966 13.0164 14.4 12.2838 14.4 11.52V4.48C14.4 2.888 13.112 1.6 11.52 1.6H4.48ZM12.2 2.8C12.4652 2.8 12.7196 2.90536 12.9071 3.09289C13.0946 3.28043 13.2 3.53478 13.2 3.8C13.2 4.06522 13.0946 4.31957 12.9071 4.50711C12.7196 4.69464 12.4652 4.8 12.2 4.8C11.9348 4.8 11.6804 4.69464 11.4929 4.50711C11.3054 4.31957 11.2 4.06522 11.2 3.8C11.2 3.53478 11.3054 3.28043 11.4929 3.09289C11.6804 2.90536 11.9348 2.8 12.2 2.8ZM8 4C9.06087 4 10.0783 4.42143 10.8284 5.17157C11.5786 5.92172 12 6.93913 12 8C12 9.06087 11.5786 10.0783 10.8284 10.8284C10.0783 11.5786 9.06087 12 8 12C6.93913 12 5.92172 11.5786 5.17157 10.8284C4.42143 10.0783 4 9.06087 4 8C4 6.93913 4.42143 5.92172 5.17157 5.17157C5.92172 4.42143 6.93913 4 8 4V4ZM8 5.6C7.36348 5.6 6.75303 5.85286 6.30294 6.30294C5.85286 6.75303 5.6 7.36348 5.6 8C5.6 8.63652 5.85286 9.24697 6.30294 9.69706C6.75303 10.1471 7.36348 10.4 8 10.4C8.63652 10.4 9.24697 10.1471 9.69706 9.69706C10.1471 9.24697 10.4 8.63652 10.4 8C10.4 7.36348 10.1471 6.75303 9.69706 6.30294C9.24697 5.85286 8.63652 5.6 8 5.6Z"
                                        fill="#004BB3" />
                            </svg>
                            <?php echo e('@'.$fields['screen_1_instagram']); ?></a>
                        <div class="contacts-bot-pic">
                            <?php if(!empty($fields['screen_1_image'])): ?>
                                <picture>
                                    <?php if(!empty($fields['screen_1_image_mob'])): ?>
                                        <source media="(max-width: 574px)" srcset="/images/pixel.webp"
                                                data-original="<?php echo e($fields['screen_1_image_mob']['image']->url_webp([580, 340])); ?>" class="lazy-web" type="image/webp">
                                        <source media="(max-width: 574px)" srcset="/images/pixel.jpg"
                                                data-original="<?php echo e($fields['screen_1_image_mob']['image']->url([580, 340])); ?>" class="lazy-web" type="image/jpg">
                                    <?php endif; ?>
                                    <source srcset="/images/pixel.webp" data-original="<?php echo e($fields['screen_1_image']['image']->url_webp([1080, 640])); ?>" class="lazy-web"
                                            type="image/webp">
                                    <source srcset="/images/pixel.jpg" data-original="<?php echo e($fields['screen_1_image']['image']->url([1080, 640])); ?>" class="lazy-web"
                                            type="image/jpg">
                                    <img src="/images/pixel.jpg" data-original="<?php echo e($fields['screen_1_image']['image']->url([1080, 640])); ?>" class="lazy" alt="<?php echo e(!empty($seo->name) ? $seo->name : $page->name); ?>">
                                </picture>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section contacts-department-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-4 col-xs-12 col">
                        <div class="contacts-department-main">
                            <span><?php echo e($fields['screen_2_title']); ?></span>
                            <p><?php echo e($fields['screen_2_subtitle']); ?></p>
                            <i><svg width="49" height="37" viewBox="0 0 49 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M29.8062 -0.000178947L27.5521 2.25388L42.0313 16.7331L2.45263e-07 16.7331L2.06019e-07 19.9206L42.0313 19.9206L27.5521 34.3998L29.8062 36.6538L48.1327 18.3273L29.8062 -0.000178947Z"
                                            fill="#C4C4C4" />
                                </svg>
                            </i>
                        </div>
                    </div>
                    <div class="col-12 col-md-3  col-sm-4 col-xs-6 col">
                        <div class="contacts-department-more">
                            <span><?php echo e(__('Отдел сбыта')); ?></span>
                            <?php $__currentLoopData = $fields['sales_department_contacts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <?php $__currentLoopData = $contact->phones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="tel:<?php echo e($phone->phone); ?>"><?php echo e($phone->phone); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <a href="mailto:<?php echo e($contact->email); ?>"><?php echo e($contact->email); ?></a>
                                <span><?php echo e($contact->contact); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-sm-4 col-xs-6 col">
                        <div class="contacts-department-more last">
                            <span><?php echo e(__('Отдел снабжения')); ?></span>
                            <?php $__currentLoopData = $fields['supply_department_contacts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <?php $__currentLoopData = $contact->phones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="tel:<?php echo e($phone->phone); ?>"><?php echo e($phone->phone); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <a href="mailto:<?php echo e($contact->email); ?>"><?php echo e($contact->email); ?></a>
                                    <span><?php echo e($contact->contact); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="mfp-hide">
        <div class="popup" id="partners-popup">
            <button title="Close (Esc)" type="button" class="mfp-close">
                <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24.0349 10.792L18.5003 16.3266L12.9657 10.792L10.792 12.9657L16.3266 18.5003L10.792 24.0349L12.9657 26.2087L18.5003 20.6741L24.0349 26.2087L26.2087 24.0349L20.6741 18.5003L26.2087 12.9657L24.0349 10.792Z" fill="white"/>
                </svg>
            </button>
            <span class="popup-title"><?php echo e(__('Станьте нашим партнером!')); ?></span>
            <form class="consult-form ajax_form clear-styles" data-error-title="<?php echo e(__('Ошибка отправки!')); ?>" data-error-message="<?php echo e(__('Попробуйте еще раз через некоторое время.')); ?>" data-success-title="<?php echo e(__('Спасибо за сообщение')); ?>" data-success-message="<?php echo e(__('Наш менеджер свяжется с Вами в ближайшее время.')); ?>">
                <div class="radio-wrapper">
                    <span><?php echo e(__('Связаться как')); ?>:</span>
                    <div class="radio">
                        <input type="radio" name="type" id="rr1">
                        <label for="rr1"><?php echo e(__('Поставщик')); ?></label>
                    </div>
                    <div class="radio">
                        <input type="radio" name="type" id="rr2">
                        <label for="rr2"><?php echo e(__('Дистрибьютор')); ?></label>
                    </div>
                    <div class="radio">
                        <input type="radio" name="type" id="rr3">
                        <label for="rr3"><?php echo e(__('Потребитель')); ?></label>
                    </div>
                </div>
                <div class="input-wrapper">
                    <input class="input" type="text" name="name" placeholder="<?php echo e(__('Имя')); ?>" data-title="Имя" data-validate-required="<?php echo e(__('Обязательное поле')); ?>">
                </div>
                <div class="form-row">
                    <div class="input-wrapper">
                        <input class="input" type="email" name="email" placeholder="Email" data-title="Email" data-validate-required="<?php echo e(__('Обязательное поле')); ?>" data-validate-email="<?php echo e(__('Неправильный email')); ?>">
                    </div>
                    <div class="input-wrapper">
                        <input class="input" type="tel" name="phone" placeholder="<?php echo e(__('Телефон')); ?>" data-title="Телефон" data-validate-required="<?php echo e(__('Обязательное поле')); ?>" data-validate-uaphone="<?php echo e(__('Неправильный номер')); ?>">
                    </div>
                </div>
                <div class="input-wrapper">
                    <input class="input" type="text" name="comment" placeholder="<?php echo e(__('Текст сообщения')); ?>" data-title="Сообщение" data-validate-required="<?php echo e(__('Обязательное поле')); ?>">
                </div>
                <button type="submit" class="btn btn-tr"><?php echo e(__('Отправить')); ?></button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/pages/contacts.blade.php ENDPATH**/ ?>