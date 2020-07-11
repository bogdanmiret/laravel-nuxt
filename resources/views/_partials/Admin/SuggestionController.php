<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;
use Sleimanx2\Plastic\Facades\Plastic;

class SuggestionController extends Controller
{
    public function __construct() {
        $this->middleware('permission:admin_view_feedback_suggestions');
        $this->middleware('permission:admin_delete_feedback_suggestions', ['only' => 'destroy']);
    }

    public function index()
    {
        return view('admin.suggestions.index');
    }
    
    public function show($id)
    {
        $suggestion = Suggestion::where('id', $id)->with('company.city', 'user')->first();

        if(!$suggestion || !$suggestion->company) {
            return back()->with(['status' => 'error', 'message' => 'Suggestion company not found']);
        }
        
        return view('admin.suggestions.show', compact('suggestion'));
    }
    
    public function datatable()
    {
        return Datatables::queryBuilder(DB::table('suggestions')
            ->select('suggestions.content', 'suggestions.id', 'suggestions.created_at', 'suggestions.processed',
                'company_id', 'user_id',
                DB::raw('CONCAT(companies.name, " ", companies.id) AS company_name'),
                DB::raw('full_name AS user_name'))
            ->join('users', 'suggestions.user_id', 'users.id')
            ->join('companies', 'suggestions.company_id', 'companies.id'))
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("full_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(companies.name, ' ', companies.id) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('processed_label', function ($suggestion) {
                if ($suggestion->processed) {
                    return "<label class='label label-success'>" . "Processed" . "</label>";
                }
                
                return "<label class='label label-warning'>" . "Pending" . "</label>";
            })
            ->addColumn('action', function ($suggestion) {
                $action = '<div class="btn-group btn-group-justified">
                <a class="btn btn-warning" href="' . route('admin.suggestion.show',
                        $suggestion->id) . '">' . trans('global.btn.more_info') . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.suggestion.destroy',
                        $suggestion->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['processed_label', 'action'])
            ->make(true);
    }
    
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        
        return response()->json([
            'status'   => 'ok',
            'redirect' => route('admin.suggestions.index'),
            'message'  => "Big success",
        ]);
    }
    
    public function mark_processed($id, $status)
    {
        $suggestion = Suggestion::find($id);
        $suggestion->processed = $status;
        $suggestion->save();
        
        return back()->with(['status' => 'success', 'message' => 'Big Success']);
    }

    public function mark_closed($id){

        $restaurant = Company::where('id', $id);
        $restaurantFirst = $restaurant->first();

        $restaurantFirst->closed = (int)!$restaurantFirst->closed;
        $restaurantFirst->save();

//        Plastic::persist()->bulkSave($restaurant->get());

        $message = $restaurantFirst->closed ? 'closed' : 'open';

        return back()->with(['status' => 'success', 'message' => 'Marked as ' . $message]);

    }
    
    
}
