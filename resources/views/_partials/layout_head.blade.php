<title>@yield('title',  isset($country_specific) && isset($country_specific->site_name) ? $country_specific->site_name : 'Speisekarte.menu')</title>
<meta name="description"
      content="@yield('meta-description',  isset($country_specific) && isset($country_specific->site_description) ? $country_specific->site_description : '')"/>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="{{ App::getLocale() }}"/>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta property="og:title"
      content="@yield('title', isset($country_specific) && isset($country_specific->site_name) ? $country_specific->site_name : 'Speisekarte.menu')"/>
<meta property="og:image"
      content="@yield('og_image', asset("assets/img/logos/logo_". str_slug(extractDomain(Request::url())) .".jpg") )"/>
<meta property="og:description"
      content="@yield('meta-description',  isset($country_specific) && isset($country_specific->site_description) ? $country_specific->site_description : '')"/>
<meta property="og:url" content="{{Request::url()}}"/>
<meta property="og:site_name" content="{{ isset($country_specific) && isset($country_specific->site_name) ? $country_specific->site_name : 'Speisekarte.menu' }}">
@if( isset($country_specific) && isset($country_specific->language))
    <meta property="og:locale"
          content="{{ $country_specific->language . "_". strtoupper($country_specific->isocode) }}">
@endif

<meta name="twitter:card" content="@yield('og_image', asset("assets/img/logos/logo_". str_slug(extractDomain(Request::url())) .".jpg") )">
<meta name="twitter:image:alt" content="@yield('title',  isset($country_specific) && isset($country_specific->site_name) ? $country_specific->site_name : 'Speisekarte.menu')">
<meta name="twitter:title" content="@yield('title',  isset($country_specific) && isset($country_specific->site_name) ? $country_specific->site_name : 'Speisekarte.menu')">
<meta name="twitter:description" content="@yield('meta-description',  isset($country_specific) && isset($country_specific->site_description) ? $country_specific->site_description : '')">

<meta name="_ewoiplnx" content="no">
{{-- <meta property=”article:author”
      content=”@yield('art_author', isset(app("country_specific")->facebook)? app("country_specific")->facebook : '')”> --}}

@stack('head')

@if(isset($og_type))
    @if($og_type == "profile")
        <meta property="profile:first_name" content="@yield('profile_first_name')">
    @endif
@else

    {{--*/ $og_type = 'website' /*--}}
@endif
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="_token" content="{{ csrf_token() }}">

@if (isset($country_specific->facebook) && strlen($country_specific->facebook))
    <meta property="fb:page_id" content="{{ $country_specific->facebook }}">
    <meta property="fb:pages" content="{{ $country_specific->facebook }}">
@endif

@yield('meta_robots_noindex')

@if(isset($all_countries) && Route::currentRouteName() !== 'trans.blog.show')
    @foreach($all_countries as $single_country)
        <link rel="alternate"
              href="{{ $single_country->domain .= Request::path() != "/" ? "/" . Request::path() : "" }}"
              hreflang="{{  $single_country->language .= $single_country->main_domain != 1 ? "-". strtoupper($single_country->isocode) : '' }}"/>
    @endforeach
@endif

<link rel="shortcut icon" href="{{ asset("assets/img/favicons/favicon.png") }}"/>
<!-- Styles -->

<link href="//fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ url(mix('css/frontend.css')) }}">

<script data-cfasync="false" type="text/javascript">
    (function(w, d) {
        var s = d.createElement('script');
        s.src = '//cdn.adpushup.com/40559/adpushup.js';
        s.type = 'text/javascript'; s.async = true;
        (d.getElementsByTagName('head')[0] || d.getElementsByTagName('body')[0]).appendChild(s);
    })(window, document);
</script>

<script>
    var BASE_URL = "{{ url('/') }}";

    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
