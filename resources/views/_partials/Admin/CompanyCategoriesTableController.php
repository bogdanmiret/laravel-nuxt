<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;

class CompanyCategoriesTableController extends Controller
{
	public function __invoke(Request $request)
	{
		
		if (!$request->ajax()) {
			abort('404');
		}
		
//		$restaurants = Company::with('city')->select('companies.*')->orderby('created_at', 'desc');
		$categories = Category::with('translations')->get();
		
		
		return Datatables::of($categories)
//			->editColumn('name_format', function ($category) {
//				return isset($category->name) ? $category->
//				return $category->approved_label;
//			})
//			->editColumn('active', function ($category) {
//				return $category->approved_label;
//			})
//			->addColumn('actions', function ($category) {
//				return $category->action_buttons;
//			})
			->make(TRUE);

	}
}
