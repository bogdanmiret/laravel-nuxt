@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<div class="panel panel-default">
						<div class="panel-body">

							@if(isset($feedback))

								<b> {{ trans('global.fld.passed_profanity') }}
									: {!!  $feedback->passed_profanity ? "<span class='label label-success'> Yes </span>" : "<span class='label label-danger'> No </span>" !!}  </b>
								<br>
								<b> {{ trans('global.fld.status') }}
									: {!!  $feedback->status ? "<span class='label label-success'> Enabled </span>" : "<span class='label label-danger'> Disabled </span>" !!}  </b>


								<span class="pull-right">
								<b> {{ trans('admin/claim.table.actions') }} :</b>


									@if($feedback->status == 0)
										<a href="{{route('admin.feedback.status.mark', [$feedback, 1, ])}}"
										   class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip"
										                                     data-placement="top"
										                                     title=" {{trans('admin/feedback.approve')}}"></i></a>
									@else
										<a href="{{route('admin.feedback.status.mark', [$feedback,0, ])}}"
										   class="btn btn-xs btn-warning"><i class="fa fa-cog" data-toggle="tooltip"
										                                     data-placement="top"
										                                     title=" {{trans('admin/feedback.disapprove')}}"></i></a>
									@endif


									<a href="#" class="btn btn-xs btn-danger"
									   onclick="deleteFeedback('{{ trans('admin/feedback.sure') }}', '{{ route('admin.feedback.destroy', $feedback) }}')"><i
												class="fa fa-trash" data-toggle="tooltip" data-placement="top"
												title="{{trans('admin/feedback.delete')}}"></i></a>
							</span>



								<hr>
								<div class="box box-solid">
									<div class="box-header with-border">
										<h3 class="box-title" style="display:flex;">

												{{ $feedback->title }}



										</h3>
										<span class="pull-right">
													@if($feedback->model_type == "App\Models\Company")
												<a href="{{ $feedback->company_model->url }}" target="_blank">URL</a>
											@endif
											</span>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<dl class="dl-horizontal">
											<dt>User</dt>
											<dd>{{ $feedback->user->full_name}}</dd>
											<br>
											<dt>Comment</dt>
											<dd>{{ $feedback->comment }}</dd>
											<br>

											<dt>Rating</dt>
											<dd>{{ $feedback->rating }}</dd>
											<br>
											<dt>Detailed Rating</dt>
											<dd>
												@if(isset($feedback->custom_properties))
													@php
														$ratings = json_decode($feedback->custom_properties);
													@endphp

													@foreach($ratings as $rating_name => $number)
														<b>{{ $rating_name }}</b> -> {{ $number }} &nbsp;&nbsp;&nbsp;&nbsp;
													@endforeach
												@endif
											</dd>


										</dl>
									</div>
									<!-- /.box-body -->
								</div>

							@endif

						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
@endsection


@push('scripts')
<script>
    function deleteFeedback(confirmation_message, delete_address) {
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

