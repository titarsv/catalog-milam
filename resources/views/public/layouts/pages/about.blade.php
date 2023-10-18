@extends('public.layouts.main')
@section('page_vars')
    @include('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => '/images/logo.png'
     ])
@endsection

@section('content')
    <main class="main">
        {!! Breadcrumbs::render('page', $page) !!}
        <div class="section about-section">
            <div class="section-title">
                <div>{{ $seo->name }}</div>
            </div>
            <div class="container">
                <div class="about-item row">
                    <div class="col-md-7">
                        <div class="about-text">
                            <span>{{ $fields['screen_1_title'] }}</span>
                            {!! $fields['screen_1_text'] !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="about-pic">
                            {!! $fields['screen_1_image']['image']->webp([890, 800], [], 'static') !!}
                        </div>
                    </div>
                </div>
                <div class="about-item row">
                    <div class="col-md-5">
                        <div class="about-pic">
                            {!! $fields['screen_2_image_1']['image']->webp([658, 550], ['picture_class' => 'about-pic-logo-milam'], 'static') !!}
                            {!! $fields['screen_2_image_2']['image']->webp([890, 800], [], 'static') !!}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="about-text">
                            <span>{{ $fields['screen_2_title'] }}</span>
                            {!! $fields['screen_2_text'] !!}
                        </div>
                    </div>
                </div>
                <div class="about-item row">
                    <div class="col-md-7">
                        <div class="about-text">
                            <span>{{ $fields['screen_3_title'] }}</span>
                            {!! $fields['screen_3_text'] !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="about-pic">
                            {!! $fields['screen_3_image_1']['image']->webp([743, 352], ['picture_class' => 'about-pic-logo-milam-chemical'], 'static') !!}
                            {!! $fields['screen_3_image_2']['image']->webp([890, 800], [], 'static') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('public.layouts.consult')
    </main>
@endsection