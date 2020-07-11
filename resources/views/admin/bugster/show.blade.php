@push('scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@endpush

@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<h4>{{ $bugster->status_code }}	 {{ $bugster->method }}  / {{ $bugster->full_url }}  <span class="pull-right">ENV : {{ $bugster->app_env }} | Reported : {{ $bugster->reported }}</span> </h4>
				</div>
				<div class="box-body">
					<div class="col-md-12">
					  	<span class="pull-right"></span>
					</div>
					<hr>
					<div class="col-md-12">

						<div class="tabbable pills">
							<ul id="faqs" class="nav nav-pills " style="padding-left:40%">

										<li class="active" ><a href="#general" data-toggle="tab">General</a></li>
										<li  ><a href="#trace" data-toggle="tab">Trace</a></li>
										<li  ><a href="#headers" data-toggle="tab">Headers</a></li>
							</ul>
							<br>
							<div class="tab-content">
									<div class="tab-pane fade general in active" id="general">
										<hr>
										<div class="row">
											<div class="col-md-3 col-sm-6 col-xs-12">
												<div class="info-box">
													<span class="info-box-icon bg-aqua"><i class="fa fa-user-circle"></i></span>

													<div class="info-box-content">
														<span class="info-box-text">Users Affected</span>
														<span class="info-box-number">{{ $users_affected }}</span>
													</div>
													<!-- /.info-box-content -->
												</div>
												<!-- /.info-box -->
											</div>
											<!-- /.col -->
											<div class="col-md-3 col-sm-6 col-xs-12">
												<div class="info-box">
													<span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>

													<div class="info-box-content">
														<span class="info-box-text">Occurrences</span>
														<span class="info-box-number">{{ $count }}</span>
													</div>
													<!-- /.info-box-content -->
												</div>
												<!-- /.info-box -->
											</div>
											<!-- /.col -->
											<div class="col-md-3 col-sm-6 col-xs-12">
												<div class="info-box">
													<span class="info-box-icon bg-yellow"><i class="fa fa-calendar"></i></span>

													<div class="info-box-content">
														<span class="info-box-text">First occurrence</span>
														<span class="info-box-number">{{ $first_occurrence }}</span>
													</div>
													<!-- /.info-box-content -->
												</div>
												<!-- /.info-box -->
											</div>
											<!-- /.col -->
											<div class="col-md-3 col-sm-6 col-xs-12">
												<div class="info-box">
													<span class="info-box-icon bg-red"><i class="fa fa-calendar-plus-o"></i></span>

													<div class="info-box-content">
														<span class="info-box-text">Most recent occurrence</span>
														<span class="info-box-number">{{ $last_occurrence }}</span>
													</div>
													<!-- /.info-box-content -->
												</div>
												<!-- /.info-box -->
											</div>
											<!-- /.col -->
										</div>
										<!-- /.row -->
										<br>
										<br>

										<div class="row">
											<div class="col-md-6">
												<b>IP</b> : {{ $bugster->ip_address }}
											</div>
											@if($bugster->user_id != 0 )
												<div class="row">
													<div class="col-md-6">
														<b>User ID</b> : {{ $bugster->user_id }}
													</div>
												</div>

											@endif

										</div>

										<hr>

										<div class="row">
											<div class="col-md-6">
												<b>Message</b> : {!! $bugster->message !!}

											</div>
											<div class="col-md-6">
												<b>Report File </b> :  {{ $bugster->file }}
											</div>
										</div>

										<hr>


										<br>
										<br>
										<div class="row">
											<div class="col-md-12">
												<div id="bug_chart" style="height: 300px;"></div>
											</div>
											<br>
											<br>
											<br>
										</div>

									</div>

									<div class="tab-pane fade general " id="trace">

										{!! soft_dd(json_decode($bugster->trace)) !!}

									</div>

								<div class="tab-pane fade general " id="headers">
							
								@foreach(json_decode($bugster->headers, true ) as $key => $headers)
									{{ $key }}
										@foreach($headers as $header)
											<pre>{{ $header }}</pre>
										@endforeach
									{{--<hr>--}}
									@endforeach
							
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
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<script>
    new Morris.Line({
        element: 'bug_chart',
        data: {!! $graph !!}  ,
        xkey: 'date',
        ykeys: ['times'],
        labels: ['times']
    });
</script>
@endpush