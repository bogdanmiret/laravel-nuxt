<?php

namespace App\Http\Controllers\Admin;

use App\Models\KeywordsResearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class KeywordsResearchController extends Controller
{
    public $langs;

    public function __construct () {
        $this->langs = config('languages');
        $this->middleware('permission:view_keywords_research');
        $this->middleware('permission:delete_keywords_research', ['only' => 'destroy']);
        $this->middleware('permission:edit_keywords_research', ['only' => ['update', 'edit', 'store', 'create']]);
    }

    public function index (Request $request) {

        if (isset($request->type)) {

            if (KeywordsResearch::where('type', $request->type)->first() === null) {
                return redirect(route('admin.keywords.index'))->with(['status' => 'warning', 'message' => trans('admin/alerts.messages.not_found', ['name' => 'Type'])]);
            }

            $type = $request->type;

        } else {
            $type = 'all';
        }


        return view('admin.keywords-research.index')->with(['type' => $type]);
    }

    public function getDtKeywords (Request $request) {

        if (!$request->ajax()) {
            abort(404);
        }

        $type = $request->type;


        $keywords = KeywordsResearch::query()->select(DB::raw('keywords_research_trans.keyword as keyword1'), 'keywords_research.*')
            ->leftJoin('keywords_research_trans', 'keywords_research.id',  '=', 'keywords_research_trans.keywords_research_id')
            ->groupBy('keywords_research.id');


        $type !== 'all' ? $keywords->where('type', $type) : '';

        return DataTables::eloquent($keywords)
            ->addColumn('action', function ($keywords) {
                $action = '<div class="btn-group btn-group-justified">';
                $action .= '<a class="' . config('base.btn.edit') . '" href="'.route('admin.keywords.edit', $keywords->id).'">' . trans('global.btn.edit') . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" onclick="deleteElement(this)" data-href="'.route('admin.keywords.destroy', $keywords->id).'">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['slug', 'type', 'action'])
            ->make(true);

    }

    public function edit (Request $request, $id) {

        $keyword = KeywordsResearch::findOrFail($id);

        foreach($this->langs as $language) {
            $item['translation'][$language]['keyword'] = empty($keyword->translate($language)) ? '' : $keyword->translate($language)->keyword;
        }

        $keyword->translation = $item['translation'];

        return view('admin.keywords-research.edit')->with(['keyword' => $keyword, 'languages' => $this->langs]);

    }

    public function update (Request $request, $id) {

        $keyword = KeywordsResearch::findOrFail($id);

        $this->validate($request, [
            'translation.' . defaultLocale() . '.keyword' => 'required'
        ]);

        foreach ($this->langs as $locale) {
            if (!empty($request['translation'][$locale]['keyword'])) {
                $keyword->translateOrNew($locale)->keyword = $request['translation'][$locale]['keyword'];
            }
        }

        if($keyword->save()) {
            $alert_message = trans('admin/alerts.messages.updated', ['name' => 'Keyword']);
            $alert_status = 'success';
        }

        return redirect(route('admin.keywords.index', ['type' => $keyword->type]))->with(['status' => $alert_status, 'message' => $alert_message]);

    }

    public function create () {

        $types = [
            'google' => 'Google Imports',
            'river' => 'River Imports'
        ];

        return view('admin.keywords-research.edit')->with(['languages' => $this->langs, 'types' => $types]);

    }

    public function store (Request $request) {

        $this->validate($request, [
            'translation.' . defaultLocale() . '.keyword' => 'required',
            'slug' => 'required'
        ]);

        $slug = str_slug($request->slug);
        $type = str_slug($request->type);

        $checkKeyword = KeywordsResearch::where('slug', $slug)->where('type', $type)->first();

        if ($checkKeyword !== null) {
            $alert_message = trans('admin/alerts.messages.exists', ['name' => 'Keyword']);
            return back()->with(['status' => 'warning', 'message' => $alert_message]);
        }


        $keyword = new KeywordsResearch();
        $keyword->slug = $slug;
        $keyword->type = $type;

        foreach ($this->langs as $locale) {
            if (!empty($request['translation'][$locale]['keyword'])) {
                $keyword->translateOrNew($locale)->keyword = $request['translation'][$locale]['keyword'];
            }

        }

        if($keyword->save()) {
            $alert_message = trans('admin/alerts.messages.created', ['name' => 'Keyword']);
            $alert_status = 'success';
        }

        return redirect(route('admin.keywords.edit', $keyword->id))->with(['status' => $alert_status, 'message' => $alert_message]);

    }


    public function destroy ($id) {

        KeywordsResearch::destroy($id);

        return response()->json(['status' => 'ok']);


    }
}
