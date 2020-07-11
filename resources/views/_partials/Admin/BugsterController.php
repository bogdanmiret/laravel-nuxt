<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bugster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class BugsterController extends Controller
{
	public function index()
	{
		$graph = DB::table('bugsters')
			->select(DB::raw('DATE(created_at) as date') , DB::raw('count(*) as times'))
			->groupBy('date')
			->orderBy('date' , 'asc')
			->get()->toArray();
		
		return view('admin.bugster.index')->with([
			'graph' => json_encode($graph) ,
		]);
	}
	
	
	public function show($id)
	{
		$bugster = Bugster::findOrFail($id);
		
		$same_error_count = Bugster::where('path' , $bugster->path)->count();
		$first_occurrence = Bugster::where('path' , $bugster->path)->orderBy('created_at' , 'asc')->first()->created_at->toDateTimeString();
		$last_occurrence = Bugster::where('path' , $bugster->path)->orderBy('created_at' , 'desc')->first()->created_at->toDateTimeString();
		$users_affected = Bugster::where('path' , $bugster->path)->distinct('ip_address')->count('ip_address');
		
		
		$graph = DB::table('bugsters')
			->where('path' , $bugster->path)
			->select(DB::raw('DATE(created_at) as date') , DB::raw('count(*) as times'))
			->groupBy('date')
			->orderBy('date' , 'asc')
			->get()->toArray();
		
		
		return view('admin.bugster.show')->with([
			'bugster'          => $bugster ,
			'count'            => $same_error_count ,
			'first_occurrence' => $first_occurrence ,
			'last_occurrence'  => $last_occurrence ,
			'graph'            => json_encode($graph) ,
			'users_affected'   => $users_affected ,
		]);
	}
	
	
	public function datatable()
	{
		return Datatables::queryBuilder(DB::table('bugsters')->select('id', 'method', 'path', 'status_code', 'app_env', 'app_name', 'reported', 'created_at', DB::raw('count(*) as occurrences'))->groupBy('path'))
			->addColumn('action' , function ($bug) {
				$action = '<div class="btn-group btn-group-justified">
                <a class="btn btn-warning" href="' . route('admin.bugster.show' , $bug->id) . '">' . trans('global.btn.more_info') . '</a>';
				$action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.bugster.destroy' , $bug->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
				
				$action .= '</div>';
				
				return $action;
			})
			->make(TRUE);
	}
	
	public function destroy($id)
	{
		$bug = Bugster::find($id);
		
		DB::table('bugsters')->where('path', $bug->path)->delete();
		
		return response()->json(['status' => 'ok']);
	}
}
