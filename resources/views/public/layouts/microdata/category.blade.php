<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "product",
  "logo": "{{env('APP_URL')}}/images/logo.png"
  @if(!empty($title))
  ,"name": "{{ $title }}"
  @endif
  @if(!empty($category->image))
  ,"image": "{{ $category->image->url() }}"
  @endif
  ,"offers": {
    "@type": "AggregateOffer",
    "offerCount": "{{ $total }}",
    "highPrice": "{{ $category->max_price($category->id) }}",
    "lowPrice": "{{ $category->min_price($category->id) }}",
    "priceCurrency": "UAH"
  }
}
</script>