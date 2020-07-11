@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if(isset($email_template))
                    {!! Form::model($email_template, ['url' => route('admin.email-templates.update', [ 'id' => $email_template['id']]), 'method' => 'put']) !!}
                @else
                    {!! Form::open(['url' => route('admin.email-templates.store')], ['method' => 'post']) !!}
                @endif
                <div class="box-body">
                    <div class="box-body">
                        @if(!isset($email_template) || isset($email_template) &&  $email_template->admin_notification == 1)
                            <h3 class="text-center">{!! trans('admin/email_templates.warning.only_for_admin.description', ['link' => "<a href='" . route('admin.emails.create') . "' target='_blank'>" . trans('admin/email_templates.warning.only_for_admin.link') . "</a>"]) !!}</h3>
                        @endif
                        <div class="form-group">
                            {!! Form::label('slug', trans('global.fld.slug')) !!}
                            @if(isset($email_template))
                                {!! Form::text('slug', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            @else
                                {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                            @endif
                            {!! Form::checkError('slug', $errors)  !!}
                        </div>

                            <div class="form-group">
                                {!! Form::label('category', trans('global.fld.category')) !!}
                                {!! Form::select('category', $categories, isset($email_template) ? $email_template->type_id : '', ['class' => 'form-control']) !!}
                                {!! Form::checkError('category', $errors)  !!}
                            </div>
                        <div class="tabbable pills">
                            <ul id="Tab" class="nav nav-pills">
                                @foreach($languages as $key => $locale)
                                    @if($loop->count != 1)
                                        <li @if($key == defaultLocale()) class="active" @endif><a href="#{{$locale}}" data-toggle="tab">{{ucfirst($locale)}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @forelse($languages as $key => $locale)
                                    <div id="{{$locale}}" class="tab-pane fade @if($key == defaultLocale()) in active @endif">

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.name') ? ' has-error' : '' }}">
                                            {!! Form::label('name', trans('global.fld.name')) !!}
                                            {!! Form::text('translation['. $locale .'][name]', null, ['class' => 'form-control']) !!}
                                            {!! Form::checkError('translation.' . $locale . '.name', $errors)  !!}
                                        </div>

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.subject') ? ' has-error' : '' }}">
                                            {!! Form::label('subject', trans('global.fld.subject')) !!}
                                            {!! Form::text('translation['. $locale .'][subject]', null, ['class' => 'form-control']) !!}
                                            <div class="field-info">{{ isset($email_template->subject_info) ? $email_template->subject_info : trans('admin/email_templates.available_vars', ['vars' => $available_vars->subject]) }}</div>
                                            {!! Form::checkError('translation.' . $locale . '.subject', $errors)  !!}
                                        </div>
                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.greeting') ? ' has-error' : '' }}">
                                            {!! Form::label('greeting', trans('global.fld.greeting')) !!}
                                            {!! Form::text('translation['. $locale .'][greeting]', null, ['class' => 'form-control']) !!}
                                            <div class="field-info">{{ isset($email_template->greeting_info) ? $email_template->greeting_info : trans('admin/email_templates.available_vars', ['vars' => $available_vars->greeting]) }}</div>
                                            {!! Form::checkError('translation.' . $locale . '.greeting', $errors)  !!}
                                        </div>

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.intro_line') ? ' has-error' : '' }}">
                                            {!! Form::label('intro_line', trans('global.fld.intro_line')) !!}
                                            {!! Form::textarea('translation[' . $locale . '][intro_line]', null, ['id' => 'intro_line', 'class' => 'form-control summernote']) !!}
                                            <div class="field-info">{{ isset($email_template->intro_line_info) ? $email_template->intro_line_info : trans('admin/email_templates.available_vars', ['vars' => $available_vars->intro_line]) }}</div>
                                            {!! Form::checkError('translation.' . $locale . '.intro_line', $errors)  !!}
                                        </div>

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.outro_line') ? ' has-error' : '' }}">
                                            {!! Form::label('outro_line', trans('global.fld.outro_line')) !!}
                                            {!! Form::textarea('translation[' . $locale . '][outro_line]', null, ['id' => 'outro_line', 'class' => 'form-control summernote']) !!}
                                            <div class="field-info">{{ isset($email_template->outro_line_info) ? $email_template->outro_line_info : trans('admin/email_templates.available_vars', ['vars' => $available_vars->outro_line]) }}</div>
                                            {!! Form::checkError('translation.' . $locale . '.outro_line', $errors)  !!}
                                        </div>

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.action_text') ? ' has-error' : '' }}">
                                            {!! Form::label('action_text', trans('global.fld.action_text')) !!}
                                            {!! Form::text('translation[' . $locale . '][action_text]', null, ['id' => 'description2', 'class' => 'form-control']) !!}
                                            {!! Form::checkError('translation.' . $locale . '.action_text', $errors)  !!}
                                        </div>

                                        <div class="form-group {{ $errors->has('translation.' . $locale . '.action_url') ? ' has-error' : '' }}">
                                            {!! Form::label('action_url', trans('global.fld.action_url')) !!}
                                            {!! Form::text('translation[' . $locale . '][action_url]', null, ['id' => 'action_url', 'class' => 'form-control']) !!}
                                            <div class="field-info">{{ isset($email_template->action_url_info) ? $email_template->action_url_info : trans('admin/email_templates.available_vars', ['vars' => $available_vars->action_url]) }}</div>
                                            {!! Form::checkError('translation.' . $locale . '.action_url', $errors)  !!}
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
