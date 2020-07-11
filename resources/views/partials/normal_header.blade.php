<header class="header">
	<div class="header-wrapper">
		<div class="container">
			<div class="header-inner">
				<div class="header-logo">
					<a href="/">
							<img class="logo img-responsive normal_header_logo" src="{{ asset("assets/img/logos/logo_". str_slug(extractDomain(Request::url())) .".jpg")  }}" alt="Logo" width="230" height="90">
					</a>
				</div><!-- /.header-logo -->
				<div class="header-content">
					<div class="header-top">
						@include('partials.vue_top_search')
					</div><!-- /.header-top -->
					<div class="header-bottom responsive-content">
						@include('partials.top-big-nav')
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
