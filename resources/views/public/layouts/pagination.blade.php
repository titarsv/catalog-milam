@if ($paginator->lastPage() > 1)
    {{--@if($paginator->currentPage() < $paginator->lastPage())--}}
        {{--<a href="{{ $cp->url($paginator->url($paginator->currentPage()+1), $paginator->currentPage()+1) }}" data-id="{{ $paginator->currentPage()+1 }}" class="catalog-items__more-btn" id="js_more_products">{{ __('Показать еще товары') }}</a>--}}
    {{--@else--}}
        <div class="container">
            <ul class="pagination{{ !empty($js) ? ' js_pagination' : '' }}">
                @if($paginator->currentPage() != 1)
                    <li class="prev">
                        <a href="{{ $cp->url($paginator->url(1), 1) }}"><<</a>
                    </li>
                @endif

                @if($paginator->lastPage() <= 5)

                    @for ($c=1; $c<=$paginator->lastPage(); $c++)
                        <li{!! $paginator->currentPage() == $c ? ' class="current"' : '' !!}>
                            @if($paginator->currentPage() == $c)
                              <span>{{ $c }}</span>
                            @else
                                <a href="{{ $cp->url($paginator->url($c), $c) }}">{{ $c }}</a>
                            @endif
                        </li>
                    @endfor

                @elseif($paginator->currentPage() < 4)

                    @for ($c=1; $c<=4; $c++)
                        <li{!! $paginator->currentPage() == $c ? ' class="current"' : '' !!}>
                            @if($paginator->currentPage() == $c)
                                <span>{{ $c }}</span>
                            @else
                                <a href="{{ $cp->url($paginator->url($c), $c) }}">{{ $c }}</a>
                            @endif
                        </li>
                    @endfor

                    @if($paginator->lastPage() >= 6)
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    @endif

                    <li{!! $paginator->currentPage() == $paginator->lastPage() ? ' class="current"' : '' !!}>
                        @if($paginator->currentPage() == $paginator->lastPage())
                            <span>{{ $paginator->lastPage() }}</span>
                        @else
                            <a href="{{ $cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                        @endif
                    </li>

                @elseif($paginator->currentPage() > ($paginator->lastPage()-3))

                    <li{!! $paginator->currentPage() == 1 ? ' class="current"' : '' !!}>
                        @if($paginator->currentPage() == 1)
                            <span>{{ 1 }}</span>
                        @else
                            <a href="{{ $cp->url($paginator->url(1), 1) }}">{{ 1 }}</a>
                        @endif
                    </li>

                    @if($paginator->lastPage() >= 4)
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    @endif

                    @for ($c=($paginator->lastPage()-3); $c<=$paginator->lastPage(); $c++)
                        <li{!! $paginator->currentPage() == $c ? ' class="current"' : '' !!}>
                            @if($paginator->currentPage() == $c)
                                <span>{{ $c }}</span>
                            @else
                                <a href="{{ $cp->url($paginator->url($c), $c) }}">{{ $c }}</a>
                            @endif
                        </li>
                    @endfor

                @else

                    <li{!! $paginator->currentPage() == 1 ? ' class="current"' : '' !!}>
                        @if($paginator->currentPage() == 1)
                            <span>{{ 1 }}</span>
                        @else
                            <a href="{{ $cp->url($paginator->url(1), 1) }}">{{ 1 }}</a>
                        @endif
                    </li>

                    @if($paginator->currentPage() > 3)
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    @endif

                    @for ($c=($paginator->currentPage()-1); $c<=($paginator->currentPage()+1); $c++)
                        <li{!! $paginator->currentPage() == $c ? ' class="current"' : '' !!}>
                            @if($paginator->currentPage() == $c)
                                <span>{{ $c }}</span>
                            @else
                                <a href="{{ $cp->url($paginator->url($c), $c) }}">{{ $c }}</a>
                            @endif
                        </li>
                    @endfor

                    @if($paginator->currentPage() < $paginator->lastPage()-2)
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    @endif

                    <li{!! $paginator->currentPage() == $paginator->lastPage() ? ' class="current"' : '' !!}>
                        @if($paginator->currentPage() == $paginator->lastPage())
                            <span>{{ $paginator->lastPage() }}</span>
                        @else
                            <a href="{{ $cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                        @endif
                    </li>

                @endif

                @if($paginator->currentPage() != $paginator->lastPage())
                    <li class="next">
                        <a href="{{ $cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage()) }}">>></a>
                    </li>
                @endif
            </ul>
            {{--<a href="{{ $cp->url($paginator->url(1).'&show=all', 1) }}" class="{{ !empty($js) ? 'js_show_all' : '' }}">{{ trans('app.show_all') }}</a>--}}
        </div>
    {{--@endif--}}
@endif