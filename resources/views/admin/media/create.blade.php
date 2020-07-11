@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-right">
                        <a href="{{ route('admin.media.index') }}" class="btn btn-primary" ><i class="fa fa-chevron-left"></i> Back</a>
                    </div>
                </div>
                <div class="box-body" id="app">
                    {{--<form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="file">File</label>--}}
                            {{--<input type="file" name="file" class="form-control">--}}
                        {{--</div>--}}
                        {{--<button type="submit" class="btn btn-primary">Add</button>--}}
                    {{--</form>--}}
                    <media-corpper
                            :upload_url="'{{ route('admin.media.store') }}'"
                            :translates="{{ json_encode([ 'reupload' => __("profile.change_profile_pic"), "cancel" => __("global.btn.cancel"), "submit" => __("global.btn.submit") ]) }}"
                    ></media-corpper>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush