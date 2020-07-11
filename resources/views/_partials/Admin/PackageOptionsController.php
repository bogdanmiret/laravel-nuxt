<?php

namespace App\Http\Controllers\Admin;

use App\Models\PackageOption;
use App\Models\PackageOptionTrans;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Datatables;

class PackageOptionsController extends Controller
{
    public function __construct()
    {
        $this->mainModel = PackageOption::class;
        
        $this->middleware('permission:admin_view_package_options', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_package_options', ['only' => 'destroy']);
        
    }
    
    public function index()
    {
        return view('admin.package_options.index');
    }
    
    public function getPackageOptions(Request $request)
    {
        if (!$request->ajax()) {
            abort('404');
        }
        
        $package_options = PackageOption::query();
        
        return Datatables::eloquent($package_options)
            ->editColumn('status', function ($package_option) {
                return '<div class="status' . $package_option->id . '">' . $package_option->getStatus() . '</div>';
            })
            ->addColumn('action', function ($package_option) {
                if ($package_option->active == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $deleteName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $deleteName = trans("global.btn.activate");
                }
                
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $package_option->id . '" data-href="' . route('admin.package-options.change-status',
                        $package_option->id) . '" onclick="changeElementStatus(this)">' . $deleteName . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.package-options.edit',
                        $package_option->id) . '">' . trans('global.btn.edit') . '</a>';
                
                if (config('base.PackageOption.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.package-options.destroy',
                            $package_option->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function create()
    {
        $languages = config('languages');
        
        return view('admin.package_options.edit', ['languages' => $languages]);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'translation.*.name'        => 'required',
            'translation.*.description' => 'required',
        ]);
        
        $languages = config('languages');
        
        $package_option = new PackageOption();
        isset($request->active) ? $package_option->active = 1 : $package_option->active = 0;
        
        foreach ($languages as $locale) {
            $package_option->translateOrNew($locale)->name = $request->translation[$locale]['name'];
            $package_option->translateOrNew($locale)->description = $request->translation[$locale]['description'];
            $package_option->slug = generateSlug([
                'class'   => PackageOption::class,
                'element' => $request->translation['en']['name'],
            ]);
        }
        
        if ($package_option->save()) {
            $alert_message = trans('admin/package_option.alert.c_success');
            $alert_status = 'success';
        }
        
        return redirect(route('admin.package-options.edit', $package_option->id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
    }
    
    public function edit($id)
    {
        $package_option = PackageOption::findOrFail($id);
        $languages = config('languages');
        
        foreach ($languages as $language) {
            $item['translation'][$language]['name'] = empty($package_option->translate($language)) ? '' : $package_option->translate($language)->name;
            $item['translation'][$language]['description'] = empty($package_option->translate($language)) ? '' : $package_option->translate($language)->description;
        }
        
        $package_option->translation = $item['translation'];
        
        return view('admin.package_options.edit', ['package_option' => $package_option, 'languages' => $languages]);
    }
    
    public function update(Request $request, $id)
    {
        $package_option = PackageOption::findOrFail($id);
        
        $this->validate($request, [
            'translation.' . defaultLocale() . '.name'        => 'required',
            'translation.' . defaultLocale() . '.description' => 'required',
            'slug'                                            => 'required',
        ]);
        
        $languages = config('languages');
        isset($request->active) ? $package_option->active = 1 : $package_option->active = 0;
        
        foreach ($languages as $locale) {
            $package_option->translateOrNew($locale)->name = $request->translation[$locale]['name'];
            $package_option->translateOrNew($locale)->description = $request->translation[$locale]['description'];
        }
        
        if ($package_option->save()) {
            $alert_message = trans('admin/package_option.alert.u_success');
            $alert_status = 'success';
        }
        
        return redirect(route('admin.package-options.edit', $id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
    }
    
    public function changeStatus($id)
    {
        $class = $this->mainModel;
        $element = $class::findOrFail($id);
        
        if ($element->active == 1) {
            $element->active = 0;
            $rclass = config('base.btn.deactivate');
            $aclass = config('base.btn.activate');
            $newstatus = trans('global.sts.deactivated');
            $newStatusBtn = trans('global.btn.activate');
        } else {
            $element->active = 1;
            $rclass = config('base.btn.activate');
            $aclass = config('base.btn.deactivate');
            $newstatus = trans('global.sts.activated');
            $newStatusBtn = trans('global.btn.deactivate');
        }
        $element->save();
        
        return response()->json([
            'status'       => 'ok',
            'newstatus'    => $newstatus,
            'newstatusbtn' => $newStatusBtn,
            'aclass'       => $aclass,
            'rclass'       => $rclass,
        ]);
    }
    
    public function destroy($id)
    {
        if (config('base.PackageOption.delete') == false) {
            die();
        }
        
        $package_option = PackageOption::find($id);
        
        if ($package_option) {
            if (config('base.PackageOption.forceDelete') == true) {
                $package_option->forceDelete();
            } else {
                $package_option_trans = PackageOptionTrans::where('package_option_id', $id)->get();
                foreach ($package_option_trans as $poT) {
                    $poT->delete();
                }
                $package_option->delete();
            }
            
            return response()->json(['status' => 'ok']);
        }
        
        return response()->json([
            'status'  => 'error',
            'message' => trans('admin/admin.error.package-option-not-found'),
        ]);
    }
    
}
