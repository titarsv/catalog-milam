@if($products->count())
    @foreach($products as $product)
        @include('public.layouts.product', ['product' => $product])
    @endforeach
@else
    <div class="col"><p class="note-msg"><span>{{ __('Нет товаров, соответствующих Вашему выбору.') }}</span></p></div>
@endif