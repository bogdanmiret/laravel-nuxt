<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Datatables;

class LanguagesController extends Controller
{
    public function __construct()
    {
        $this->location = config('base.language_location');
        $this->current_languages = config('languages');

        $this->middleware('permission:admin_view_languages', ['except' => ['destroy', 'language']]);
        $this->middleware('permission:admin_delete_languages', ['only' => 'destroy']);
    }

    public function index()
    {
        return view('admin.languages.index');
    }

    public function getDT(Request $request)
    {

        if (!$request->ajax()) {
            abort('404');
        }

        $directories = \File::directories($this->location);
        $languages = $this->current_languages;
        $dirs = [];
        foreach ($directories as $directory) {
            $directory = str_replace($this->location, '', $directory);
            $directory = ltrim(str_slug($directory), '-');
            array_push($dirs, $directory);
        }
        $diffs = array_diff($this->current_languages, $dirs);
        foreach ($diffs as $diff) {
            unset($languages[$diff]);
        }

        $languages = collect($languages);
        return Datatables::collection($languages)
            ->addColumn('name', function ($language) {
                return $language;
            })
            ->addColumn('action', function ($language) {

                $action = '<div class="btn-group btn-group-justified">
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.translations.index', ['lang' => $language]) . '">' . trans('global.btn.view_translations') . '</a>';

                if (config('base.Translation.forceDelete') == true && $language != defaultLocale()) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.languages.destroy', $language) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function create()
    {
        return view('admin.languages.create');

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $folder = $request->name;
        if (!\File::isDirectory($this->location . $folder)) {
            \File::copyDirectory($this->location . defaultLocale(), $this->location . $folder);
            $this->updateLangFile($folder, 'add');

        }
        return redirect(route('admin.languages.index'))->with(['status' => 'success', 'message' => trans('admin/languages.')]);

    }

    public function destroy($lang)
    {
        if (config('base.Language.forceDelete') == false) {
            die();
        }

        if (\File::isDirectory($this->location . $lang)) {
            \File::deleteDirectory($this->location . $lang);
            $this->updateLangFile($lang, 'remove');
        }
        return response()->json(['status' => 'ok']);
    }

    public function updateLangFile($field, $type = 'add')
    {
        $languages_array = getAvLangs();
        if ($type == 'add') {
            array_push($languages_array, $field);
        } elseif ($type == 'remove') {
            unset($languages_array[$field]);
        }

        $form = "<?php \n\n";
        $form .= "return [ \n";

        foreach ($languages_array as $key => $val) {
            $form .= "\t";
            $form .= '"' . strip_tags($val) . '" => "' . strip_tags($val) . '", ' . " \n ";
        }
        $form .= '];';

        $filename = base_path() . '/config/languages.php';
        \File::put($filename, $form);
        if(!\Config::get('app.debug')){
            $create_cache = Artisan::call('config:cache');
        }
    }
	
	
	
	public function language(Request $request)
	{
		session()->put('localeAdmin', $request->lang);
		
		return redirect()->back();
	}
	
}
