<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class FAQCategoriesController extends Controller
{
    public function __construct()
    {
        $this->langs = config('languages');
        $this->mainModel = FaqCategory::class;
        $this->middleware('permission:admin_view_faq', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_faq', ['only' => 'destroy']);
    }

    public function validation(Request $request)
    {
        $this->validate($request, [
            'translation.' . defaultLocale() . '.name' => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq_cat = FaqCategory::all();

        return view('admin.faq-categories.index')->with('faq', $faq_cat);
    }

    public function getFAQCategories(Request $request)
    {

        if(!$request->ajax()){
            abort('404');
        }

        $faq_cats = FaqCategory::all();
        return Datatables::of($faq_cats)
            ->editColumn('status', function ($faq_cat) {
                return '<div class="status' . $faq_cat->id . '">' . $faq_cat->getStatus() . '</div>';
            })
            ->addColumn('faq_count', function ($faq_cat) {
                return $faq_cat->countFAQ();
            })
            ->addColumn('action', function ($faq_cat) {

                if ($faq_cat->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $faq_cat->id . '" data-href="' . route('admin.faq-categories.change-status', $faq_cat->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.faq-categories.edit', $faq_cat->id) . '">' . trans('global.btn.edit') . '</a>';

                if (config('base.FaqCategory.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.faq-categories.destroy', $faq_cat->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.faq-categories.edit', ['languages' => $this->langs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

        $input = $request->all();

        $faq_cat = new FaqCategory();

        foreach ($this->langs as $locale) {
            $faq_cat->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
        }

        if($faq_cat->save()){
            $alert_message = trans('admin/faq.alert_category.c_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.faq-categories.edit', $faq_cat->id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faq_cat = FaqCategory::find($id);


        $item = $faq_cat->toArray();
        foreach ($this->langs as $language) {
            $item['translation'][$language]['name'] = empty($faq_cat->translate($language)) ? '' : $faq_cat->translate($language)->name;
        }

        return view('admin.faq-categories.edit', [
            'item' => $item,
            'languages' => $this->langs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'translation.' . config('app.locale') . '.name' => 'required',
        ]);

        $input = $request->all();

        // update the langs
        $faq_cat = FaqCategory::find($id);

        foreach ($this->langs as $locale) {
            $faq_cat->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
        }
        if($faq_cat->save()){
            $alert_message = trans('admin/faq.alert_category.u_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.faq-categories.edit', $id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (config('base.FaqCategory.delete') == false) {
            die();
        }

        $faq_cat = FaqCategory::find($id);

        if($faq_cat->countFAQ() > 0){
            return response()->json(['status' => 'error', 'message' => trans('admin/faq.error_delete_have_faq')]);
        }

        if ($faq_cat) {
            if (config('base.FaqCategory.forceDelete') == true) {
                $faq_cat->forceDelete();
            } else {
                $faq_cat->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/faq.faq_category_not_found')]);

    }
}
