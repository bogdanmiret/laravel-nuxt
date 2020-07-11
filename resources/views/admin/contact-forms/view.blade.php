<div class="modal fade viewContactForm" id="viewContactForm" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
	<div class="modal-dialog modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><span style="color:red;">{{ trans('admin/cf.contact_type.' . $contact->type) }}</span> {{ $contact->full_name }} </h4>
			</div>
			<div class="modal-body">
				<div class="contact-modal-content">
					<div> {{ trans('global.fld.created_at') }}: <b>{{ $contact->created_at }}</b></div>
					<div> {{ trans('auth.full_name') }}: <b>{{ $contact->full_name }}</b></div>
					<div> {{ trans('global.fld.subject') }}: <b>{{ $contact->subject }}</b></div>
					<div> {{ trans('global.fld.from_email') }}: <b>{{ $contact->from_email }}</b></div>
					@if($contact->to_email)
						<div> {{ trans('global.fld.to_email') }}: <b>{{ $contact->to_email }}</b></div>
					@endif

					<div> {{ trans('global.fld.message') }}: <br/>
						<b>
							@if(!empty($contact->content) )
								{{ $contact->content }}
							@elseif(!empty($contact->html_content))
								{{ strip_tags($contact->html_content) }}
							@endif
						</b>
					</div>

					@if($contact->attachments)
						<a href="javascript:void(0);" onclick="download_attachments()" >Download attachments</a>
					@endif
				</div>
			</div>
			<div class="modal-footer">
				{{ Form::open(['url' => route('admin.emails.reply-to-email' ),'method' => 'post']) }}
				{{ Form::hidden('contact_form_id',$contact->id) }}
				{{--<a href="{{route('admin.emails.create')}}"--}}
				{{--class="{{ config('base.btn.send_email') }}">{{ trans('global.btn.send_email') }}</a>--}}
				{{ Form::submit(trans('global.btn.send_email'),['class'=> config('base.btn.send_email')]) }}
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

<script>
    function download_attachments() {
        iziToast.show({
            title: 'Please Wait!',
            message: 'Download will start soon',
            color: 'blue'
        });
        window.location = '{{ route("admin.emails.download.attachments", ['id' => $contact->id]) }}';
    }
</script>