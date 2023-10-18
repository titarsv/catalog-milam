<script type='application/ld+json'>
{
  "@context": "http://www.schema.org",
  "@type": "product",
  @if(!empty($product->brand))
  "brand": {
    "@type": "Brand",
    "name": "{{ $product->brand->name }}"
  },
  @endif
  "logo": "{{env('APP_URL')}}/images/logo-milam.png",
  "name": "{{ $product->name }}",
  "sku": "{{ $product->sku }}",
  @if(!empty($product->gtin))
  "gtin": "{{ $product->gtin }}",
  @endif
  @if(!empty($category = $product->main_category()))
  "category": "{{ $category->name }}",
  @endif
  @if(!empty($product->image))
  "image": "{{ $product->image->url() }}",
  @endif
  "description": "{{ empty($product->description) ? $product->name : strip_tags($product->description) }}",
  "offers": {
    "@type": "Offer",
    {{--"priceCurrency": "UAH",--}}
    {{--"price": "{{ $product->price }}",--}}
    {{--"priceValidUntil": "{{ date('Y-m-d', time() + 86400 * 30) }}",--}}
    {{--"itemCondition": "http://schema.org/UsedCondition",--}}
    "availability": "http://schema.org/InStock",
    "url": "{{ env('APP_URL')}}/product/{{ $product->url_alias }}",
    "seller": {
      "@type": "Organization",
      "name": "ТОВ Торговий Дім «Пірана»"
    }
  }
  {{--@if(isset($reviews))--}}
    {{--@php--}}
        {{--$bestRating = 0;--}}
        {{--$sumRating = 0;--}}
        {{--$reviewCount = 0;--}}
        {{--foreach($reviews as $review){--}}
            {{--if($review->grade > $bestRating){--}}
                {{--$bestRating = $review->grade;--}}
            {{--}--}}
            {{--$sumRating += $review->grade;--}}
            {{--$reviewCount++;--}}
        {{--}--}}
    {{--@endphp--}}
  {{--@if($reviewCount > 0)--}}
  {{--,--}}
  {{--"aggregateRating": {--}}
    {{--"@type": "aggregateRating",--}}
    {{--"worstRating": "1",--}}
    {{--"ratingValue": "{{ round($sumRating/$reviewCount, 2) }}",--}}
    {{--"bestRating": "{{ $bestRating }}",--}}
    {{--"reviewCount": "{{ $reviewCount }}"--}}
  {{--}--}}
  {{--@else--}}
  {{--,--}}
  {{--"aggregateRating": {--}}
    {{--"@type": "aggregateRating",--}}
    {{--"worstRating": "1",--}}
    {{--"ratingValue": "4.9",--}}
    {{--"bestRating": "5",--}}
    {{--"reviewCount": "48"--}}
  {{--}--}}
  {{--@endif--}}
{{--@endif--}}
}
</script>