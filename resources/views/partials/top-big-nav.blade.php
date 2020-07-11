{{--<div class="header-action">--}}
{{--<a href="{{route('trans.get_new_restaurant_page')}}" class="header-action-inner" title="Add Listing" data-toggle="tooltip" data-placement="bottom">--}}
{{--<i class="fa fa-plus"></i>--}}
{{--</a><!-- /.header-action-inner -->--}}
{{--</div><!-- /.header-action -->--}}
<ul class="header-nav-primary nav nav-pills collapse navbar-collapse custom-flex">
	<li class="{{Route::currentRouteName() == 'trans.home' ? 'active' : ''}}">
		<a href="{{route('trans.home')}}">{{trans('mainmenu.home')}}</a>
	</li>

	<li class="{{Route::currentRouteName() == 'trans.restaurants.index' ? 'active' : ''}}">
		<a href="{{route('trans.restaurants.index')}}">{{trans('mainmenu.restaurants')}}</a>
	</li>

	<li class="{{Route::currentRouteName() == 'trans.dishes.index' ? 'active' : ''}}">
		<a href="{{route('trans.dishes.index')}}">{{trans('mainmenu.dishes')}} </a>
	</li>

	@if((isset($country_specific) && property_exists($country_specific, 'blog') && $country_specific->blog == 1)
	 	|| (isset($country_specific) && property_exists($country_specific, 'feed') && $country_specific->feed == 1))
		<li>
			<a href="{{route('trans.blog.index')}}" id="news-submenu">{{trans('menu.news')}}<i class="fa fa-chevron-down"></i></a>
			<ul class="sub-menu">
				@if(isset($country_specific) && property_exists($country_specific, 'blog') && $country_specific->blog == 1)
					<li class="{{Route::currentRouteName() == 'trans.blog.index' ? 'active' : ''}}">
						<a href="{{route('trans.blog.index')}}">{{ trans('mainmenu.blog') }} </a>

					</li>
				@endif

				@if(isset($country_specific) && property_exists($country_specific, 'feed') && $country_specific->feed == 1)
					<li class="{{Route::currentRouteName() == 'trans.feed.index' ? 'active' : ''}}">
						<a href="{{route('trans.feed.index')}}">{{ trans('mainmenu.feed') }} </a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if (Auth::guest())
		<li><a href="{{ url('/login') }}">{{ trans('menu.login') }}</a></li>
		{{--            <li><a href="{{ url('/register') }}">{{ trans('menu.register') }}</a></li>--}}

	@else

		<li>
			<a href="javascript:void(0)">{{ str_limit(Auth::user()->full_name, '20', '...') }} <i class="fa fa-chevron-down"></i></a>

			<ul class="sub-menu">
				@if(\Zizaco\Entrust\EntrustFacade::can("view_admin_area"))
					<li><a href="{{ route('admin.index') }}">{{ trans('global.admin_area') }}</a></li>
					<li><a href="{{ url('/nova') }}">Nova Admin</a></li>
				@endif

				@if(Auth::user()->companies)
					@foreach(Auth::user()->companies as $restaurant)
						<li>
							<a class="ellipsis-text" href="{{route('trans.edit.restaurant', ['restaurant_id' => $restaurant->id])}}"> {{$restaurant->name}}</a>
						</li>
					@endforeach
				@endif
				<li><a href="{{ route('trans.profile.index') }}">{{trans('mainmenu.profile')}}</a></li>
				<li><a href="{{ route('trans.profile.favorites') }}">{{trans('profile.favorites')}}</a></li>
				<li><a href="{{ url('/logout') }}"
				       onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
						Logout </a>

					<form id="logout-form" action="{{ url('/logout') }}" method="POST"
					      style="display: none;">
						{{ csrf_field() }}
					</form>
				</li>

			</ul>
		</li>

	@endif

	<li>
		<a href="{{route('trans.for.companies')}}" id="for-companies-submenu">{{trans('menu.restaurant_singup')}}<i class="fa fa-chevron-down"></i></a>
		<ul class="sub-menu">
			<li class="{{Route::currentRouteName() == 'trans.for.companies' ? 'active' : ''}}">
				<a href="{{route('trans.for.companies')}}">{{trans('menu.for-companies')}} </a>
			</li>
			<li class="{{Route::currentRouteName() == 'trans.page.about-us' ? 'active' : ''}}">
				<a href="{{route('trans.page.about-us')}}">{{trans('menu.about', ['site_name' => config('app.name')])}} </a>
			</li>
			<li class="{{Route::currentRouteName() == 'trans.pricing' ? 'active' : ''}}">
				<a href="{{route('trans.pricing')}}">{{trans('menu.pricing')}} </a>
			</li>
			<li class="{{Route::currentRouteName() == 'trans.contact.index' ? 'active' : ''}}">
				<a href="{{route('trans.contact.index')}}">{{trans('menu.contact')}} </a>
			</li>
		</ul>
	</li>


	<li class="has-mega-menu ">
		<a href="javascript:void(0)" class="align_menu_flag">
			<i class="flag {{isset($country_specific) ? $country_specific->isocode : ''}}" style="margin-top: -2px"></i>
			&nbsp;
			{{-- {{ LaravelLocalization::getSupportedLocales()[LaravelLocalization::getCurrentLocale()]['native'] }}  --}}
			<i class="fa fa-chevron-down"></i>
		</a>

		<ul class="mega-menu">
			@if(isset($continents))
				@foreach($continents as $continent_name => $continent)
					<li>
						<a href="javascript:void(0)">{{ $continent_name }}</a>
						<ul>
							@foreach($continent as $country)
								<li href="javascript:void(0)"><a href="{{ $country->domain   }}"><i class="flag {{$country->isocode}}"></i> {{ $country->country }} </a></li>
							@endforeach
						</ul>
					</li>
				@endforeach
			@endif


			<li>
				<a href="javascript:void(0)">{{ trans('global.language') }}</a>
				<ul>
					@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
						<li>
							@php
							$dom = config("base.domains.{$localeCode}");
							@endphp

							<a href="{{ $dom .= Request::path() != "/" ? "/" . Request::path() : "" }}"><i class="flag {{$localeCode == 'en' ? 'gb' : $localeCode}} mr-2"></i> &nbsp;{{ $properties['native'] }} </a>
						</li>
					@endforeach
				</ul>


			</li>
		</ul>
	</li>


</ul>

<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".header-nav-primary">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</button>


@push('scripts')
	<script>
		if(window.outerWidth < 766) {
			$('#for-companies-submenu').attr('href', '#');
			$('#news-submenu').attr('href', '#');
		}
	</script>
@endpush
