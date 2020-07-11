@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<div class="panel panel-default">
						<div class="panel-body">

							<div class="box box-solid">

								<div class="box-header with-border">
									<a href="#" class="btn btn-xs btn-danger"
									   onclick="deleteSuggestion('{{ trans('admin/suggestion.sure') }}', '{{ route('admin.suggestion.destroy', $suggestion) }}')"><i
												class="fa fa-trash" data-toggle="tooltip" data-placement="top"
												title="{{trans('admin/suggestion.delete')}}"></i> Delete suggestion</a>
									<a class="btn btn-xs btn-warning pull-right"
									   href="{{ route('admin.toggle.suggestion.processed', ['id' => $suggestion->id, 'status' => $suggestion->processed == 1 ? 0 : 1 ]) }}">
										<i class="fa fa-cogs"></i> {{ $suggestion->processed == 1 ? "Mark as unprocessed" : "Mark as processed" }}
									</a>

									&nbsp;
									<a class="btn btn-xs  pull-right {{  $suggestion->company->active ? "btn-danger" : "btn-success" }}" href="{{ route('trans.toggle.restaurant.status', ['restaurant_id' =>  $suggestion->company->id, 'status' => $suggestion->company->active ? 0 : 1]) }}">
										<i class="fa fa-cogs"></i>  {{  $suggestion->company->active ? "Disable Restaurant" : "Enable Restaurant" }}
									</a>
								</div>
								<div class="box-body">
									<dl class="dl-horizontal">
										<dt>User</dt>
										<dd>{{ $suggestion->user->full_name}}
											@if($suggestion->user->email == "test5@vlinde.com")
												{{ strlen($suggestion->email) ? " | " .$suggestion->email : "" }}
											@else
												{{ " | " .$suggestion->user->email }}
											@endif
										</dd>
										<dt>Company</dt>
										<dd>
											<a href="{{ $suggestion->company->url }}">{{ $suggestion->company->name }}</a>
										</dd>
										<dt>Details</dt>
										<dd>
											@php
												$suggestions = json_decode($suggestion->content);
												$reverts = json_decode($suggestion->old, true);
											@endphp
											@if($suggestions)
												@foreach($suggestions as $slug => $loop_suggestion)
													<div class="{{ $suggestion->company->$slug == $loop_suggestion ? "identical" : "pending" }}">

														<b>{{ $slug }}</b>

														@if($slug == 'closed')
															<a class="pull-right"
															   href="{{ route('admin.suggestions.mark_closed', $suggestion->company->id) }}"><i
																		class="fa fa-check fa-3x"></i></a>
														@endif

														@if(in_array($slug, ['extended_address', 'phone', 'website']))

															@if($loop_suggestion != $suggestion->company->$slug)
															@if(isset($reverts[$slug]))
																<a class="pull-right"
																href="{{ route('trans.restaurant.revert.suggestion', ['restaurant_id' => $suggestion->company->id,  "field" => $slug, "suggestion_id" => $suggestion->id]) }}"><i
																			class="fa fa-undo fa-3x"></i></a>
																<br>
																Old Value :    {{ $reverts[$slug] }}
															@else
																<a class="pull-right"
																href="{{ route('trans.restaurant.approve.suggestion', ['restaurant_id' => $suggestion->company->id,  "field" => $slug, "suggestion_id" => $suggestion->id]) }}"><i
																			class="fa fa-check fa-3x"></i></a>
															@endif
															@endif

															Current -> {{ $suggestion->company->$slug}}
															<br>
																Suggestion -> {{ $loop_suggestion }}
														@endif
													</div>
												@endforeach
											@endif		

										</dd>
									</dl>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection


@push('scripts')
<script>
    function deleteSuggestion(confirmation_message, delete_address) {
        $(function () {
                bootbox.confirm({
                    title: '{{ trans('profile.confirmation') }}',
                    message: confirmation_message,

                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirm'
                        }
                    },
                    callback: function (result) {
                        if (result == true) {

                            $.ajax({
                                type: "DELETE",
                                url: delete_address,
                                dataType: "json",
                                success: function (data) {
                                    if (data.status == "ok") {

                                        window.location = data.redirect;
                                    } else {

                                        console.log('Something went wrong');
                                    }
                                }
                            });


                        }

                    }
                });
            }
        )
    }
</script>
@endpush

