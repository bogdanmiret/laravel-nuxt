<?php

namespace App\Http\Controllers\Admin;

use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactForm;
use Datatables;

class ContactFormsController extends Controller
{
    public function __construct()
    {
        $this->mainModel = ContactForm::class;

        $this->middleware('permission:admin_view_contact_forms', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_contact_forms', ['only' => 'destroy']);

    }

    public function index($type)
    {
        return view('admin.contact-forms.index')->with('type', $type);
    }


    public function getContactForms(Request $request)
    {
        if (!$request->ajax()) {
            abort('404');
        }

        $type = $request->type == 'regular-mails' ? 1 : 0;
        if ($request->type == 'direct-mails') {
            $type = 1;

            /**
             * In this case we need to keep the old emails too.
             */
            $contactforms = ContactForm::orderBy('created_at', 'DESC')
                ->where('type', $type)
                ->where('to_email', 'premium@dishes.menu')
                ->orWhere('to_email', 'hello@dishes.menu')
                ->orWhere('to_email', 'jobs@dishes.menu')
                ->orWhere('to_email', 'premium@foodlocator.menu')
                ->orWhere('to_email', 'hello@foodlocator.menu')
                ->orWhere('to_email', 'jobs@foodlocator.menu')
                ->get();
        } else {
            $contactforms = ContactForm::orderBy('created_at', 'DESC')->where('type', $type)->get();
        }

        return Datatables::of($contactforms)
            ->editColumn('status', function ($cf) {
                return '<div class="status' . $cf->id . '">' . $cf->getStatus() . '</div>';
            })
            ->editColumn('username', function ($cf) {
                return $cf->getUserName();
            })
            ->editColumn('from_email', function ($cf) {
                return $cf->from_email;
            })
            ->editColumn('subject', function ($cf) {
                return str_limit($cf->subject, 50, '...');
            })
            ->addColumn('action', function ($cf) {

                if ($cf->status == 1) {
                    $statusClass = config('base.btn.unread');
                    $deleteName = trans("global.btn.unread");
                } else {
                    $statusClass = config('base.btn.read');
                    $deleteName = trans("global.btn.read");
                }

                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $cf->id . '" data-href="' . route('admin.contact-forms.change-status', $cf->id) . '" onclick="changeElementStatus(this)">' . $deleteName . '</a>
                        <a class="' . config('base.btn.view') . ' viewContact" href="javascript:;" data-href="' . route('admin.contact-forms.show', $cf->id) . '">' . trans('global.btn.view') . '</a>';

                if (config('base.ContactForm.delete') == true) {

                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.contact-forms.destroy', $cf->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;

            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $contact = ContactForm::find($id);

        $contact->status = 1; // message seen
        $contact->save();
        if ($contact) {
            return view('admin.contact-forms.view', ['contact' => $contact]);
        }
    }

    public function destroy($id)
    {

        if (config('base.ContactForm.delete') == false) {

            die();
        }

        $contact = ContactForm::find($id);
        if ($contact) {

            if (config('base.ContactForm.forceDelete') == true) {

                $contact->forceDelete();
            } else {
                $contact->delete();
            }

            return response()->json(['status' => 'ok']);
        } else {
            return response()->json(['status' => 'error', 'message' => trans('admin/admin.error.contact-form-not-found')]);
        }

    }

    public function changeCFStatus($id)
    {
        $class = $this->mainModel;
        $element = $class::findOrFail($id);

        if ($element->status == 1) {
            $element->status = 0;
            $rclass = config('base.btn.unread');
            $aclass = config('base.btn.read');
            $newstatus = trans('global.sts.unread');
            $newStatusBtn = trans('global.btn.read');
        } else {
            $element->status = 1;
            $rclass = config('base.btn.read');
            $aclass = config('base.btn.unread');
            $newstatus = trans('global.sts.read');
            $newStatusBtn = trans('global.btn.unread');
        }
        $element->save();

        return response()->json(['status' => 'ok', 'newstatus' => $newstatus, 'newstatusbtn' => $newStatusBtn, 'aclass' => $aclass, 'rclass' => $rclass]);
    }

    public function downloadAttachments(Request $request)
    {
        $contact = ContactForm::find($request->id);

        if (!$contact) {
            abort(404, 'Contact form not found!');
        }
        $path = config('base.ContactForm.al') . $request->id . '/';
        $files = $path . '*';
        if (count(glob($files)) > 0) {
            $zip_name = $path . str_slug('contact_' . trim($contact->full_name)) . '.zip';
            Zipper::make($zip_name)->add($path)->close();

            return response()->download($zip_name)->deleteFileAfterSend(true);
        } else {
            abort(404, 'No files found!');
        }
    }
}
