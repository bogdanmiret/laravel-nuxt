<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryTrans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sleimanx2\Plastic\Facades\Plastic;
use Datatables;

class CompanyCategoriesController extends Controller
{
	private $langs = [];
	
	public function __construct()
	{
		$this->langs = config('languages');
	}
	
	public function index()
	{
		
		return view('admin.company_categories.index');
	}
	
	public function create()
	{
		return view('admin.company_categories.show')->with([
			'languages' => $this->langs ,
		]);
	}
	
	
	public function edit_company_category(Category $category)
	{
		
		$item = $category->toArray();
		
		foreach($this->langs as $language) {
			$item['translation'][$language]['name'] = empty($category->translate($language)) ? '' : $category->translate($language)->name;
			$item['translation'][$language]['slug'] = empty($category->translate($language)) ? '' : $category->translate($language)->slug;
			$item['translation'][$language]['synonyms'] = empty($category->translate($language)) ? '' : $category->translate($language)->synonyms;
			
		}
		
		
		return view('admin.company_categories.show')->with([
			'item'      => $item ,
			'languages' => $this->langs ,
		]);
	}
	
	public function get_company_categories()
	{
		$categories = CategoryTrans::select('category_trans.*');
		
		return Datatables::eloquent($categories)
			->addColumn('action' , function ($category) {
				$action =
					'<div class="btn-group btn-group-justified">
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.edit_company_category' , $category->category_id) . '">' . trans('global.btn.edit') . '</a>';
				$action .= '</div>';
				
				return $action;
			})
			->make(TRUE);
	}
	
	
	public function update_company_category(Request $request , $id)
	{
		
		$category = Category::findOrFail($id);
		
		
		$this->validate($request , [
			'translation.' . config('app.locale') . '.name' => 'required' ,
			'translation.' . config('app.locale') . '.slug' => 'required' ,
		]);
		
		$input = $request->all();
		
		foreach($this->langs as $locale) {
			if(strlen($input['translation'][$locale]['name'])) {
				$category->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			} else {
				$category->translateOrNew($locale)->name = $input['translation'][config('app.locale')]['name'];
			}
			
			if(strlen($input['translation'][$locale]['slug'])) {
				$category->translateOrNew($locale)->slug = $input['translation'][$locale]['slug'];
			} else {
				$category->translateOrNew($locale)->slug = $input['translation'][config('app.locale')]['slug'];
			}
			
			if(strlen($input['translation'][$locale]['synonyms'])) {
				$category->translateOrNew($locale)->synonyms = $input['translation'][$locale]['synonyms'];
			} else {
				$category->translateOrNew($locale)->synonyms = $input['translation'][config('app.locale')]['synonyms'];
			}
			
		}
		$category->save();
		
		$category->companies()->chunk(500, function ($companies) {
				Plastic::persist()->bulkSave($companies);
		});
		
		
		return back()->with('success' , 'Updated successfully');
	}
	
	
	public function store(Request $request)
	{
		$this->validate($request , [
			'translation.' . config('app.locale') . '.name' => 'required' ,
			'translation.' . config('app.locale') . '.slug' => 'required' ,
		]);
		
		$category = new Category();
		$input = $request->all();
		
		
		foreach($this->langs as $locale) {
			if(strlen($input['translation'][$locale]['name'])) {
				$category->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			} else {
				$category->translateOrNew($locale)->name = $input['translation'][config('app.locale')]['name'];
			}
			
			if(strlen($input['translation'][$locale]['slug'])) {
				$category->translateOrNew($locale)->slug = $input['translation'][$locale]['slug'];
			} else {
				$category->translateOrNew($locale)->slug = $input['translation'][config('app.locale')]['slug'];
			}
			
			if(strlen($input['translation'][$locale]['synonyms'])) {
				$category->translateOrNew($locale)->synonyms = $input['translation'][$locale]['synonyms'];
			} else {
				$category->translateOrNew($locale)->synonyms = $input['translation'][config('app.locale')]['synonyms'];
			}
			
		}
		
		$category->save();
		
		
		return redirect(route('admin.edit_company_category' , $category->id))->with('success' , 'success');
		
		
	}
	
	
	public function destroy(Category $category)
	{
//		$this->authorize('delete', $category);
		
		$category->delete();
		
		return redirect(route('admin.company_categories_data_table'))->with('success', 'Category deleted successfully');
	}
}
