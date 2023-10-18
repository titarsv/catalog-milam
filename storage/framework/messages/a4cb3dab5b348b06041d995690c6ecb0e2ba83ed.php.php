<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="<?php echo e(str_replace(['http://', 'https://'], '', env('APP_URL'))); ?>" width="228" height="60" />
</div>

<h1>Здравствуйте, <strong><?php echo $user['last_name'] or ''; ?> <?php echo $user['first_name']; ?></strong>!</h1>
<p>Добро пожаловать в Интернет-магазин <?php echo e(env('APP_URL')); ?>!</p>
<p>Для входа в <a href="<?php echo url('/user'); ?>">личный кабинет</a> используйте свой e-mail и пароль, указанный при регистрации.</p>