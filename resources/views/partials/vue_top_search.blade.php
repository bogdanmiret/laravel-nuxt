<div class="relative v-cloak-block" style="height: 40px">

</div>

@php

	if (isset($search_keyword) && $search_keyword != '') {
		$keyword = $search_keyword;
	} elseif (isset($search_terms) && count($search_terms) > 0) {
		$keyword = $search_terms[0];
	} else {
	    $keyword = '';
	}

@endphp

<div id="vue_wrapper2" class="pull-right vue_wrapper" style="width: 536px; padding-bottom: 12px">

	<top-search :button_trans="'{{ trans('home.search-button') }}'"
			:not_location_selected="'{{ trans('home.not_location_selected_top') }}'"
			:keyword_placeholder_trans="'{{ trans('home.search-placeholder') }}'"
			:city_placeholder_trans="'{{ trans('home.city-placeholder') }}'"
			:suggested_searches_php="' {{ isset($suggested_searches) ? str_replace("'", " ", $suggested_searches) : json_encode([]) }}'"
			:suggested_searches_trans="'{{ trans('restaurant.suggested_searches') }}'"
			:auto_complete_route="'{{ route('trans.companies.auto.complete') }}'"
			:no_suggestions_trans="'{{ trans('restaurant.no_suggestion') }}'"
			:php_search_keyword="'{{ $keyword }}'"
			:php_search_city="'{{ isset($recreated_object) ? json_encode(str_replace("'", " ",$recreated_object)) : ''  }}'"
			:build_search_route="'{{ route('trans.build_restaurants_search') }}'"
			:city_session="'{{isset($citySession) ? json_encode(str_replace("'", " ",$citySession)) : ''}}'"
			:here_maps_id="'{{env('HERE_MAPS_ID')}}'"
			:here_maps_code="'{{env('HERE_MAPS_CODE')}}'"
			:here_maps_key="'{{env('HERE_MAPS_NEW_API_KEY')}}'"
	></top-search>
</div>
