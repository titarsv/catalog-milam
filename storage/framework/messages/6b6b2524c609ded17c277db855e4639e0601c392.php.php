
<?php $__env->startSection('meta'); ?>
    <title><?php echo e(__('Ошибка 404. Страница не найдена')); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <main class="main">
        <div class="not-found">
            <div class="breadcrumbs">
                <div class="container">
                    <ul>
                        <li><a href="<?php echo e(base_url('/')); ?>"><?php echo e(__('Главная')); ?></a></li>
                        <li><span><?php echo e(__('Страница не найдена')); ?></span></li>
                    </ul>
                </div>
            </div>
            <span class="not-found-title">404</span>
            <p class="not-found-text"><?php echo e(__('Простите, но у нас возникли проблемы с поиском страницы, которую Вы запрашиваете.')); ?></p>
            <p class="not-found-text"><?php echo e(__('Вы можете связаться с нашими менеджерами в форме внизу страницы либо:')); ?></p>
            <a class="not-found-link" href="<?php echo e(base_url('/')); ?>"><?php echo e(__('Перейти на главную')); ?></a>
            <picture>
                <source media="(max-width: 574px)" srcset="/images/pixel.webp"
                        data-original="/images/not-found-bg-mob.webp" class="lazy-web" type="image/webp">
                <source media="(max-width: 574px)" srcset="/images/pixel.jpg"
                        data-original="/images/not-found-bg-mob.jpg" class="lazy-web" type="image/jpg">
                <source srcset="/images/pixel.webp" data-original="/images/not-found-bg.webp" class="lazy-web"
                        type="image/webp">
                <source srcset="/images/pixel.jpg" data-original="/images/not-found-bg.jpg" class="lazy-web"
                        type="image/jpg">
                <img src="/images/pixel.jpg" data-original="/images/not-found-bg.jpg" class="lazy" alt="">
            </picture>
        </div>
        <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', ['wrapper_class' => 'not-found__wrapper'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>