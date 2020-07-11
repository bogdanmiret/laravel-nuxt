<?php

namespace App\Http\Controllers\Admin;

use App\Models\JsonList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Datatables;

class JsonListsController extends Controller
{
    public $languages;

    /**
     * JsonListsController constructor.
     */
    public function __construct()
    {
        $this->languages = config('languages');

        $this->middleware('permission:admin_edit_json_lists');
    }

    /**
     * @return $this
     */
    public function index()
    {

        $lists = JsonList::all();

        return view('admin.json_lists.index')->with(['json_lists' => $lists]);
    }

    /**
     * @return Post data for JsonLists datatable
     */
    public function getJsonLists()
    {
        $lists = JsonList::all();


        return Datatables::of($lists)
            ->addColumn('action', function ($list) {
                $action = '<div class=" ">
                <a class="btn btn-warning" href="' . route('admin.json_lists.edit',
                        $list->id) . '">' . trans('global.btn.edit') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->make(true);

    }


    /**
     * Create new list
     */
    public function create()
    {
        return view('admin.json_lists.edit', ['languages' => $this->languages]);
    }

    public function edit($id)
    {
        $list = JsonList::find($id);
        $structure = trim(preg_replace('/\s\s+/', ' ', $list->structure));

        $structure = array_keys(collect(json_decode($structure))->toArray());

        $translates = [];


        foreach ($this->languages as $language) {
            if (isset($list->translate($language)->json_value) && $list->translate($language)->json_value != null) {
                $translates[$language] = json_decode($list->translate($language)->json_value);
            } else {
                $translates[$language][] = [];
            }
        }

        return view('admin.json_lists.edit', [
            'languages' => $this->languages,
            'list' => $list,
            'translates' => json_encode($translates),
            'structure' => $structure
        ]);
    }

    public function store(Request $request)
    {

        $list = Jsonlist::find($request->list_id);

        $iso_codes = collect(json_decode(JsonList::where('slug',
            'envs')->first()->translate('de')->json_value))->pluck('country_iso');

        foreach ($iso_codes as $iso_code) {
            clearRedisCache(["laravel:{$iso_code}"]);
        }

        $language_array = $this->languages;

        $translates = $request->element;
        if (isset($translates)) {
            foreach ($translates as $key => $translate) {
                unset($language_array[$key]);

                $list->translateOrNew($key)->json_value = json_encode($translate);
            }
        }

        foreach ($language_array as $lang) {
            $list->translateOrNew($lang)->json_value = null;
        }


        $list->save();

        return back()->with([
            'status' => 'success',
            'message' => 'Big success'
        ]);


    }
}
