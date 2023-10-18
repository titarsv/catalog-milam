@extends('public.layouts.main', ['pagination' => $products, 'root_category' => $category->get_root_category()])
@section('page_vars')
    <script>
        dataLayer = [{
            "page": {
                "type": "category",
                "categoryId": "{{ $category->id }}"
            }
        }];
    </script>
    <script>
        var fbqProductsData = [];
    </script>
    <!-- Код тега ремаркетинга Google -->
    <script>
        var google_tag_params = {
            ecomm_prodid: [{{ implode(', ', $products->pluck('id')->toArray()) }}],
            ecomm_pagetype: 'category',
            ecomm_totalvalue: [{{ implode(', ', $products->pluck('price')->toArray()) }}],
        };
    </script>
    {{--    @include('public.layouts.microdata.category', ['category' => $category, 'title' => $seo->meta_title, 'total' => $products->total()])--}}
    @include('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main class="main">
        {!! Breadcrumbs::render('categories', $category) !!}
        <div class="section categories-top">
            <div class="section-title">
                <h1>{{ $seo->name }}</h1>
            </div>
            <div class="container">
                <div class="categories-top__wrapper">
                    @foreach($categories as $subcategory)
                    <a href="{{ $subcategory->link() }}" class="categories-top__item{{ $subcategory->id == $category->id || $subcategory->id == $category->parent_id ? ' current' : ' inactive' }}">
                        <div class="categories-top__pic">
                            {!! $subcategory->image == null ? '<picture class="pic-main">
    <source data-src="/images/larchik/no_image.webp" srcset="/images/pixel.webp" type="image/webp">
    <source data-src="/images/larchik/no_image.jpg" srcset="/images/pixel.jpg" type="image/jpeg">
    <img src="/images/pixel.jpg" alt="'.$product->name.' ">
    </picture>' : $subcategory->image->webp([360, 360], ['picture_class' => 'pic-main', 'alt' => $subcategory->name], 'static') !!}
                        </div>
                        <span class="categories-top__title">{{ $subcategory->name }}</span>
                    </a>
                    @endforeach
                </div>
                <div class="categories-filters__mob">
                    <div class="categories-filters__btn btn-filters__mob">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.8">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 2V3.67L10 8.429V14H6V8.429L1 3.669V2H15ZM7 8V13H9V8L14 3.24V3H2V3.24L7 8Z" fill="#003174"/>
                            </g>
                        </svg>
                        <span>{{ __('Фильтры') }}</span>
                        <svg class="arrow" width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.4">
                                <path d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z" fill="#383838"/>
                            </g>
                        </svg>
                    </div>
                    @include('public.layouts.selected_filters')
                </div>
            </div>
        </div>
        <div class="section categories-main">
            <div class="container">
                <div class="categories-main__content">
                    <div class="categories-main__top">
                        <div class="col-left">
                            <div class="categories-filters__btn btn-filters">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g opacity="0.8">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15 2V3.67L10 8.429V14H6V8.429L1 3.669V2H15ZM7 8V13H9V8L14 3.24V3H2V3.24L7 8Z" fill="#003174"/>
                                    </g>
                                </svg>
                                <span>{{ __('Отобразить фильтры') }}</span>
                                <svg class="arrow" width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g opacity="0.4">
                                        <path d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z" fill="#383838"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="col-right">
                            @include('public.layouts.selected_filters')
                        </div>
                    </div>
                    <div class="categories-main__wrapper">
                        <div class="categories-filters">
                            <div class="categories-filters__main" id="js_filters">
                                <input type="hidden" value="{{ $category->id }}" id="js_category">
                                <input type="hidden" value="{{ !empty($limit) ? $limit : 12 }}" id="js_limit">
                                @include('public.layouts.filters')
                            </div>
                        </div>
                        <div class="categories-items" id="js_products_wrapper">
                            @include('public.layouts.products_list')
                        </div>
                    </div>
                </div>
            </div>
            <div class="categories-pagination">
                @include('public.layouts.pagination', ['paginator' => $products])
            </div>
        </div>
        @include('public.layouts.consult')
    </main>
    <div class="overlay"></div>
    <div class="mobile-filters__wrapper">
        <div class="mobile-filters__head mobile-filters__close">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.72599 6.523C3.75657 6.56742 3.79749 6.60373 3.84522 6.62882C3.89296 6.65391 3.94607 6.66701 3.99999 6.66701C4.05392 6.66701 4.10703 6.65391 4.15476 6.62882C4.2025 6.60373 4.24341 6.56742 4.27399 6.523L7.274 2.18967C7.30872 2.13969 7.32908 2.08115 7.33287 2.0204C7.33666 1.95966 7.32373 1.89904 7.29549 1.84513C7.26725 1.79122 7.22477 1.74608 7.17267 1.71462C7.12058 1.68316 7.06085 1.66657 7 1.66667H0.999994C0.939275 1.66692 0.879774 1.68372 0.827888 1.71526C0.776002 1.74679 0.733695 1.79188 0.705517 1.84567C0.677339 1.89945 0.664356 1.9599 0.667964 2.02051C0.671572 2.08112 0.691634 2.13961 0.725994 2.18967L3.72599 6.523Z" fill="#fff"/>
            </svg>
            {{ __('Фильтры') }}
        </div>
        <div class="mobile-filters__body js_mob_filters">
            @include('public.layouts.mob_filters')
        </div>
        <div class="mobile-filters__footer">
            <span class="btn btn-tr mobile-filters__close">{{ __('Назад') }}</span>
            <span class="btn js_submit_filters">{{ __('Готово') }}</span>
        </div>
    </div>
@endsection
