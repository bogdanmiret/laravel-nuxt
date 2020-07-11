@if (count($errors) > 0)

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

@endif

@if (session('status'))
        <div class="row">
            <div class="col-md-12">
                <div class="text-center alert @if(session('status') == 'success') alert-success @elseif(session('status') == 'warning') alert-warning @endif  alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                           <h4>{!! session('message')  !!}</h4>
                </div>
            </div>
        </div>
@endif