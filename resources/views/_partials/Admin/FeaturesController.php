<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feature;
use App\Models\FeatureTrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Datatables;
use Illuminate\Support\Facades\DB;

class FeaturesController extends Controller
{

	public $langs = [];
	public function __construct()
	{
		$this->langs = config('languages');
		$this->mainModel = Feature::class;
	}

	public function index()
	{
		return view('admin.features.index');
	}


    public function GetFeatures()
    {
        $features = FeatureTrans::query()->select('features.*', DB::raw('feature_trans.name as name, feature_trans.slug as slug, feature_trans.question as question'))
            ->leftJoin('features', 'feature_trans.feature_id', 'features.id')
            ->groupBy('features.id');

        return Datatables::eloquent($features)
		    ->editColumn('status', function ($feature) {
			    return $feature->status ? "Active" : "Disabled";
		    })
            ->editColumn('slug', function ($feature) {
                return ($feature->slug != null && !empty($feature->slug)) ?  $feature->slug :  '('.trans('global.fld.slug_missing').')';
            })
		    ->addColumn('action', function ($feature) {
			
			    if ($feature->status == 1) {
				    $statusClass = config('base.btn.deactivate');
				    $statusName = trans("global.btn.deactivate");
			    } else {
				    $statusClass = config('base.btn.activate');
				    $statusName = trans("global.btn.activate");
			    }
			    $action = '<div class="btn-group ">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $feature->id . '" data-href="' . route('admin.feature.change-status', $feature->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.features.edit', $feature->id) . '">' . trans('global.btn.edit') . '</a>';
			
			    if (config('base.landings.delete') == true) {
				    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.features.destroy', $feature->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
			    }
			    $action .= '</div>';
			
			    return $action;
		    })
		    ->make(true);

    }



	public function create()
	{
		$languages = config('languages');
		

		return view('admin.features.edit', compact ('languages'));
	}



	public function destroy(Feature $feature)
	{

		\Log::info($feature .' deleted.');

		$feature->delete();

		return response()->json(['status' => 'ok']);

	}
	
	public function update(Request $request, Feature $feature)
	{
		$input = $request->all();

		$feature->importance = $input['importance'];
		$feature->status = $input['status'];

		$languages = $this->langs;

		foreach ($languages as $locale) {
			$feature->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			$feature->translateOrNew($locale)->slug = $input['translation'][$locale]['slug'];
			$feature->translateOrNew($locale)->question = $input['translation'][$locale]['question'];

		}
		$feature->save();
		return redirect(route('admin.features.edit', $feature->id))->with('success', 'success');

	}
	public function store(Request $request)
	{
		$feature = new Feature();

		$languages = $this->langs;


			foreach($languages as $language)
			{
				$this->validate($request, [
					'translation.' . $language . '.name' => 'required',
					'status' => 'required|between:0,1',
					'translation.' . $language . '.slug' => 'required',   // |unique:feature_trans,slug'
					'importance' => 'required|numeric',
					'translation.' . $language . '.question' => 'required',
				]);
			}


		$input = $request->all();

		$feature->status = $input['status'];
		$feature->importance = $input['importance'];


		foreach ($languages as $locale) {
				$feature->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
				$feature->translateOrNew($locale)->slug = $input['translation'][$locale]['slug'];
				$feature->translateOrNew($locale)->question = $input['translation'][$locale]['question'];
		}

		$feature->save();


		return redirect(route('admin.features.edit', $feature->id))->with('success', 'success');
		
	}

	public function edit(Feature $feature)
	{
		$item = $feature->toArray();
		$item['status'] = $feature->status;
		$item['importance'] = $feature->importance;
		foreach ($this->langs as $language) {
			$item['translation'][$language]['name'] = empty($feature->translate($language)) ? '' : $feature->translate($language)->name;
			$item['translation'][$language]['slug'] = empty($feature->translate($language)) ? '' : $feature->translate($language)->slug;
			$item['translation'][$language]['question'] = empty($feature->translate($language)) ? '' : $feature->translate($language)->question;
		}



		$languages = $this->langs;

		return view('admin.features.edit', [
			'item' => $item,
			'languages' => $languages,
		]);
	}

}
