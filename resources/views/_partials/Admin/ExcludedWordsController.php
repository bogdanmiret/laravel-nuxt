<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExcludeWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExcludedWordsController extends Controller
{
    public function __construct() {
        $this->langs = config('languages');
        $this->middleware('permission:admin_view_excluded_words');
        $this->middleware('permission:admin_delete_excluded_words', ['only' => 'destroy']);
        $this->middleware('permission:admin_edit_excluded_words', ['only' => ['update', 'edit', 'store', 'create']]);

    }

    public function index() {
        return view('admin.excluded.index');
    }

    public function getDT() {
        $words = ExcludeWord::query()
            ->select(DB::raw('exclude_words_trans.word as word'), 'exclude_words.*')
            ->leftJoin('exclude_words_trans', 'exclude_words.id',  '=', 'exclude_words_trans.exclude_word_id')
            ->groupBy('exclude_words.id');

        return DataTables::eloquent($words)
            ->addColumn('action', function ($words) {
                $action = '<div class="btn-group btn-group-justified">';
                $action .= '<a class="' . config('base.btn.edit') . '" href="'.route('admin.excluded-words.edit', $words->id).'">' . trans('global.btn.edit') . '</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" onclick="deleteElement(this)" data-href="'.route('admin.excluded-words.destroy', $words->id).'">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['slug', 'type', 'action'])
            ->make(true);

    }

    public function edit (Request $request, $id) {

        $word = ExcludeWord::findOrFail($id);

        foreach($this->langs as $language) {
            $item['translation'][$language]['word'] = empty($word->translate($language)) ? '' : $word->translate($language)->word;
        }

        $word->translation = $item['translation'];

        return view('admin.excluded.edit')->with(['word' => $word, 'languages' => $this->langs]);

    }

    public function update (Request $request, $id) {

        $word = ExcludeWord::findOrFail($id);

        $this->validate($request, [
            'translation.' . defaultLocale() . '.word' => 'required'
        ]);

        foreach ($this->langs as $locale) {
            if (!empty($request['translation'][$locale]['word'])) {
                $word->translateOrNew($locale)->word = $request['translation'][$locale]['word'];
            }
        }

        if($word->save()) {
            $alert_message = trans('admin/alerts.messages.updated', ['name' => 'Word']);
            $alert_status = 'success';
        }

        return redirect(route('admin.excluded-words.index', ['type' => $word->type]))->with(['status' => $alert_status, 'message' => $alert_message]);

    }

    public function create () {

        $types = [
            'Feed' => 'Feed imports',
        ];

        return view('admin.excluded.edit')->with(['languages' => $this->langs, 'types' => $types]);

    }

    public function store (Request $request) {

        $this->validate($request, [
            'translation.' . defaultLocale() . '.word' => 'required',
            'slug' => 'required'
        ]);

        $slug = str_slug($request->slug);
        $type = str_slug($request->type);

        $checkWord = ExcludeWord::where('slug', $slug)->first();

        if ($checkWord !== null) {
            $alert_message = trans('admin/alerts.messages.exists', ['name' => 'Word']);
            return back()->with(['status' => 'warning', 'message' => $alert_message]);
        }


        $word = new ExcludeWord();
        $word->slug = $slug;
        $word->type = $type;


        foreach ($this->langs as $locale) {
            if (!empty($request['translation'][$locale]['word'])) {
                $word->translateOrNew($locale)->word = $request['translation'][$locale]['word'];
            }

        }

        if($word->save()) {
            $alert_message = trans('admin/alerts.messages.created', ['name' => 'Word']);
            $alert_status = 'success';
        }

        return redirect(route('admin.excluded-words.edit', $word->id))->with(['status' => $alert_status, 'message' => $alert_message]);

    }

    public function destroy ($id) {

        ExcludeWord::destroy($id);

        return response()->json(['status' => 'ok']);


    }




}
