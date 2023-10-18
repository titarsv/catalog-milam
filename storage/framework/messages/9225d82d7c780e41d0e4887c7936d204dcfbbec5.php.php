<body>
<main class="clearfix">
    <aside id="sidebar">
        <div class="logo">
            <a href="/admin">
                <img src="/images/logo-milam.png" alt="logo" style="height: 50px; margin: 0 auto;" />
            </a>
        </div>
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="bottom-logo">
            <a href="http://triplefork.com.ua/" target="_blank">

            </a>
            <span><a href="https://triplefork.it" target="_blank" style="color: #fff">&copy; &laquo;Triplefork&raquo; <?php echo e(date('Y')); ?></a></span>
        </div>
    </aside>
    <div id="content">
        <div class="row">
            <nav class="navbar col-sm-12">
                <div class="navbar-title">
                    <?php echo $__env->yieldContent('title'); ?>
                </div>
                <ul class="nav">
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown" data-toggle="dropdown">
                            <img src="/images/larchik/flags/ru.png" alt="Русский" title="Русский">
                        </a>
                        <ul class="dropdown-menu">
                            <?php $__currentLoopData = $locales_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $lang_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="js_lang_switcher<?php echo e($lang == $main_lang ? ' active' : ''); ?>" data-lang="<?php echo e($lang); ?>">
                                    <a href="javascript:void(0)">
                                        <img src="/images/larchik/flags/<?php echo e($lang); ?>.png" alt="<?php echo e($lang_name); ?>" title="<?php echo e($lang_name); ?>"> <?php echo e($lang_name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge"><?php echo $new_orders + $new_reviews; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/orders">Заказы <span class="badge"><?php echo $new_orders; ?></span></a></li>
                            <li><a href="/admin/reviews">Отзывы <span class="badge"><?php echo $new_reviews; ?></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/admin/settings" data-toggle="tooltip" data-placement="bottom" title="Настройки">
                            <i class="fa fa-gears"></i>
                        </a>
                    </li>
                    <li>
                        <p><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></p>
                    </li>

                    <li>
                        <a href="/" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Перейти в магазин">
                            <i class="fa fa-television" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" data-toggle="tooltip" data-placement="bottom" title="Выйти">
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="content-container">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</main>


<?php echo $__env->yieldContent('before_footer'); ?>
<?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>