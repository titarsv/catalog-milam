<!DOCTYPE html>
<html lang="{{ App::getLocale() == 'ua' ? 'uk' : 'ru' }}" prefix="og: http://ogp.me/ns#">
@include('public.layouts.head')

<?php
$value = '';
if (!empty($settings->{'site_message_'.App::getLocale()})){
    $value = 'alert-message';
}
else if(!empty($somethingelse)){
    $value = '';
}
?>

<body class="{{ Request::path()=='/' ? ' home' : '' }} <?php echo $value; ?>" id="top">
@if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false)
@if(!empty(env('GOOGLE_ADS')))
<script>
    /* <![CDATA[ */
    var google_conversion_id = {{env('GOOGLE_ADS')}};
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script>
    if(typeof window.google_tag_params !== 'undefined' && typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'remarketingTriggered',
            'google_tag_params': window.google_tag_params
        });
    }
</script>
<script src="//www.googleadservices.com/pagead/conversion.js"></script>
@endif
<!-- Google Tag Manager (noscript) -->
{!! $settings->gtm_noscript !!}
<!-- End Google Tag Manager (noscript) -->
@endif
@include('public.layouts.header')
@yield('content')
@include('public.layouts.footer')
@include('public.layouts.footer-scripts')
</body>
</html>
