@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
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
                        <div class="col-md-6" id="myTabContent" class="tab-content">
                            {!! Form::open(['url' => route('admin.social.facebook.post' ), 'method' => 'post', 'id' => 'posttofacebook' ]) !!}

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.link')}}
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::text('link', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.name')}}
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::text('name', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.caption')}}
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::text('caption', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.picture')}}
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::text('picture', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.description')}} <span class="symbol required"></span>
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::textarea('description', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.message')}} <span class="symbol required"></span>
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::textarea('message', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="clearfix">
                                <div class="col-md-6 text-align-right">
                                    {{Form::submit(trans('global.btn.post-facebook'), ['class' => 'btn btn-success'])}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>

                        <div class="col-md-6" id="myTabContent" class="tab-content">
                            {!! Form::open(['url' => route('admin.social.twitter.post' ), 'method' => 'post', 'id' => 'createEmailForm' ]) !!}

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        {{trans('global.fld.message')}} <span class="symbol required"></span>
                                    </label>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{Form::textarea('status', null, ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="clearfix">
                                <div class="col-md-6 text-align-left">
                                    {{Form::submit(trans('global.btn.post-twitter'), ['class' => 'btn btn-success'])}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <br>
                    <br>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                {{trans('admin\social.key_expire',['days' => $tokendays])}}
                            </label>
                            <div class="space20">
                                <a href="{{route('admin.social.facebook.permissions')}}"
                                   class="btn btn-danger btn-o add-event"
                                   style>{{trans('admin\social.regenerate')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
