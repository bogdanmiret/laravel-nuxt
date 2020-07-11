<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class FAQsController extends Controller
{
    public function __construct()
    {
        $this->langs = config('languages');
        $this->mainModel = Faq::class;
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
        $faq = Faq::all();

        return view('admin.faqs.index')->with('faq', $faq);
    }

    public function getFAQs()
    {

        $faqs = Faq::all();
        return Datatables::of($faqs)
            ->editColumn('status', function ($user) {
                return '<div class="status' . $user->id . '">' . $user->getStatus() . '</div>';
            })
            ->editColumn('category', function ($faq) {
                return $faq->category()->first()->name;
            })
            ->addColumn('action', function ($faq) {

                if ($faq->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $faq->id . '" data-href="' . route('admin.faqs.change-status', $faq->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.faqs.edit', $faq->id) . '">' . trans('global.btn.edit') . '</a>';

                if (config('base.faqs.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.faqs.destroy', $faq->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = FaqCategory::all()->pluck('name', 'id');
        return view('admin.faqs.edit', ['languages' => $this->langs, 'categories' => $categories]);
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

        $faq = new Faq;
        $faq->category = $input['category'];

        foreach ($this->langs as $locale) {
            $faq->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
            $faq->translateOrNew($locale)->description = $input['translation'][$locale]['description'];
        }

        $faq->save();

        return redirect(route('admin.faqs.edit', $faq->id))->with('success', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faq = Faq::find($id);
        $categories = FaqCategory::all()->pluck('name', 'id');

        $item = $faq->toArray();
        foreach ($this->langs as $language) {
            $item['translation'][$language]['name'] = empty($faq->translate($language)) ? '' : $faq->translate($language)->name;
            $item['translation'][$language]['description'] = empty($faq->translate($language)) ? '' : $faq->translate($language)->description;
        }

        return view('admin.faqs.edit', [
            'item' => $item,
            'languages' => $this->langs,
            'categories' => $categories,
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
        $faq = Faq::findOrFail($id);
//        $faq->update($request->all());

        foreach ($this->langs as $locale) {
            $faq->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
            $faq->translateOrNew($locale)->description = $input['translation'][$locale]['description'];
            $faq->save();
        }


        return redirect(route('admin.faqs.edit', $id))->with('success', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(config('base.faqs.delete') == false){
            die();
        }

        $faq = Faq::find($id);

        if ($faq) {
            if(config('base.faqs.forceDelete') == true){
                $faq->forceDelete();
            } else {
                $faq->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/faqs.faq_not_found')]);
    }
}
