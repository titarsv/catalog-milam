<div class="header" style="text-align: center;">
    <img src="{!! url('/images/logo.png') !!}" alt="logo" title="{{ env('APP_URL') }}" width="228" height="60" />

    @if(!empty($email) && isset($comment))
        <p style="font-size: 20px;">Новое сообщение на сайте {{ env('APP_URL') }}!</p>
    @else
        <p style="font-size: 20px;">Поступил заказ обратного звонка на сайте {{ env('APP_URL') }}!</p>
    @endif

    <p style="font-size: 20px;">Имя:<b>{{ $name }}</b></p>
    @if(!empty($phone))
    <p style="font-size: 20px;">Телефон:<b>{{ $phone }}</b></p>
    @endif
    @if(!empty($email))
        <p style="font-size: 20px;">Email:<b>{{ $email }}</b></p>
    @endif
    @if(isset($product))
        <p>Заявка пришла по следующему товару: <a href="{{ url('/product/'.$product->link()) }}">{{ $product->name }} (Код товара: {{ $product->sku }})</a></p>
    @endif
    @if(isset($comment))
        <p><b>Пользователь оставил следующий комментарий:</b><br>{{ $comment }}</p>
    @endif
</div>