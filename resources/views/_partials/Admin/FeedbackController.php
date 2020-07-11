<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin_delete_feedback_comments', ['only' => 'destroy']);
        $this->middleware('permission:admin_view_feedback_comments');
    }

    public function index()
    {

        return view('admin.feedback.index');
    }

    public function show($id)
    {
        $feedback = Feedback::find($id);

        return view('admin.feedback.show', compact('feedback'));
    }


    public function datatable()
    {
        return Datatables::queryBuilder(DB::table('feedback')->select(
            'feedback.*',
            DB::raw('full_name AS user_name')
        )->where('feedback.deleted_at',
            null)->join('users', 'feedback.user_id', 'users.id'))
            ->addColumn('passed_profanity', function ($feedback) {
                if ($feedback->passed_profanity) {
                    return "<label class='label label-success'>" . trans('admin/labels.general.yes') . "</label>";
                }

                return "<label class='label label-danger'>" . trans('admin/labels.general.no') . "</label>";
            })
            ->addColumn('status', function ($feedback) {
                if ($feedback->status) {
                    return "<label class='label label-success'>" . "Active" . "</label>";
                }

                return "<label class='label label-danger'>" . "Disabled" . "</label>";
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("full_name like ?", ["{$keyword}%"]);
            })
            ->addColumn('action', function ($feedback) {
                $action = '<div class="btn-group btn-group-justified">
                <a class="btn btn-warning" href="' . route('admin.feedback.show',
                        $feedback->id) . '">' . trans('global.btn.more_info') . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.feedback.destroy',
                        $feedback->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action', 'passed_profanity'])
            ->setTotalRecords(1000)
            ->make(true);
    }

    public function mark_status(Feedback $feedback, $status)
    {
        if ($status == 1) {
            $feedback->passed_profanity = 1;
            $feedback->status = 1;
            $feedback->save();
        } else {
            $feedback->status = 0;
            $feedback->save();
        }

        return back()->with([
            'status' => 'success',
            'message' => 'Big success',
        ]);


    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return response()->json(['status' => 'ok',
            'redirect' => route('admin.feedback.index'),
            'message' => "Big success",
        ]);
    }
}
