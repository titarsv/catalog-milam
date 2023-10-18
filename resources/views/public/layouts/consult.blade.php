<div class="section consult-section">
    <div class="container">
        <div class="row consult-wrapper">
            <div class="col">
                <div class="consult-title">{{ __('Свяжитесь с нами!') }}</div>
                <span class="consult-descr">{{ __('Заполните форму и наши менеджеры ответят вам') }}</span>
                <a href="{{ base_url('/contacts') }}" class="consult-link">{{ __('Контакты') }}</a>
            </div>
            <div class="col">
                <form class="consult-form ajax_form clear-styles" data-error-title="{{ __('Ошибка отправки!') }}" data-error-message="{{ __('Попробуйте еще раз через некоторое время.') }}" data-success-title="{{ __('Спасибо за сообщение') }}" data-success-message="{{ __('Наш менеджер свяжется с Вами в ближайшее время.') }}">
                    <div class="radio-wrapper">
                        <span>{{ __('Связаться как') }}:</span>
                        <div class="radio">
                            <input type="radio" name="type" value="Поставщик" id="p1" data-title="Связаться как">
                            <label for="p1">{{ __('Поставщик') }}</label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="type" value="Дистрибьютор" id="p2" data-title="Связаться как">
                            <label for="p2">{{ __('Дистрибьютор') }}</label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="type" value="Потребитель" id="p3" data-title="Связаться как">
                            <label for="p3">{{ __('Потребитель') }}</label>
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <input class="input" type="text" name="name" placeholder="{{ __('Имя') }}" data-title="Имя" data-validate-required="{{ __('Обязательное поле') }}">
                    </div>
                    <div class="form-row">
                        <div class="input-wrapper">
                            <input class="input" type="text" name="email" placeholder="Email" data-title="Email"
                                   data-validate-required="{{ __('Обязательное поле') }}" data-validate-email="{{ __('Неправильный email') }}">
                        </div>
                        <div class="input-wrapper">
                            <input class="input" type="tel" name="phone" placeholder="{{ __('Телефон') }}" data-title="Телефон" data-validate-required="{{ __('Обязательное поле') }}" data-validate-uaphone="{{ __('Неправильный номер') }}">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <input class="input" type="text" name="comment" placeholder="{{ __('Текст сообщения') }}" data-title="Сообщение" data-validate-required="{{ __('Обязательное поле') }}">
                    </div>
                    <button type="submit" class="btn btn-tr">{{ __('Отправить') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
