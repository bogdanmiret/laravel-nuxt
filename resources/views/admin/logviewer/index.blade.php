@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
                    <iframe src="{{route('log-viewer::dashboard')}}" style="width:100%;height:1000px"></iframe>
        </div>
    </div>
    <div class="showRoleModal"></div>
@endsection