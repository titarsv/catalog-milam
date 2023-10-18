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
    <div class="container">
        <div class="policy">
            <h1>{{ !empty($seo->name) ? $seo->name : $page->name }}</h1>
            {!! html_entity_decode($page->body) !!}
        </div>
    </div>
</main>
@endsection