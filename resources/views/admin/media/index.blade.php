@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-right">
                        <a href="{{ route('admin.media.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> Add media</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col">#</th>
                                <th class="col">URL</th>
                                <th class="col">Thumb URL</th>
                                <th class="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($media as $m)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ asset('storage/media/media_general/' . $m->id . '/' . $m->file_name) }}" target="_blank">
                                            <img src="{{ asset('storage/media/media_general/' . $m->id . '/' . $m->file_name) }}" alt="" width="60px">
                                        </a>
                                    </th>
                                    <td style="vertical-align: middle;">{{ 'storage/media/media_general/' . $m->id . '/' . $m->file_name }}</td>
                                    <td style="vertical-align: middle;">{{ 'storage/media/media_general/' . $m->id . '/conversions/' . $m->collection_name . '.jpg' }}</td>
                                    <td style="vertical-align: middle">
                                        <form action="{{ route('admin.media.delete', [ 'media' => $m->id ]) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-danger">DELETE</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush