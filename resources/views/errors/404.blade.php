@extends('layouts.app')

@section('title')
404
@endsection
@section('content')
    <div class="container">
        <div class="content">



            <div class="caution">
                <h1>404</h1>
                <h2>{{ trans('global.404') }}</h2>
            </div><!-- /.page-header -->

        </div><!-- /.content -->
    </div><!-- /.container -->

@endsection