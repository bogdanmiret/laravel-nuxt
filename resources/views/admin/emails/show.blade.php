@extends('layouts.admin')

@section('content')

    <div class="space20">
        <a href="{{route('admin.emails.index')}}" class="btn btn-primary btn-o add-event"
           style>{{trans('global.btn.go-back')}}</a>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
        {!! Form::model($email, ['url' => route('admin.emails.show', [ 'id' => $email->id ] ), 'method' => 'put', 'id' => 'editArticleForm', 'files' => true ]) !!}
        <div id="myTabContent" class="tab-content">

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.user')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('sender_user', $email->send_by_user->username, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.sent')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('sent', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.delivered')}} ({{ trans('admin/emails.updated_using_cron') }}) <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('delivered', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.from_email')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('from_email', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.created_at')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('created_at', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.to_email')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('to_email', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.subject')}} <span class="symbol required"></span>
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::text('subject', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                <div class="form-group">
                    <label>
                        {{trans('global.fld.content')}} <span class="symbol required"></span>
                        @if($email->email_template_id)
                            <a href="{{ route('admin.email-templates.edit', $email->email_template_id) }}" target="_blank">{{trans('admin/emails.email_use_template')}}</a>
                        @endif
                    </label>
                    <div class="form-group">
                        <div class="form-group">
                            {{Form::textarea('content', null, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')

    <script>

        $(".custom-select2").select2({
            width: '100%',
            class: 'form-control',
            placeholder: "{{trans('admin/emails.tag-a-person')}}",
        });

    </script>
@endpush
