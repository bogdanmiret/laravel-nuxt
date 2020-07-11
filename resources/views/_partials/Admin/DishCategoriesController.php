<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryTrans;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;

class DishCategoriesController extends Controller
{
	private $langs = [];
	
	public function __construct()
	{
		$this->langs = config('languages');
        $this->middleware('permission:admin_view_dishes');

    }
	
	public function index()
	{
		
		return view('admin.dish_categories.index');
	}
	
	public function create()
	{
		return view('admin.dish_categories.show')->with([
			'languages' => $this->langs ,
		]);
	}
	
	
	public function edit_dish_category(Type $type)
	{
		$item = $type->toArray();
		
		foreach($this->langs as $language) {
			$item['translation'][$language]['name'] = empty($type->translate($language)) ? '' : $type->translate($language)->name;
			
		}
		
		return view('admin.dish_categories.show')->with([
			'item'      => $item ,
			'languages' => $this->langs ,
		]);
	}
	
	public function get_dish_categories()
	{
		$categories = Type::orderBy('dish_count' , 'desc');
		
		return Datatables::eloquent($categories)
			->addColumn('action' , function ($category) {
				$action =
					'<div class="btn-group btn-group-justified">
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.edit_dish_category' , $category->id) . '">' . trans('global.btn.edit') . '</a>';
				$action .= '</div>';
				
				return $action;
			})
			->make(TRUE);
	}
	
	
	public function update_dish_category(Request $request , $id)
	{
		$category = Type::findOrFail($id);
		$this->validate($request , [
			'translation.' . config('app.locale') . '.name' => 'required' ,
		]);
		
		$input = $request->all();
		foreach($this->langs as $locale) {
			if(strlen($input['translation'][$locale]['name'])) {
				$category->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			} else {
				$category->translateOrNew($locale)->name = $input['translation'][config('app.locale')]['name'];
			}
			
		}
		$category->save();
		
		return back()->with('success' , 'Updated successfully');
	}
	
	
	public function store(Request $request)
	{
		$this->validate($request , [
			'name' => 'required' ,
		]);
		
		$category = new Category();
		$category->name = $request->name;
		$category->save();
		
		
		return redirect(route('admin.edit_dish_category' , $category->id))->with('success' , 'success');
		
		
	}
	
	
	public function destroy(Type $type)
	{
//		$this->authorize('delete', $category);
		
		$type->delete();
		
		return redirect(route('admin.company_categories_data_table'))->with('success' , 'Category deleted successfully');
	}
}
