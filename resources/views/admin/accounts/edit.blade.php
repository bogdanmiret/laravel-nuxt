@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if(isset($user))
                    {!! Form::model($user,['url' => route('admin.accounts.update', $user->id), 'method' => 'PUT'])  !!}
                @endif
                <div class="box-body">
                    <div class="form-group {{ $errors->has('full_name') ? ' has-error' : '' }}">
                        {!! Form::label('full_name', trans('auth.full_name')) !!}
                        {!! Form::text('full_name', null, ['class' => 'form-control', 'required' => 'required'])!!}
                        {!! Form::checkError('full_name', $errors)  !!}
                    </div>
                    {{-- <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                        {!! Form::label('last_name', trans('global.fld.last_name')) !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required'])!!}
                        {!! Form::checkError('last_nane', $errors)  !!}
                    </div> --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                {!! Form::label('zip', trans('global.fld.zip')) !!}
                                {!! Form::text('zip', null, ['class' => 'form-control'])!!}
                                {!! Form::checkError('zip', $errors)  !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                {!! Form::label('city', trans('global.fld.city')) !!}
                                {!! Form::text('city', null, ['class' => 'form-control'])!!}
                                {!! Form::checkError('city', $errors)  !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                {!! Form::label('street', trans('global.fld.street')) !!}
                                {!! Form::text('street', null, ['class' => 'form-control'])!!}
                                {!! Form::checkError('street', $errors)  !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', trans('global.fld.email')) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required'])!!}
                        {!! Form::checkError('email', $errors)  !!}
                    </div>
                    <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                        {!! Form::label('phone', trans('global.fld.phone') ) !!}
                        {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                        {!! Form::checkError('phone', $errors)  !!}

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