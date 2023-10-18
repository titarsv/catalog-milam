<meta property="og:locale" content="uk_UA" />
<meta name="twitter:card" content="summary">
<meta name="twitter:creator" content="pirana_td@ukr.net">
@if(!empty($title))
<meta property="og:title" content="{{ $title }}" />
<meta name="twitter:title" content="{{ $title }}">
@endif
@if(!empty($description))
    <meta property="og:description" content="{{ $description }}" />
    <meta name="twitter:description" content="{{ $description }}">
@endif
<meta property="og:site_name" content="ТОВ Торговий Дім «Пірана»" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{env('APP_URL')}}/{{ Request::path() }}" />
@if(!empty($image))
    <meta property="og:image" content="{{ $image }}" />
    <meta name="twitter:image" content="{{ $image }}">
@endif