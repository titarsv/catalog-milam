<div class="panel-group">
    <div class="panel panel-default">
        <table class="table table-hover table-condensed">
            <thead>
                <tr class="success">
                    <td align="center" style="min-width: 100px">Фото</td>
                    <td>Артикул</td>
                    <td>Цена</td>
                    <td style="max-width: 220px;">Название</td>
                    <td align="center" class="hidden-xs">Категория</td>
                    <td align="center">Наличие</td>
                    <td align="center">Действия</td>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr id="product-{{ $product->id }}">
                        <td align="center">
                            @if(!empty($product->image))
                            <img src="{{ $product->image->url([100, 100]) }}"
                                 alt="{{ $product->image->title }}"
                                 class="img-thumbnail">
                            @else
                                <img src="/uploads/no_image.jpg"
                                     alt="no_image"
                                     class="img-thumbnail">
                            @endif
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->original_price }}</td>
                        <td style="max-width: 220px;white-space: normal;">{{ $product->name }}</td>
                        <td align="center" class="hidden-xs product-categories">
                            @foreach($product->categories as $category)
                                <span class="product-category category-{{ $category->id }}">{{ $category->name }}</span><br>
                            @endforeach
                        </td>
                        <td class="status" align="center">
                            <span class="{!! $product->stock ? 'on' : 'off' !!}" data-id="{{ $product->id }}" style="cursor: pointer;">
                                <span class="runner"></span>
                            </span>
                        </td>
                        <td class="actions" align="center">
                            <a class="btn btn-primary" href="/admin/products/edit/{{ $product->id }}" target="_blank">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger remove-from-action" data-id="{{ $product->id }}">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" align="center">Нет добавленных товаров!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($products->count())
        <div class="panel-footer text-right">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>