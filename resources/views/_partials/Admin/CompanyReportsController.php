<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Feedback;
use App\Models\ReportFeedback;
use App\Models\User;
use App\Notifications\ReviewReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;


class CompanyReportsController extends Controller
{

    public function __construct()
    {
        $this->mainModel = ReportFeedback::class;
    }

    public function index() {

        return view('admin.company_reports.index');

    }

    public function changeStatus($id)
    {
        $class = $this->mainModel;
        $element = $class::findOrFail($id);

        if ($element->status === 1) {
            $element->status = 0;
            $rclass = config('base.btn.activate');
            $aclass = config('base.btn.deactivate');
            $newstatus = 'Pending';
            $newStatusBtn = 'Pending';
        } else {
            $element->status = 1;
            $rclass = config('base.btn.deactivate');
            $aclass = config('base.btn.activate');
            $newstatus = 'Processed';
            $newStatusBtn = 'Processed';
        }
        $element->save();
        return response()->json(['status' => 'ok', 'newstatus' => $newstatus, 'newstatusbtn' => $newStatusBtn, 'aclass' => $aclass, 'rclass' => $rclass]);
    }


    public function get_dt(Request $request) {

        if (!$request->ajax()) {
            abort('404');
        }

        $reports = ReportFeedback::query()->select(DB::raw('companies.name as cname'),'report_feedback.*')
            ->leftJoin('companies', 'report_feedback.model_id', 'companies.id')
            ->groupBy('report_feedback.id');


        return Datatables::eloquent($reports)
            ->filterColumn('cname', function ($reports, $keyword) {
                $sql = "companies.name like ?";
                $reports->select(DB::raw('companies.name as cname'), 'report_feedbacks.*')
                    ->leftJoin('companies', 'report_feedbacks.model_id', 'companies.id');
                $reports->whereRaw($sql, ["%{$keyword}%"]);

            })
            ->editColumn('status', function ($reports) {
                return '<div class="status' . $reports->id . '">' . $reports->getStatus() . '</div>';
            })
            ->editColumn('solved', function ($reports) {

                return '<div class="lable text-center ' . $reports->getSolved()[1] . '">' . $reports->getSolved()[0] . '</div>';
            })
            ->addColumn('action', function ($reports) {

                if ($reports->status == 1) {
                    $statusClass = config('base.btn.activate');
                    $statusName = "Processed";
                } else {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = "Pending";
                }


                $action = '<div class="btn-group btn-group-justified">
                <a class="' . config('base.btn.view') . '" href="' . route('admin.reports.show', $reports->id) . '">' . trans('global.btn.view') . '</a>';
                $action .= ' <a class="' . $statusClass . '" href ="javascript:void(0);" data-id="' . $reports->id . '" data-href="' . route('admin.reports.change-status', $reports->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.reports.destroy', $reports->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action','status','solved'])
            ->make(true);
    }

    public function show($id) {

        $report = ReportFeedback::with('user','review')->findOrFail($id);
        $company = Company::find($report->review->model_id);

        return view('admin.company_reports.show')->with(['report' => $report, 'company' => $company]);

    }

    public function solved($id, $action) {
        // $action ↓
        // 0 - pending
        // 1 - approved
        // 2 - disapprove
        $report = ReportFeedback::findOrFail($id);

        $report->status = 1;
        $report->solved = $action;

        if ($action == 1) {

            Feedback::find($report->review->id)->delete();
            $user = User::find($report->user_id);
            $user->notify(new ReviewReport(1,$report));
        } else {

            $user = User::find($report->user_id);
            $user->notify(new ReviewReport(2,$report));

        }

        $report->save();

        return redirect()->route('admin.reports.index');

    }

    public function destroy(Request $request, $id) {

        if (!$request->ajax()) {
            abort('404');
        }

        ReportFeedback::find($id)->delete();
        return response()->json(['status' => 'ok']);

    }

}
