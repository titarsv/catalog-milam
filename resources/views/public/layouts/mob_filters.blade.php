{{--@if(isset($filter['categories']))--}}
{{--    @if(!empty($filter['categories']['values']))--}}
{{--        <div class="categories-filter__block">--}}
{{--            <div class="categories-filter__head">--}}
{{--                {{ __('Продукция') }}--}}
{{--            </div>--}}
{{--            <div class="categories-filter__body">--}}
{{--                @foreach($filter['categories']['values'] as $value_id => $value)--}}
{{--                    <a href="{{ $value['url'] }}" class="filter{{ $value['checked'] ? ' checked' : '' }}" data-id="{{ $value_id }}" data-name="{{ $value['name'] }}">{{ $value['name'] }}</a>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--@endif--}}




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





{{--<div class="categories-filter__block">--}}
{{--    <div class="categories-filter__head">--}}
{{--        Продукция--}}
{{--    </div>--}}
{{--    <div class="categories-filter__body">--}}
{{--        --}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Засіб для прання</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Засоби для виведення плям</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Гелі для прання</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Пральні порошки</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Засіб для догляду за ванною кімнатою</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Для чистоти та дезинфекції</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Санітарно-гігієнічні засоби</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Для прочищення труб</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Універсальні засоби</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Засіб для кухні</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Для чищення плит</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Засоби від накипу</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Універсальні засоби</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Сухі засоби для чищення</a>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Засіб для дому</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Засоби від цвілі</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Поліролі</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Для килимів</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Універсальні засоби</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Для миття підлоги</a>--}}
{{--                <a href="javascript:void(0)" class="filter"> Для миття скла</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Рідке мило для рук</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Антибактеріальний комплекс</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Ароматизоване</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Гліцеринове</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Крем–мило</a>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Засіб для миття посуду</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Бальзам</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Лимон</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Яблуко</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="filter-wrapper">--}}
{{--            <a class="filter" href="javascript:void(0)">Для відбілювання та дезінфекції</a>--}}
{{--            <span class="has-children"></span>--}}
{{--            <div class="subcategory">--}}
{{--                <a href="javascript:void(0)" class="filter">Білизна</a>--}}
{{--                <a href="javascript:void(0)" class="filter">Засоби для дезинфекції</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


@if(isset($filter['attributes']))
    @foreach($filter['attributes'] as $attribute_id => $attribute)
        @if(!empty($attribute['values']))
            <div class="categories-filter__block">
                <div class="categories-filter__head">
                    {{ $attribute['name'] }}
                </div>
                <div class="categories-filter__body">
                    @foreach($attribute['values'] as $value_id => $value)
                        <div class="filter">
                            <input type="checkbox" class="js_mob_attribute_checkbox_filter" name="attributes[]" value="{{ $value_id }}" id="mob_a{{ $value_id }}" data-id="{{ $value_id }}"{{ $value['checked'] ? ' checked' : '' }}>
                            <label for="mob_a{{ $value_id }}">{{ $value['name'] }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif

