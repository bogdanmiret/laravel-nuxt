<div class="modal fade viewRoleForm" id="viewRoleForm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"> {{ trans('admin/users.change_role') }} {{$user->full_name}} </h4>
            </div>
            {!! Form::model($user,['url'=>route('admin.accounts.update-role', $user->id), 'method' => 'PUT', 'id' => 'updateUserRoleForm']) !!}
            {!! Form::hidden('user_id', $user->id) !!}
            {!! Form::hidden('old_role_id', $roleUser) !!}
            <div class="modal-body">
                <div class="contact-modal-content">
                    <div class="displayError"></div>
                    <div class="form-group">
                        <label>
                            {{trans('admin/users.role')}} <span class="symbol required"></span>
                        </label>
                        <div class="form-group">
                            <select name="role" class="form-control">
                                @foreach($roles as $key => $role)
                                    <option value="{{ $key }}" @if($roleUser == $key) selected="selected" @endif >{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button(trans('global.btn.change_role'), ['class' => 'btn btn-default', 'onclick' => 'updateAccountRole(this)']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


