@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {!! Form::open(['url' => route('admin.emails.store' ), 'method' => 'post', 'id' => 'createEmailForm', 'files' => true]) !!}
                <div class="box-body">

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="email_sender_options_holder">
                        <div class="form-group">
                            <label>
                                {{trans('global.fld.from_email')}} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    {{--									{{Form::email('from_email', app('country_specific')->contact_email_address_sender, ['class' => 'form-control'])}}--}}
                                    {{ Form::select('from_email', isset($email_sender_options) ? $email_sender_options: [], 1,['class' => 'form-control', 'id' => 'email_sender']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2 hidden"
                         id="custom_email_sender_holder">
                        <div class="form-group">
                            <label>
                                {{trans('global.fld.custom_from_email')}} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    {{ Form::text('custom_from_email', config('env.EMAIL_FROM_ADDRESS'),['class' => 'form-control', 'id' => 'custom_email_sender']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="recipients_holder">
                        <div class="form-group">
                            <label>
                                {{trans('global.fld.to_email')}} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    <!--email_off-->
                                {{Form::select('to_email[]', [], null,['class' => 'form-control','multiple','id' => 'recipients'])}}
                                <!--/email_off-->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                {{trans('global.fld.limit')}} {{ trans('admin/emails.per_group') }} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    {{Form::number('limit', null, ['class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2 hidden" id="custom_recipients_holder">
                        <div class="form-group">
                            <label>
                                {{trans('global.fld.custom_to_email')}} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    <!--email_off-->
                                {{Form::select('custom_to_email[]', isset($reply_to) ? [$reply_to->email => $reply_to->email] : [], isset($reply_to) ? $reply_to->email : null ,['class' => 'form-control','multiple','id' => 'custom_recipients'])}}
                                <!--/email_off-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="use_template_holder">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="form-group">
                                    {{Form::checkbox('use_template', 1, null, ['id' => 'use_template'])}}
                                    {{trans('admin/emails.use_template')}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="template_email_notification" class="hidden" id="admin_templta_email_holder">
                        <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2">
                            <div class="form-group">
                                <label>
                                    {{trans('global.fld.email_template')}} <span class="symbol required"></span>
                                </label>
                                <div class="form-group">
                                    <div class="form-group">
                                        {{Form::select('admin_email_template', $admin_email_templates, null, ['class' => 'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="content_holder">
                        <div class="form-group">
                            <label>
                                {{trans('global.fld.files')}} <span class="symbol required"></span>
                            </label>
                            <div class="form-group">
                                <div class="form-group">
                                    {{ Form::file('upload_files[]',  ['class' => 'form-control', 'multiple' => 'multiple']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="simple_email_notification">
                        <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="subject_holder">
                            <div class="form-group">
                                <label>
                                    {{trans('global.fld.subject')}} <span class="symbol required"></span>
                                </label>
                                <div class="form-group">
                                    <div class="form-group">
                                        {{Form::text('subject', isset($reply_to) ? trans('global.fld.reply_subject',['subject' => $reply_to->subject]) : null, ['class' => 'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 col-md-offset-2 col-sm-5 col-sm-offset-2" id="content_holder">
                            <div class="form-group">
                                <label>
                                    {{trans('global.fld.content')}} <span class="symbol required"></span>
                                </label>
                                <div class="form-group">
                                    <div class="form-group">
                                        {{Form::textarea('email_content', isset($reply_to) ? trans('global.fld.reply_name',['name' => trim($reply_to->first_name . " " . $reply_to->last_name)]) . '<br><br><br><br>' . trans('global.regards') . ',<br>' . app('country_specific')->site_name . '<hr>' . $reply_to->content  : null, ['class' => 'form-control summernote'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    {!! Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <script>

        if ($("#use_template").is(':checked')) {
            $('#simple_email_notification').addClass('hidden');
            $('#template_email_notification').removeClass('hidden');
        }

        set_sender_email_address($("#email_sender").val());

        $("#use_template").change(function () {
            $('#simple_email_notification').toggleClass('hidden', 500);
            $('#template_email_notification').toggleClass('hidden', 500);
        });

        $("#email_sender").change(function () {
            set_sender_email_address(this.value);
        });

        $("#custom_recipients").select2({
            tags: true,
            width: '100%',
            tokenSeparators: [',', ' ']
        });

        $("#recipients").select2({
            width: '100%',
            ajax: {
                type: 'POST',
                url: "{{route('admin.emails.get-sel-recipients')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        text: params.term, // search term
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
        });

        function set_sender_email_address(type) {
            if (type == 'custom') {
                $('#custom_recipients_holder').removeClass('hidden');
                $('#recipients_holder').addClass('hidden');

                $('#template_email_notification').addClass('hidden');
                $('#simple_email_notification').removeClass('hidden');
                $('#use_template_holder').addClass('hidden');
                $("#use_template").prop("checked", false);
                $('#custom_email_sender_holder').removeClass('hidden');
            } else {

                if ($('#template_email_notification').hasClass('hidden')) {
                    $('#custom_email_sender_holder').addClass('hidden');
                    $('#recipients_holder').removeClass('hidden');
                    $('#custom_recipients_holder').addClass('hidden');

                    if ($("#use_template").is(':checked')) {
                        $('#template_email_notification').removeClass('hidden');
                        $('#simple_email_notification').addClass('hidden');
                    } else {
                        $('#simple_email_notification').removeClass('hidden');
                        $('#template_email_notification').addClass('hidden');
                    }
                }
                $('#use_template_holder').removeClass('hidden');
            }
        }
    </script>
@endpush
