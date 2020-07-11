<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplatesCategoriesController extends Controller
{

    public $langs;

    public function __construct() {
        $this->langs = config('languages');
    }

    public function index() {
        return view('admin.email-templates-categories.index');
    }

    public function getCategories(Request $request) {

        if (!$request->ajax()) {
            abort('404');
        }

        $email_categories = EmailType::where('slug', '!=', 'action-mails')->get();

        return Datatables::collection($email_categories)
            ->addColumn('action', function ($email_categories) {

                $action = '<div class="btn-group btn-group-justified">';

                $action .= '<a class="' . config('base.btn.edit') . '" href="' . route('admin.email-categories.edit', $email_categories->id) . '">' . trans('global.btn.edit') . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.email-categories.destroy', $email_categories->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';

                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }

    public function edit(Request $request, $id){

        $category = EmailType::findOrFail($id);

        foreach ($this->langs as $key => $locale) {
            $item['translation'][$locale]['name'] = empty($category->translate($locale)) ? '' : $category->translate($locale)->name;
        }

        $category->translation = $item['translation'];

        return view('admin.email-templates-categories.edit')->with(['category' => $category, 'languages' => $this->langs]);
    }

    public function update (Request $request, $id) {

        $category = EmailType::findOrFail($id);

        $this->validate($request, [
            'translation.*.name' => 'required'
        ]);

        foreach ($this->langs as $key => $locale) {

            $category->translateOrNew($locale)->name = $request['translation'][$locale]['name'];

        }

        if ($category->save()) {
            $alert_message = trans('admin/alerts.messages.updated', ['name' => 'Category']);
            $alert_status = 'success';
        }

        return redirect(route('admin.email-categories.index'))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    public function create() {

        return view('admin.email-templates-categories.edit')->with(['languages' => $this->langs]);
    }

    public function store(Request $request) {

        $slug = str_slug($request['translation'][defaultLocale()]['name']);
        $name = $request['translation'][defaultLocale()]['name'];

        $checkType = EmailType::where('slug', $slug)->first();

        if ($checkType !== null) {
            $alert_message = trans('admin/alerts.messages.exists', ['name' => 'Email category']);
            return back()->with(['status' => 'warning', 'message' => $alert_message]);
        }


        $category = new EmailType();
        $category->slug = $slug;
        $category->name = $name;

        $this->validate($request, [
            'translation.*.name' => 'required',
        ]);

        foreach ($this->langs as $key => $locale) {
            $category->translateOrNew($locale)->name = $request['translation'][$locale]['name'];
        }

        if ($category->save()) {
            $alert_message = trans('admin/alerts.messages.created', ['name' => 'Email category']);
            $alert_status = 'success';
        }

        return redirect(route('admin.email-categories.edit', $category->id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    public function destroy($id) {

        EmailType::findOrFail($id)->delete();

        return response()->json(['status' => 'ok']);

    }




}
