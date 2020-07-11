@extends('layouts.admin')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				@if (isset($page))
					{!! Form::model($page, ['url' => route('admin.pages.update', [ 'id' => $id ] ), 'method' => 'put', 'files' => true, 'id' => 'editCustomPageForm' ]) !!}
				@else
					{!! Form::open(['url' => route('admin.pages.store' ), 'method' => 'post', 'files' => true, 'id' => 'editCustomPageForm']) !!}
				@endif
				<div class="box-body">
					<div class="box-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.display_in_footer')}} <span class="symbol required"></span>
										<input type="checkbox" class="minimal"
										       @if(isset($page['display_in_footer']) && $page['display_in_footer'] == 1)
										        checked="checked"
										       @endif  value="1" name="display_in_footer">

									</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.active')}} <span class="symbol required"></span>
									</label>
									<input type="checkbox" class="js-switch"
									       @if(isset($page['status']) && $page['status'] == 1)
									        checked="checked"
									       @endif  value="1" name="status" data-switchery="true">

								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.display_title')}} <span class="symbol required"></span>
									</label>
									<input type="checkbox" class="js-switch"
									       @if(isset($page['display_title']) && $page['display_title'] == 1)
									        checked="checked"
									       @endif  value="1" name="display_title" data-switchery="true">

								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.display_in_copyright')}} <span class="symbol required"></span>
									</label>
									<input type="checkbox" class="js-switch"
									       @if(isset($page['display_in_copyright']) && $page['display_in_copyright'] == 1)
									        checked="checked"
									       @endif  value="1" name="display_in_copyright" data-switchery="true">

								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.full_width')}} <span class="symbol required"></span>
									</label>
									<input type="checkbox" class="js-switch"
									       @if(isset($page['full_width']) && $page['full_width'] == 1)
									        checked="checked"
									       @endif  value="1" name="full_width" data-switchery="true">

								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>
										{{trans('global.fld.embedded')}} <span class="symbol required"></span>
									</label>
									<input type="checkbox" class="js-switch"
									       @if(isset($page['embedded']) && $page['embedded'] == 1)
									        checked="checked"
									       @endif  value="1" name="embedded" data-switchery="true">

								</div>
							</div>
						</div>

						<div class="tabbable pills">
							<ul id="Tab" class="nav nav-pills">
								@foreach($languages as $key => $locale)
									@if($loop->count != 1)
										<li @if($key === defaultLocale()) class="active" @endif><a href="#{{$locale}}"
										                                                           data-toggle="tab">{{ucfirst($locale)}}</a>

										</li>
									@endif
								@endforeach
							</ul>
							<div class="tab-content">
								@forelse($languages as $key => $locale)
									<div id="{{$locale}}"
									     class="tab-pane fade @if($key === defaultLocale()) in active @endif">

										@if(isset($page))
											<div class="form-group {{ $errors->has('translation.' . $locale . '.slug') ? ' has-error' : '' }}">
												{!! Form::label('slug', trans('global.fld.slug')) !!}
												{!! Form::text('translation['. $locale .'][slug]', null, ['class' => 'form-control']) !!}
												{!! Form::checkError('translation.' . $locale . '.slug', $errors)  !!}
											</div>
										@endif

										<div class="form-group {{ $errors->has('translation.' . $locale . '.name') ? ' has-error' : '' }}">
											{!! Form::label('name', trans('global.fld.name')) !!}
											{!! Form::text('translation['. $locale .'][name]', null, ['class' => 'form-control']) !!}
											{!! Form::checkError('translation.' . $locale . '.name', $errors)  !!}
										</div>

										<div class="form-group {{ $errors->has('translation.' . $locale . '.content') ? ' has-error' : '' }}">
											{!! Form::label('content', trans('global.fld.content')) !!}
											{!! Form::textarea('translation[' . $locale . '][content]', null, ['id' => 'page_description', 'class' => 'form-control summernote']) !!}
											{!! Form::checkError('translation.' . $locale . '.content', $errors)  !!}
										</div>
									</div>
								@empty
									{{trans('admin.add_language_first')}}
								@endforelse
							</div>
						</div>
					</div>
					<div class="box-footer">
						{{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
@endsection