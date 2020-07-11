<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12 ">
                    <h2>{{ isset($country_specific) && isset($country_specific->footer_about_title) ? $country_specific->footer_about_title : '' }}</h2>
                    <p> {{ isset($country_specific) && isset($country_specific->footer_about_description) ? $country_specific->footer_about_description : '' }}</p>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <h2 style="">{{ trans('home.pages') }}</h2>
                    <div style="float:left">
                        <ul class="footer_links_list">
                            <li><a href="{{route('trans.faq.index')}}">{{trans('menu.faq')}}</a></li>
                            @if(isset($footer_pages->footer))
                                @foreach($footer_pages->footer as $page)
                                    <li><a href="{{ route('trans.page.show', $page->slug) }}"> {{ $page->name }} </a></li>
                                @endforeach
                                    <li><a href="{{ route('trans.page.about-us') }}">{{ trans('about-us.title') }}</a></li>
                                    <li><a href="{{ route('trans.page.press') }}">{{ trans('pages.press-page_footer_title') }}</a></li>
                            @endif
                            @if(config('app.debug') == true)
                                <li>
                                    <a href="#">{{ trans('admin/tpl.render_time') }} {{ (microtime(true) - LARAVEL_START) }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <h2 style="">{{ trans('home.footer-submenu2-title') }}</h2>
                    <ul class="footer_links_list">
                        @if(isset($footer_landings_1))
                            @foreach($footer_landings_1 as $calltoaction => $footer_link)
                                <li><a href="{{ $footer_link }}">{{ $calltoaction }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <h2 style="">{{ trans('home.footer-submenu1-title') }}</h2>
                    <ul class="footer_links_list">
                        @if(isset($footer_landings_2))
                            @foreach($footer_landings_2 as $calltoaction => $footer_link)
                                <li><a href="{{ $footer_link }}">{{ $calltoaction }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom" style="padding: 15px 0;">
        <div class="container">
            <div class="footer-bottom-left col-md-8">
                <span class="footer-copyright">&copy; {{ isset($country_specific) && isset($country_specific->copyright) ? $country_specific->copyright : ''  }}</span>
                <a href="{{route('trans.for.companies')}}"
                   class="footer-copyright-link">{{trans('menu.for-companies')}} </a>
                <a href="{{url('/contact')}}" class="footer-copyright-link">{{ trans('home.footer-contact') }}</a>
                @if(isset($footer_pages->copyright))
                    @foreach($footer_pages->copyright as $page)
                        <a href="{{ route('trans.page.show', $page->slug) }}"
                           class="footer-copyright-link">{{ $page->name }}</a>
                    @endforeach
                @endif
            </div>
            <div class="footer-bottom-right">

                <ul class="social-links nav nav-pills">
                    @if(isset($country_specific->facebook) && strlen($country_specific->facebook))
                        <li><a href="{{$country_specific->facebook}}"
                               target="_blank"
                               rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                    @endif
                    @if(isset($country_specific->instagram) && strlen($country_specific->instagram))
                        <li><a href="{{$country_specific->instagram}}"
                               target="_blank"
                               rel="nofollow"><i class="fa fa-instagram"></i></a></li>
                    @endif
                    @if(isset($country_specific->twitter) && strlen($country_specific->twitter))
                        <li><a href="{{$country_specific->twitter}}"
                               target="_blank"
                               rel="nofollow"><i class="fa fa-twitter"></i></a></li>
                    @endif
                </ul><!-- /.header-nav-social -->
            </div>
        </div>
    </div>
</footer>
