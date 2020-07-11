<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExternalVisitCounter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class ExternalLinksController extends Controller
{


    public function index()
    {
        $links = ExternalVisitCounter::all();

        $statistics = DB::table('external_visit_counters')
            ->select('name', DB::raw('sum(visits) as total'))
            ->groupBy('name')
            ->orderby('total', 'desc')
            ->limit(10)
            ->get();
        return view('admin.external-links.index', compact('links', 'statistics'));
    }

    public function Getlinks()
    {
        $links = ExternalVisitCounter::all();
        return Datatables::of($links)
            ->make(true);
    }
}
