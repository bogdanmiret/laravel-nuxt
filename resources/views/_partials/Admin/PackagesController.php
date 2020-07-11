<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\JsonList;
use App\Models\Package;
use App\Models\PackageOption;
use App\Models\PackageTrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class PackagesController extends Controller
{
    public function __construct()
    {
        $this->mainModel = Package::class;

		$this->middleware('permission:admin_view_packages', ['except' => 'destroy']);
		$this->middleware('permission:admin_delete_packages', ['only' => 'destroy']);
		
		$this->validate_rules = [
            'translation.*.name'        => 'required',
            'translation.*.description' => 'required'
        ];
    }
    
    public function index()
    {
        return view('admin.packages.index');
    }
    
    public function create()
    {
        $package_options = PackageOption::all();
        
        $countries_json = JsonList::where('slug', 'country_specific')->first()->translateOrDefault('en')->json_value;
        $countries_currency = collect(json_decode($countries_json));
        $currencies = Currency::all();
        
        foreach ($countries_currency as $country_currency_key => $country_currency) {
            $currency_pivot = null;
            $currency = $currencies->where('code', $country_currency->currency)->first();
            
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['price'] = isset($currency->price) ? $currency->price : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['discount'] = isset($currency->discount) ? $currency->discount : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['discount_valid'] = isset($currency->discount_valid) ? $currency->discount_valid : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['name'] = $country_currency->country . ' - ' . $country_currency->currency;
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['currency_id'] = isset($currency->currency_id) ? $currency->currency_id : $currency->id;
        }
        
        $package = new \stdClass(); // DO NOT ADD THE ID FIELD!
        $package->active = 0;
        $package->discount_active = 0;
        $package->currencies = $item['currencies'];
        
        return view('admin.packages.edit', [
            'package_options' => $package_options,
            'package'         => $package,
        ]);
    }
    
    public function store(Request $request)
    {
        $package = new Package();
        
        $this->validate($request, $this->validate_rules);
        
        $package->active = isset($request->active) ? 1 : 0;
        $package->discount_active = isset($request->discount_active) ? 1 : 0;
        
        foreach (config('languages') as $locale) {
            $package->translateOrNew($locale)->name = $request->translation[$locale]['name'];
            $package->translateOrNew($locale)->description = $request->translation[$locale]['description'];
        }
        $package->slug = $request->translation['en']['name']; // can be hardcoded to en because all languages are required
        $package->save();
        
        $package->options()->sync($request->packages);
        $currencies = [];
        
        foreach ($request->currencies as $currency_id => $prices) {
            foreach ($prices as $isocode => $price) {
                if (!empty($price['price'])) {
                    $currencies[] = [
                        'package_id'  => $package->id,
                        'currency_id' => $currency_id,
                        'price'       => $price['price'],
                        'discount'    => $price['discount'],
                        'isocode'     => $isocode,
                        'discount_valid' => $price['discount_valid']
                    ];
                }
            }
            
        }
        
        DB::table('currency_package')->insert($currencies);
        
        if ($package->save()) {
            $alert_message = trans('admin/package.alert.c_success');
            $alert_status = 'success';
        }
        
        return redirect(route('admin.packages.edit', $package->id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
    }
    
    public function edit($id)
    {
        $package = Package::with('options', 'currencies')->find($id);
        $package_options = PackageOption::all();
        
        foreach (config('languages') as $language) {
            $item['translation'][$language]['name'] = empty($package->translate($language)) ? '' : $package->translate($language)->name;
            $item['translation'][$language]['description'] = empty($package->translate($language)) ? '' : $package->translate($language)->description;
        }
        
        $countries_json = JsonList::where('slug', 'country_specific')->first()->translateOrDefault('en')->json_value;
        $countries_currency = collect(json_decode($countries_json));
        $currencies = Currency::all();
        
        $currency_package = DB::table('currencies')
            ->select(
                DB::raw('packages.id as package_id'),
                DB::raw('packages.discount_active as discount_active'),
                DB::raw('currencies.id as currency_id'),
                DB::raw('currencies.code as currency_code'),
                DB::raw('currency_package.isocode as isocode'),
                DB::raw('currency_package.price as price'),
                DB::raw('currency_package.discount as discount'),
                DB::raw('currency_package.discount_valid as discount_valid')
            )
            ->leftjoin('currency_package', 'currency_package.currency_id', 'currencies.id')
            ->join('packages', 'packages.id', 'currency_package.package_id')
            ->where('packages.id', $id)
            ->get();
        //TODO this script can be optimised
        foreach ($countries_currency as $country_currency_key => $country_currency) {
            $currency_pivot = null;
            
            $currency = $currency_package->where('currency_code', $country_currency->currency)
                ->where('isocode', $country_currency->isocode)
                ->first();
            
            if (!isset($currency->currency_id)) {
                $currency = $currencies->where('code', $country_currency->currency)->first();
            }
            
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['price'] = isset($currency->price) ? $currency->price : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['discount'] = isset($currency->discount) ? $currency->discount : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['discount_valid'] = isset($currency->discount_valid) ? $currency->discount_valid : '';
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['discount_active'] = isset($currency->discount_active) ? $currency->discount_active : 0;
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['name'] = $country_currency->country . ' - ' . $country_currency->currency;
            $item['currencies'][$currency->currency_id][$country_currency->isocode]['currency_id'] = isset($currency->currency_id) ? $currency->currency_id : $currency->id;
        }
        
        $package->translation = $item['translation'];
        $package->currencies = $item['currencies'];
        
        return view('admin.packages.edit', [
            'package'            => $package,
            'package_options'    => $package_options,
            'countries_currency' => $countries_currency,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $package = Package::find($id);
    
        $this->validate($request, $this->validate_rules);
    
        $package->active = isset($request->active) ? 1 : 0;
        $package->discount_active = isset($request->discount_active) ? 1 : 0;
        
        foreach (config('languages') as $locale) {
            $package->translateOrNew($locale)->name = $request->translation[$locale]['name'];
            $package->translateOrNew($locale)->description = $request->translation[$locale]['description'];
        }
        
        $package->slug = $request->slug;
        
        $package->options()->sync($request->packages);
        $currencies = [];
        
        foreach ($request->currencies as $currency_id => $prices) {
            foreach ($prices as $isocode => $price) {
                if (!empty($price['price'])) {
                    $currencies[] = [
                        'package_id'  => $package->id,
                        'currency_id' => $currency_id,
                        'price'       => $price['price'],
                        'discount'    => $price['discount'],
                        'discount_valid'    => $price['discount_valid'],
                        'isocode'     => $isocode,
                    ];
                }
            }
            
        }
        
        DB::table('currency_package')->where('package_id', $package->id)->delete();
        DB::table('currency_package')->insert($currencies);
        
        if ($package->save()) {
            $alert_message = trans('admin/package.alert.u_success');
            $alert_status = 'success';
        }
        
        return redirect(route('admin.packages.edit', $id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
    }
    
    public function getPackages(Request $request)
    {
        if (!$request->ajax()) {
            abort('404');
        }
        
        $packages = Package::query();
        
        return Datatables::eloquent($packages)
            ->editColumn('status', function ($package) {
                return '<div class="status' . $package->id . '">' . $package->getStatus() . '</div>';
            })
            ->addColumn('action', function ($package) {
                if ($package->active == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $deleteName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $deleteName = trans("global.btn.activate");
                }
                
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $package->id . '" data-href="' . route('admin.package.change-status', $package->id) . '" onclick="changeElementStatus(this)">' . $deleteName . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.packages.edit', $package->id) . '">' . trans('global.btn.edit') . '</a>';
                
                if (config('base.Package.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.packages.destroy',
                            $package->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
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
        
        return response()->json(['status'       => 'ok',
                                 'newstatus'    => $newstatus,
                                 'newstatusbtn' => $newStatusBtn,
                                 'aclass'       => $aclass,
                                 'rclass'       => $rclass,
        ]);
    }
    
    public function destroy($id)
    {
        if (config('base.Package.delete') == false) {
            die();
        }
        
        $package = Package::find($id);
        if ($package) {
            if (config('base.Package.forceDelete') == true) {
                $package->forceDelete();
            } else {
                $packageTrans = PackageTrans::where('package_id', $id)->get();
                foreach ($packageTrans as $pT) {
                    $pT->delete();
                }
                $package->delete();
            }
            
            return response()->json(['status' => 'ok']);
        }
        
        return response()->json(['status' => 'error', 'message' => trans('admin/admin.error.page-not-found')]);
    }
}
