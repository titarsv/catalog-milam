@extends('public.layouts.main')
@section('page_vars')
    <script>
        dataLayer = [{
            "page": {
                "type": "category",
                "categoryId": "{{ $category->id }}"
            }
        }];
    </script>
    @include('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main class="main">
        {!! Breadcrumbs::render('categories', $category) !!}
        <div class="section catalog-section">
            <div class="section-title">
                <h1>{{ $seo->name }}</h1>
            </div>
            <div class="container">
                <div class="products-wrapper">
                    @foreach($categories as $subcategory)
                    <a href="{{ $subcategory->link() }}" class="product-item">
                        <div class="product-pic">
                            <svg width="235" height="244" viewBox="0 0 235 244" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="path1" d="M130.128 22.5249C171.811 29.0213 200.162 52.6834 212.976 82.328C225.802 112.001 223.163 147.898 202.368 179.066L206.527 181.841C228.224 149.322 231.093 111.639 217.566 80.3442C204.026 49.0212 174.164 24.3276 130.898 17.5845L130.128 22.5249Z" fill="#78BEE1"/>
                                <path class="path2" d="M233.934 121.105C233.934 182.506 184.463 232.271 123.45 232.271C62.438 232.271 12.9673 182.506 12.9673 121.105C12.9673 89.8149 25.8143 61.5466 46.4914 41.3447C66.3879 21.9055 93.5298 9.93945 123.45 9.93945C155.619 9.93945 184.577 23.7716 204.771 45.8523C222.877 65.6511 233.934 92.0774 233.934 121.105Z" stroke="#DFDFDF" stroke-width="2"/>
                                <path class="path3" d="M129.703 233.334L129.016 223.358L129.016 223.358L129.703 233.334ZM10.6538 129.428L0.67727 130.112L10.6538 129.428ZM129.016 223.358C73.0199 227.211 24.4805 184.867 20.6304 128.743L0.67727 130.112C5.2814 197.228 63.3421 247.925 130.389 243.311L129.016 223.358ZM20.6304 128.743C16.7802 72.618 59.0836 24.0195 115.082 20.1659L113.709 0.213049C46.663 4.82701 -3.92674 62.9983 0.67727 130.112L20.6304 128.743ZM195.824 191.825C178.895 209.723 155.497 221.535 129.016 223.358L130.389 243.311C162.079 241.13 190.113 226.968 210.354 205.568L195.824 191.825Z" fill="#DFDFDF"/>
                            </svg>
                            {!! $subcategory->image == null ? '<picture class="pic-main">
    <source data-src="/images/larchik/no_image.webp" srcset="/images/pixel.webp" type="image/webp">
    <source data-src="/images/larchik/no_image.jpg" srcset="/images/pixel.jpg" type="image/jpeg">
    <img src="/images/pixel.jpg" alt="'.$product->name.' ">
    </picture>' : $subcategory->image->webp([360, 360], ['picture_class' => 'pic-main', 'alt' => $subcategory->name], 'static') !!}
                        </div>
                        <span class="product-title">{{ $subcategory->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @include('public.layouts.consult')
    </main>
@endsection