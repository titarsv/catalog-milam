<head>
    <meta charset="utf-8">

    <?php if(!empty($seo)): ?>
        <title><?php echo e($seo->meta_title); ?></title>
        <meta name="description" content="<?php echo e($seo->meta_description); ?>">
        <meta name="keywords" content="<?php echo e($seo->meta_keywords); ?>">

        <?php if(!empty($seo->canonical)): ?>
            <link rel="canonical" href="<?php echo e($seo->canonical); ?>">
        <?php elseif(empty($pagination) || $pagination->currentPage() == 1): ?>
            <link rel="canonical" href="<?php echo e(request()->url()); ?>">
        <?php endif; ?>
        <?php if(!empty($seo->robots)): ?>
            <meta name="robots" content="<?php echo e($seo->robots); ?>">
        <?php else: ?>
            <meta name="robots" content="index, follow">
        <?php endif; ?>

        <?php if(!empty($pagination) && $pagination->currentPage() > 1): ?>
            <link rel="prev" href="<?php echo e($cp->url($pagination->url($pagination->currentPage() - 1), $pagination->currentPage() - 1)); ?>">
        <?php endif; ?>
        <?php if(!empty($pagination) && $pagination->currentPage() < $pagination->lastPage()): ?>
            <link rel="next" href="<?php echo e($cp->url($pagination->url($pagination->currentPage() + 1), $pagination->currentPage() + 1)); ?>">
        <?php endif; ?>
    <?php else: ?>
        <?php echo $__env->yieldContent('meta'); ?>
    <?php endif; ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#59D230">

    <!-- Template Basic Images Start -->
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    
    
    
    <!-- Template Basic Images End -->

    <link rel="preload" as="font" href="/fonts/OpenSans-Bold.woff?030f6a69f8156aef8382a1e74351ec7d" type="font/woff" crossorigin>
    <link rel="preload" as="font" href="/fonts/OpenSans-Regular.woff?74fbb1368a3029d9e39a560a3d387e48" type="font/woff" crossorigin>
    <link rel="preload" as="font" href="/fonts/OpenSans-SemiBold.woff?1232ba75e1255695995870f71fcaa958" type="font/woff" crossorigin>
    <link rel="preload" as="font" href="/fonts/OpenSans-Light.woff?118737856e2ad919605cbcb601de257e" type="font/woff" crossorigin>
    <link rel="preload" as="font" href="/fonts/OpenSans-ExtraBold.woff?6b2f5b02a655bf0a1aeae486690fa695" type="font/woff" crossorigin>

    <?php if(App::getLocale() == 'ua'): ?>
        <link rel="alternate" hreflang="ru-UA" href="<?php echo e(env('APP_URL')); ?><?php echo e(Request::path() == 'ua' ? '' : '/'.substr(Request::path(), 3)); ?>" />
        <link rel="alternate" hreflang="uk-UA" href="<?php echo e(env('APP_URL')); ?>/<?php echo e(Request::path() == '/' ? '' : Request::path()); ?>" />
    <?php elseif(App::getLocale() == 'ru'): ?>
        <link rel="alternate" hreflang="uk-UA" href="<?php echo e(env('APP_URL')); ?>/ua<?php echo e(Request::path() == '/' ? '' : '/'.Request::path()); ?>" />
        <link rel="alternate" hreflang="ru-UA" href="<?php echo e(env('APP_URL')); ?><?php echo e(Request::path() == '/' ? '' : '/'.Request::path()); ?>" />
    <?php endif; ?>

    <!-- Load CSS, CSS Localstorage & WebFonts Main Function -->
    <script>!function(e){"use strict";function t(e,t,n){e.addEventListener?e.addEventListener(t,n,!1):e.attachEvent&&e.attachEvent("on"+t,n)};function n(t,n){return e.localStorage&&localStorage[t+"_content"]&&localStorage[t+"_file"]===n};function a(t,a){if(e.localStorage&&e.XMLHttpRequest)n(t,a)?o(localStorage[t+"_content"]):l(t,a);else{var s=r.createElement("link");s.href=a,s.id=t,s.rel="stylesheet",s.type="text/css",r.getElementsByTagName("head")[0].appendChild(s),r.cookie=t}}function l(e,t){var n=new XMLHttpRequest;n.open("GET",t,!0),n.onreadystatechange=function(){4===n.readyState&&200===n.status&&(o(n.responseText),localStorage[e+"_content"]=n.responseText,localStorage[e+"_file"]=t)},n.send()}function o(e){var t=r.createElement("style");t.setAttribute("type","text/css"),r.getElementsByTagName("head")[0].appendChild(t),t.styleSheet?t.styleSheet.cssText=e:t.innerHTML=e}var r=e.document;e.loadCSS=function(e,t,n){var a,l=r.createElement("link");if(t)a=t;else{var o;o=r.querySelectorAll?r.querySelectorAll("style,link[rel=stylesheet],script"):(r.body||r.getElementsByTagName("head")[0]).childNodes,a=o[o.length-1]}var s=r.styleSheets;l.rel="stylesheet",l.href=e,l.media="only x",a.parentNode.insertBefore(l,t?a:a.nextSibling);var c=function(e){for(var t=l.href,n=s.length;n--;)if(s[n].href===t)return e();setTimeout(function(){c(e)})};return l.onloadcssdefined=c,c(function(){l.media=n||"all"}),l},e.loadLocalStorageCSS=function(l,o){n(l,o)||r.cookie.indexOf(l)>-1?a(l,o):t(e,"load",function(){a(l,o)})}}(this);</script>

    <style>
        <?php echo file_get_contents(public_path('css/header.css')); ?>

    </style>

    <?php if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false && config('app.debug') === false): ?>
    <!-- Load Custom CSS Compiled without JS Start -->
    <noscript>
        <link rel="stylesheet" href="<?php echo e(mix("css/app.css")); ?>">
    </noscript>
    <script>loadCSS( "<?php echo e(mix("css/app.css")); ?>", false, "all" );</script>
    <!-- Load Custom CSS Compiled without JS End -->
    <?php else: ?>
    <style>
        <?php echo file_get_contents(public_path('css/app.css')); ?>

    </style>
    <?php endif; ?>

    <script>
        performance.mark("stylesheets done blocking");
    </script>
    <?php echo $__env->yieldContent('page_vars'); ?>
    <?php if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false && config('app.debug') === false): ?>
    <!-- Google Tag Manager -->
    <?php echo $settings->gtm; ?>

    <!-- End Google Tag Manager -->
    <?php endif; ?>
    <?php echo $__env->make('public.layouts.microdata.local_business', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/head.blade.php ENDPATH**/ ?>