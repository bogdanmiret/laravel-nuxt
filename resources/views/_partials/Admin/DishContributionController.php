<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Media;
use App\Models\Newdish;
use App\Models\User;
use App\Notifications\ContributionDenied;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DishContributionController extends Controller
{
    public function __construct() {
        $this->middleware('permission:admin_view_dishes');
    }

	public function index()
	{
		return view('admin.dish_contributions.index');
	}

	public function show($contribution)
	{
		$contribution = Media::find($contribution);

		$user = User::find($contribution->custom_properties['user_id']);

//		$restaurant = Company::find($contribution->model_id);
		$dish = Newdish::find($contribution->model_id);
		return view('admin.dish_contributions.show', compact('contribution', 'user', 'dish'));
	}

	public function destroy($contribution)
	{
		$contribution = Media::find($contribution);
		if($contribution) {
			$user = User::find($contribution->custom_properties['user_id']);

			$user->notify(new ContributionDenied($contribution));
	
			$model = $contribution->model_type::find($contribution->model_id);
	
			if($model){
				$model->deleteMedia($contribution->id);
			}
	
			return response()->json(['status' => 'success', 'redirect' => route('admin.dish_contribution.index')]);
		}

		if(request()->ajax()) {
			return response()->json('Contribution id not found', 404);
		}

		return redirect()->back('Something went wrong');
		
	}
}
