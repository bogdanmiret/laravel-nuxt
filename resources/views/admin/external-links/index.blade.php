@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">
                        Statistics
                    </div>

                  </div>
                <div class="box-body">

                        <table class="table table-hover">
                            <tr>
                                <th>Host</th>
                                <th>Visits</th>
                            </tr>
                            @foreach($statistics as $statistic)
                            <tr>
                                <td>{{ $statistic->name }}</td>
                                <td>{{ $statistic->total }}</td>

                            </tr>
                            @endforeach
                        </table>


                </div>
            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">

                    </h3>
                </div>

                <div class="box-header">

                </div>
                <div class="box-body">
                    <table id="FAQsTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>{{trans('global.fld.id')}}</th>
                            <th>{{trans('global.fld.name')}}</th>
                            <th>{{trans('global.fld.url')}}</th>
                            <th>{{trans('global.fld.visits')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="showRoleModal"></div>

@endsection

@push('scripts')
<script>
    $("#FAQsTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin.get.external_links') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'url', name: 'url'},
            {data: 'visits', name: 'visits'}
        ]
//        "columnDefs": [
//            {"width": "25%", "targets": 3 }
//        ]
    });
</script>
@endpush