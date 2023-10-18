<div class="header" style="text-align: center;">
    <img src="<?php echo url('/images/logo.png'); ?>" alt="logo" title="<?php echo e(str_replace(['http://', 'https://'], '', env('APP_URL'))); ?>" width="228" height="60" />
    <p style="font-size: 20px;">Восстановление пароля</p>
</div>
<p>Для того, чтобы восстановить пароль, пожалуйста, перейдите по <a href="<?php echo e(url('/lostpassword') . '?id=' . $user->id . '&code=' . $reminder->code); ?>">данной</a> ссылке.</p>