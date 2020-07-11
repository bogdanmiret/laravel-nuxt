<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\RoleUserPivot;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Datatables;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->mainModel = User::class;

        $this->middleware('permission:admin_view_accounts', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_accounts', ['only' => 'destroy']);
    }

    public function index($type)
    {
    
        return view('admin.accounts.index', compact('type'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.accounts.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'full_name' => 'required',
            // 'last_name' => 'required',

//            'zip' => 'required|min:5|max:6',
//            'city' => 'required',
//            'street' => 'required',
//            'phone' => 'required',

            'email' => 'email|required|unique:users,email',
        ]);

        $user->full_name = $request->full_name;
        // $user->last_name = $request->last_name;
//        $user->zip = $request->zip;
        $user->city = $request->city;
//        $user->street = $request->street;
        $user->phone = $request->phone;
        $user->email = $request->email;

        if($user->save()){
            $alert_message = trans('admin/users.alert.u_success');
            $alert_status = 'success';
        }

        return back()->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    public function changeRole($id)
    {
        $user = User::find($id);
        $roles = Role::all()->pluck('display_name', 'id');
        $roleUser = RoleUserPivot::where('user_id', $id)->first();
        if ($roleUser) {
            $rU = $roleUser->role_id;
        } else {
            $rU = 0;
        }
        return view('admin.accounts.change-role', ['user' => $user, 'roles' => $roles, 'roleUser' => $rU]);
    }

    public function updateRole(Request $request)
    {
        $input = new \stdClass();
        foreach ($request->formData as $fD) {
            $fDName = $fD['name'];
            $input->$fDName = $fD['value'];
        }
        if ($input->user_id == Auth::user()->id) {
            return response()->json(['status' => 'error', 'message' => trans('admin/users.change_role_yourself')]);
        }
        if (empty($input->user_id)) {
            return response()->json(['status' => 'error', 'message' => trans('admin/users.require_user')]);
        }
        if (empty($input->role)) {
            return response()->json(['status' => 'error', 'message' => trans('admin/users.require_role')]);
        }
        if ($input->role == $input->old_role_id) {
            return response()->json(['status' => 'error', 'message' => trans('admin/users.another_role')]);
        }

        $deleteRoleUser = RoleUserPivot::where('user_id', $input->user_id)->delete();

        $roleUser = new RoleUserPivot();
        $roleUser->user_id = $input->user_id;
        $roleUser->role_id = $input->role;
        $roleUser->save();
        return response()->json(['status' => 'ok']);

    }

    public function getUsers(Request $request, $type = false)
    {
        if(!$request->ajax()){
            abort('404');
        }
	    
        if($type == false){
            $users = User::all();
        } else {
            $users = User::whereHas(
                'roles', function ($q) use($type) {
                $q->where('name', $type);
            }
            )->get();
        }
//        $users = User::all();


        return Datatables::of($users)
            ->editColumn('status', function ($user) {
                return '<div class="status' . $user->id . '">' . $user->getStatus() . '</div>';
            })
            ->addColumn('action', function ($user) {
                if ($user->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }

                $action =  '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href ="javascript:void(0);" data-id="' . $user->id . '" data-href="' . route('admin.accounts.change-status', $user->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.accounts.edit', $user->id) . '">' . trans('global.btn.edit') . '</a>
                        <a class="' . config('base.btn.role') . '" href="javascript:void(0);" data-href="' . route('admin.accounts.change-role', $user->id) . '" onclick="changeAccountRole(this)">' . trans('global.btn.role') . '</a>';
                if(config('base.User.delete') == true){
                    $action .=  '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.accounts.destroy', $user->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function destroy($id)
    {

        if(config('base.User.delete') == false){

            die();
        }

        if(Auth::user()->id == $id){
            return response()->json(['status' => 'error', 'message' => trans('admin/users.self_delete_error')]);
        }

        $user = User::find($id);

        if ($user) {
            if(config('base.User.forceDelete') == true){
                $user->forceDelete();
            } else {
                $user->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/users.user_not_found')]);

    }
}
