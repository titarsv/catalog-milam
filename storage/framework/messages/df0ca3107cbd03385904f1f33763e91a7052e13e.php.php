<div class="section consult-section">
    <div class="container">
        <div class="row consult-wrapper">
            <div class="col">
                <div class="consult-title"><?php echo e(__('Свяжитесь с нами!')); ?></div>
                <span class="consult-descr"><?php echo e(__('Заполните форму и наши менеджеры ответят вам')); ?></span>
                <a href="<?php echo e(base_url('/contacts')); ?>" class="consult-link"><?php echo e(__('Контакты')); ?></a>
            </div>
            <div class="col">
                <form class="consult-form ajax_form clear-styles" data-error-title="<?php echo e(__('Ошибка отправки!')); ?>" data-error-message="<?php echo e(__('Попробуйте еще раз через некоторое время.')); ?>" data-success-title="<?php echo e(__('Спасибо за сообщение')); ?>" data-success-message="<?php echo e(__('Наш менеджер свяжется с Вами в ближайшее время.')); ?>">
                    <div class="radio-wrapper">
                        <span><?php echo e(__('Связаться как')); ?>:</span>
                        <div class="radio">
                            <input type="radio" name="type" value="Поставщик" id="p1">
                            <label for="p1"><?php echo e(__('Поставщик')); ?></label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="type" value="Дистрибьютор" id="p2">
                            <label for="p2"><?php echo e(__('Дистрибьютор')); ?></label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="type" value="Потребитель" id="p3">
                            <label for="p3"><?php echo e(__('Потребитель')); ?></label>
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
    </div>
</div>