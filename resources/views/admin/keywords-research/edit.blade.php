@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if(isset($keyword))
                    {!! Form::model($keyword, ['url' => route('admin.keywords.update', [ 'id' => $keyword['id']]), 'method' => 'put']) !!}
                @else
                    {!! Form::open(['url' => route('admin.keywords.store')], ['method' => 'post']) !!}
                @endif
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('slug', trans('global.fld.slug')) !!}
                            @if(isset($keyword))
                                {!! Form::text('slug', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            @else
                                {!! Form::text('slug', null,['class' => 'form-control']) !!}
                            @endif
                            {!! Form::checkError('slug', $errors)  !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('slug', trans('global.fld.type')) !!}
                            @if(isset($keyword))
                                {!! Form::text('type', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            @else
                                {!! Form::select('type', $types, '', ['class' => 'form-control']) !!}
                            @endif
                            {!! Form::checkError('type', $errors)  !!}
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
                                            {!! Form::label('keyword', trans('global.fld.name')) !!}
                                            {!! Form::text('translation['. $locale .'][keyword]', null, ['class' => 'form-control']) !!}
                                            {!! Form::checkError('translation.' . $locale . '.keyword', $errors)  !!}
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
