<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Datatables;


class ClaimsTableController extends Controller
{
    public function __construct()
    {
    
    }
    
    
    public function __invoke(Request $request)
    {
        
        if (!$request->ajax()) {
            abort('404');
        }
        
        $claims = Claim::with('user', 'company.city', 'package.translations')->whereHas('company')->whereHas('user')->whereHas('package')->select('claims.*');
        if ($request->pending == 1) {
            $claims = $claims->where('status', 1);
        }
        
        
        return Datatables::of($claims)
            ->editColumn('approved', function ($claim) {
                return $claim->approved_label;
            })->editColumn('status', function ($claim) {
                return $claim->status_label;
            })->addColumn('actions', function ($claim) {
                return $claim->action_buttons;
            })
            ->rawColumns(['status', 'approved', 'actions'])
            ->make(true);
    }
}
