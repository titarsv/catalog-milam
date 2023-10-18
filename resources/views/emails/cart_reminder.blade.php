<div class="header" style="text-align: center;">
    <img src="{!! url('/images/logo.png') !!}" alt="logo"  title="Milam" width="174" height="50" />
    <p style="font-size: 20px;">Добрый день{{ !empty($user->first_name)?', '.$user->first_name.(!empty($user->last_name)?' '.$user->last_name:''):'' }}!</p>
    <p style="font-size: 20px;">На сайте <a href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a> Вы заказали товар(ы):</p>
</div>

<table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse" width="100%">
    <tbody>
        <tr style="background:#1185c2; color: #fff; text-transform:uppercase;">
            <td align="center" height="40px" width="20%">Изображение товара</td>
            <td align="center" height="40px" width="40%">Наименование товара</td>
            <td align="center" height="40px" width="20%">Количество</td>
            <td align="center" height="40px" width="20%">Цена</td>
        </tr>
        @foreach($products as $item)
            <tr>
                <td align="center" width="20%" height="150px">
                    <a href="{!! url('/product/' . $item['product']->url_alias) !!}">
                        <img src="{!! url($item['product']->image->url()) !!}" alt="product-image" width="100px" height="100px" title="{!! $item['product']->name !!}">
                    </a>
                </td>
                <td align="center" width="40%" height="150px">
                    <a href="{!! url('/product/' . $item['product']->url_alias) !!}" style="color: #333;" onmouseover="this.style.color='#333'">{!! $item['product']->name !!}</a>
                </td>
                <td align="center" width="20%" height="150px">
                    {!! $item['quantity'] !!}
                </td>
                <td align="center" width="20%" height="150px">
                    {!! $item['price'] * $item['quantity'] !!} грн
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<p style="font-size:16px; color: #333;">Чтобы завершить заказ - перейдите на страницу <a href="{{ env('APP_URL') }}/checkout">{{ env('APP_URL') }}/checkout</a>.</p>

<p style="font-size:16px; color: #333;">Благодарим за покупку!</p>