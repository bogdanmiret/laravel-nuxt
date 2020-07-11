<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->mainModel = Role::class;
        $this->permissions = Permission::pluck('display_name', 'id');
        $this->middleware('permission:admin_view_roles', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_roles', ['only' => 'destroy']);
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function getRoles(Request $request)
    {
        if (!$request->ajax()) {
            abort('404');
        }
        $roles = Role::all();
        return Datatables::collection($roles)
            ->editColumn('accounts', function ($role) {
                return '<div class="count' . $role->id . '">' . $role->users()->count() . '</div>';
            })
            ->addColumn('action', function ($role) {

                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . config('base.btn.view') . '" href="' . route('admin.accounts.show-type', ['type' => $role->name]) . '">' . trans('global.btn.view') . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.roles.edit', $role->id) . '">' . trans('global.btn.edit') . '</a>';
                if (config('base.Role.delete') == true) {
                    if ($role->name != 'admin' && $role->name != 'user') {
                        $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.roles.destroy', $role->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                    }
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action', 'accounts'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.roles.edit', ['permissions' => $this->permissions]);
    }

    public function store(Request $request)
    {
        $role = new Role();
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $role->name = str_slug($request->name);
        $role->display_name = $request->display_name;
        $role->save();
        $role->perms()->sync($request->permissions);

        if ($role->save()) {
            $alert_message = trans('admin/roles.alert.u_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.roles.edit', $role->id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('admin.roles.edit', ['role' => $role, 'permissions' => $this->permissions]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $this->validate($request, [
            'display_name' => 'required',
        ]);

        // the name cannot be changed!
        $role->display_name = $request->display_name;
        $role->perms()->sync($request->permissions);

        if ($role->save()) {
            $alert_message = trans('admin/roles.alert.u_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.roles.edit', $id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }


    public function destroy($id)
    {
        if (config('base.Role.delete') == false) {
            die();
        }
        $role = Role::find($id);
        if ($role) {
            if ($role->users()->count() > 0) {
                return response()->json(['status' => 'error', 'message' => trans('admin/roles.role_has_users')]);
            }

            $role->perms()->sync([]);
            if (config('base.Role.forceDelete') == true) {
                $role->forceDelete();
            } else {
                $role->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/roles.role_not_found')]);
    }

}
