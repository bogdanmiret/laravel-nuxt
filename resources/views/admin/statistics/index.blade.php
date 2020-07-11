@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header"></div>
                <div class="box-body">
                    <table id="usersTable" class="{{ config('base.dt.table') }}" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Two Month</th>
                            <th>Two Year</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("#usersTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{!! route('admin.statistics.dt', ['type' => Request::segment(3)]) !!}'
            },
            columns: [
                {data: '{{ Request::get('data') }}', name: '{{ Request::get('name') }}'},
                {data: 'month', name: 'month'},
                {data: 'year', name: 'year'},
                {data: 'two_month', name: 'two_month'},
                {data: 'two_year', name: 'two_year'},
            ],
            order: [[1, "desc"]],
        });
    </script>
@endpush