@if($breadcrumbs)
    @include('public.layouts.microdata.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
    <div class="breadcrumbs">
        <div class="container">
            <ul>
                @foreach($breadcrumbs as $i => $breadcrumb)
                    @if(!empty($breadcrumb->url) && $i != count($breadcrumbs) - 1)
                        <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                    @else
                        <li><span>{{ $breadcrumb->title }}</span></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif
