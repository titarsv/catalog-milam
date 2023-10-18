@if(!empty($categories))
    <div class="categories-filter__block">
        <div class="categories-filter__head">
            {{ __('Продукция') }}
        </div>
        <div class="categories-filter__body">
            @foreach($categories as $child)
                @if(!empty($child->children->count()))
                    <div class="filter-wrapper{{ in_array($child->id, $current_categories) ? ' open' : '' }}">
                        <a href="{{ $child->link() }}" class="filter{{ $child->id == $category->id ? ' checked' : '' }}" data-id="{{ $child->id }}" data-name="{{ $child->name }}">{{ $child->name }}</a>
                        <span class="has-children"></span>
                        <div class="subcategory"{!! in_array($child->id, $current_categories) ? ' style="display:block;"' : '' !!}>
                            @foreach($child->children as $subcat)
                                <a href="{{ $subcat->link() }}" class="filter{{ $subcat->id == $category->id  ? ' checked' : '' }}" data-id="{{ $subcat->id }}" data-name="{{ $subcat->name }}">{{ $subcat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $child->link() }}" class="filter{{ in_array($child->id, $current_categories) ? ' checked' : '' }}" data-id="{{ $child->id }}" data-name="{{ $child->name }}">{{ $child->name }}</a>
                @endif
            @endforeach
        </div>
    </div>
@endif
{{--@if(isset($filter['categories']))--}}
    {{--@if(!empty($filter['categories']['values']))--}}
        {{--<div class="categories-filter__block">--}}
            {{--<div class="categories-filter__head">--}}
                {{--{{ __('Продукция') }}--}}
            {{--</div>--}}
            {{--<div class="categories-filter__body">--}}
                {{--@foreach($filter['categories']['values'] as $value_id => $value)--}}
                    {{--<a href="{{ $value['url'] }}" class="filter{{ $value['checked'] ? ' checked' : '' }}" data-id="{{ $value_id }}" data-name="{{ $value['name'] }}">{{ $value['name'] }}</a>--}}
                {{--@endforeach--}}
                {{--<div class="filter-wrapper open">--}}
                    {{--<a href="" class="filter">Засіб для прання</a>--}}
                    {{--<span class="has-children"></span>--}}
                    {{--<div class="subcategory" style="display:block;">--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="filter-wrapper">--}}
                    {{--<a href="" class="filter">Засіб для прання</a>--}}
                    {{--<span class="has-children"></span>--}}
                    {{--<div class="subcategory">--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                        {{--<a href="" class="filter">Засіб для прання</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endif--}}
{{--@endif--}}
@if(isset($filter['attributes']))
    @foreach($filter['attributes'] as $attribute_id => $attribute)
        @if(!empty($attribute['values']))
            <div class="categories-filter__block">
                <div class="categories-filter__head">
                    {{ $attribute['name'] }}
                </div>
                <div class="categories-filter__body">
                    @foreach($attribute['values'] as $value_id => $value)
                        <a href="{{ $value['url'] }}" class="filter check{{ $value['checked'] ? ' checked' : '' }}" data-id="{{ $value_id }}" data-name="{{ $value['name'] }}">{{ $value['name'] }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif
