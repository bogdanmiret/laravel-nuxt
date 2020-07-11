@if ($breadcrumbs)
    <div class="breadcrumbs clearfix">
        <div itemscope itemtype="http://schema.org/BreadcrumbList">
            @foreach ($breadcrumbs as $key => $breadcrumb)
                @if($breadcrumb->first)
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <meta itemprop="position" content="{{$key+1}}" />
                        <a href="{{ $breadcrumb->url }}" id="{{ $breadcrumb->url }}" itemscope itemtype="http://schema.org/Thing" itemprop="item">
                            <span itemprop="name">{{ ucfirst($breadcrumb->title) }}</span>
                        </a>
                    </span>
                    @if(count($breadcrumbs) > 1) / @endif

                @elseif ($breadcrumb->last)
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <meta itemprop="position" content="{{$key+1}}" />
                        <a href="{{ $breadcrumb->url }}" id="{{ $breadcrumb->url }}" itemscope itemtype="http://schema.org/Thing" itemprop="item">
                            <span itemprop="name">{{ ucfirst($breadcrumb->title) }}</span>
                        </a>
                    </span>
                @else
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <meta itemprop="position" content="{{$key+1}}" />
                        <a href="{{ $breadcrumb->url }}" id="{{ $breadcrumb->url }}" itemscope itemtype="http://schema.org/Thing" itemprop="item">
                            <span itemprop="name">{{ ucfirst($breadcrumb->title) }}</span>
                        </a>
                    </span> /
                @endif
            @endforeach
        </div>
    </div>
@endif
