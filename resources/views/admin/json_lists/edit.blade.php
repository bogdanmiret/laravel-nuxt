@extends('layouts.admin')
@section('content')
	<div class="row" id="app">
		<div class="col-md-12">
			<div class="box">

				@if($list->slug == 'envs')
						<div class="col-md-12 text-right">
							<br>
							<a href="{{ route('admin.regenerate.env') }}" class="btn btn-danger">Regenerate ENV's</a>
						</div>
					<br>
				@endif

				
				<edit-create-json-list
					:php_languages="'{{ $list->slug == 'envs' ? json_encode(['de' => 'de']) : json_encode($languages) }}'"
				    :default_locale="'{{  defaultLocale() }}'"
					:php_structure="{{ $list->structure }}"
				    :form_submit_route="'{{route('admin.json_lists.store')}}'"
				    :csrf_token="'{{ csrf_token() }}'"
				    :php_translates="{{ $translates }}"
				    :list_id="'{{ $list->id }}'"
				    :php_empty_structure="'{{ json_encode($structure) }}'"
				></edit-create-json-list>

			</div>
		</div>
	</div>
@endsection