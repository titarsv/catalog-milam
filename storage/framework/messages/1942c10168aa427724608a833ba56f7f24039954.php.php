

<?php $__env->startSection('meta'); ?>
    <title><?php echo e(trans('app.Personal_information')); ?></title>
    <meta name="description" content="<?php echo $settings->meta_description; ?>">
    <meta name="keywords" content="<?php echo $settings->meta_keywords; ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main>
        <div class="section-account">
            <div class="container hidden-sm hidden-md hidden-lg">
                <?php echo Breadcrumbs::render('user'); ?>

            </div>
            <div class="container">
                <div class="col">
                    <div class="account-wrapper tabs-wrapper">
                        <aside class="account-sidebar">
                            <img src="/images/acc-logo.svg" class="lazy" alt="">
                            <div class="account-hello"><?php echo e(trans('app.Hello')); ?>,
                                <span><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>!</span>
                            </div>
                            <ul class="account-tabs">
                                <li class="active"><span><?php echo e(trans('app.Personal_information')); ?></span></li>
                                <li><a href="<?php echo e(base_url('/user/wishlist')); ?>"><?php echo e(trans('app.Wish_list')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/history')); ?>"><?php echo e(trans('app.Order_history')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/recommend')); ?>"><?php echo e(trans('app.Recommendations')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/payment')); ?>"><?php echo e(trans('app.Payment_and_delivery')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/user/contacts')); ?>"><?php echo e(trans('app.Contacts')); ?></a></li>
                                <li><a href="<?php echo e(base_url('/logout')); ?>"><?php echo e(trans('app.Exit')); ?></a></li>
                            </ul>
                        </aside>
                        <div class="account-main">
                            <div class="account-content tabs-content active">
                                <span class="account-title"><?php echo e(trans('app.Personal_information')); ?></span>
                                <span class="account-descr"></span>
                                <div class="personal-info">
                                    <div class="personal-info__form-wrapper personal-info__left">
                                        <span class="personal-info__title"><?php echo e(trans('app.Contact_Information')); ?></span>
                                        <form method="post" class="personal-info__form saved">
                                            <?php echo csrf_field(); ?>

                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Last_Name')); ?></label>
                                                <input type="text" name="last_name" value="<?php echo e($user->last_name); ?>" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.First_Name')); ?></label>
                                                <input type="text" name="first_name" value="<?php echo e($user->first_name); ?>" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Surname')); ?></label>
                                                <input type="text" name="patronymic" value="<?php echo e($user->patronymic); ?>" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label>E-mail</label>
                                                <input type="text" name="email" value="<?php echo e($user->email); ?>" required disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Date_of_birth')); ?></label>
                                                <input type="date" name="user_birth" value="<?php echo e(is_object($user->user_data) ? $user->user_data->user_birth : ''); ?>" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Phone')); ?></label>
                                                <input type="text" name="phone" value="<?php echo e(is_object($user->user_data) ? $user->user_data->phone : ''); ?>" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.town')); ?></label>
                                                <input type="text" name="city" value="<?php echo e(is_object($user->user_data) ? $user->user_data->city : ''); ?>" disabled>
                                            </div>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Gender')); ?></label>
                                                <div class="radio-wrapper">
                                                    <input type="radio" name="gender" value="0" id="female"<?php echo e(is_object($user->user_data) && $user->user_data->gender == 0 ? ' checked' : ''); ?> disabled>
                                                    <label for="female"><?php echo e(trans('app.Women')); ?></label>
                                                    <input type="radio" name="gender" value="1" id="male"<?php echo e(is_object($user->user_data) && $user->user_data->gender == 1 ? ' checked' : ''); ?> disabled>
                                                    <label for="male"><?php echo e(trans('app.Men')); ?></label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn"><?php echo e(trans('app.SAVE')); ?></button>
                                        </form>
                                        <div class="edit-btn"><?php echo e(trans('app.to_change')); ?></div>
                                    </div>
                                    <div class="personal-info__form-wrapper personal-info__right">
                                        <span class="personal-info__title"><?php echo e(trans('app.Change_password')); ?></span>
                                        <form action="<?php echo e(base_url('/user/updatepassword')); ?>" method="post" class="personal-info__form password-form saved">
                                            <?php echo csrf_field(); ?>

                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Old_password')); ?></label>
                                                <input type="password" name="old_password" placeholder="*********" disabled>
                                            </div>
                                            <?php if($errors->has('old_password')): ?>
                                                <p class="warning" role="alert"><?php echo e($errors->first('old_password',':message')); ?></p>
                                            <?php endif; ?>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.New_password')); ?></label>
                                                <input type="password" name="password" placeholder="*********" disabled>
                                            </div>
                                            <?php if($errors->has('password')): ?>
                                                <p class="warning" role="alert"><?php echo e($errors->first('password',':message')); ?></p>
                                            <?php endif; ?>
                                            <div class="input-wrapper">
                                                <label><?php echo e(trans('app.Confirm_password')); ?></label>
                                                <input type="password" name="password_confirmation" placeholder="*********" disabled>
                                            </div>
                                            <button type="submit" class="btn"><?php echo e(trans('app.SAVE')); ?></button>
                                        </form>
                                        <div class="edit-btn"><?php echo e(trans('app.to_change')); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>