<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TranslationsController extends Controller
{
    private $special_char = '♣';

    public function __construct()
    {
        $this->middleware('permission:admin_view_translations',
            ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_translations',
            ['only' => 'destroy']);
    }

    public function markTranslation(Request $request)
    {
        $folder = explode('\\', $request->location);

        $file = '';
        foreach ($folder as $key => $f) {
            if ($key == 0) {
                continue;
            }

            if ($key == 1) {
                $file = $f;
            } else {
                $file .= '\\'.$f;
            }
        }

        if (strpos($request->name, ' > ') !== false) {
            $keys = explode(' > ', $request->name);
        } else {
            $keys = [$request->name];
        }

        $name = '';
        foreach ($keys as $i => $k) {
            $k = trim(str_replace("\xc2\xa0", '', $k));
            if ($i == 0) {
                $name = $k;
            } else {
                $name .= '.'.$k;
            }
        }

        if ($request->mark) {
            $value = $request->value.$this->special_char;
        } else {
            $value = $request->value;
        }

        $req = new Request([
            'folder' => $folder[0],
            'file'   => $file,
            'name'   => $name,
            'value'  => $value,
        ]);

        return $this->DTUpdateFiled($req);
    }

    public function getEnVersion(Request $request)
    {
        $location = explode('\\', $request->location);
        unset($location[0]);
        $location = join('\\', $location);

        $filename = base_path().'/resources/lang/en/'.$location;
        $filename = str_replace('\\', '/', $filename);

        $values = \File::getRequire($filename);

        if (strpos($request->name, ' > ') !== false) {
            $keys = explode(' > ', $request->name);
        } else {
            $keys = [$request->name];
        }

        array_walk($keys, function (&$value) {
            $value = trim(str_replace("\xc2\xa0", '', $value));
        });

        $value = $this->getUsingKeys($values, $keys);
        $value = str_replace($this->special_char, '', $value);

        return response()->json($value);
    }

    private function getUsingKeys($arr, $keys)
    {
        $a = &$arr;

        while (count($keys) > 0) {
            $k = array_shift($keys);

            if ( ! is_array($a)) {
                $a = array();
            }

            $a = &$a[$k];
        }

        return $a;
    }

    public function index($lang = false)
    {
        if ( ! $lang) {
            $lang = defaultLocale();
        }
        if ( ! \File::isDirectory(config('base.language_location').$lang)
            || ! in_array($lang, getAvLangs())
        ) {
            return redirect(route('admin.translations.index'))->with([
                'status'  => 'warning',
                'message' => trans('admin/languages.language_not_found'),
            ]);
        }
        $languages = getAvLangs();

        return view('admin.translations.index',
            ['lang' => $lang, 'languages' => $languages]);
    }

    public function show(Request $request, $type = null)
    {

        if ( ! is_null($request->folder)) {
            $folder = $request->folder;
        } else {
            $folder = defaultLocale();
        }
        $location = str_replace('\\', '/', config('base.language_location'));

        if ( ! \File::isDirectory($location.$folder)
            || ! in_array(explode('/', $folder)[0], getAvLangs())
        ) {
            return redirect(route('admin.translations.files'))->with([
                'status'  => 'warning',
                'message' => trans('admin/languages.language_not_found'),
            ]);
        }

        $files = scandir($location.$folder);
        $first_file = false;
        foreach ($files as $key => $value) {
            if ($value == '.' || $value == '..' || $value == 'info.json') {
                unset($files[$key]);
            }
            if ( ! $first_file
                && ! \File::isDirectory($location.$folder.'/'.$value)
            ) {
                $first_file = $value;
            }
        }

        $file = (! is_null($request->file) ? $request->file : $first_file);
        $str = \File::getRequire($location.$folder.'/'.$file);
        $back_link = false;
        $folders = explode('/', $folder);
        $count_folders = count($folders);
        if ($count_folders > 1) {
            unset($folders[$count_folders - 1]);
            $back_link = $folders[0];
        }
        if ($count_folders - 1 > 2) {
            $back_link = implode('/', $folders);
        }

        $this->data = [
            'stringLang' => $str,
            'lang'       => $folder,
            'files'      => $files,
            'file'       => $file,
            'back_link'  => $back_link,
        ];

        return view('admin.translations.edit', $this->data);
    }

    public function getDTTranslations(Request $request, $folder = false)
    {
        if ( ! $request->ajax()) {
            abort('404');
        }

        $location = config('base.language_location');
        if ( ! in_array($folder, getAvLangs())) {
            die();
        }

        $files = \File::allFiles($location.$folder);

        $trans = [];
        foreach ($files as $key => $value) {
            $trans[$value->getRelativePathName().'->']
                = \File::getRequire($value->getPathname());
        }
        $trans = array_dot($trans);

        $dbTrans = Translation::where('locale', $folder)->orWhere('locale', '')
            ->get();
        $comments = [];

        foreach ($dbTrans as $key => $value) {
            if (isset($comments[$value->key])) {
                $comments[$value->key] .= ' '.$value->value;
            } else {
                $comments[$value->key] = $value->value;
            }
        }

        $translations = [];
        $comment = false;
        foreach ($trans as $key => $value) {
            $db_key = str_replace('php->.', '', $key);

            if (isset($comments[$db_key])) {
                $comment = $comments[$db_key];
            } else {
                $comment = false;
            }

            $file = explode('->', $key);
            $old_key = $key;
            $key = str_replace($file[0].'->.', '', $key);
            $translations[$old_key] = [
                'location' => $file[0],
                'name'     => $key,
                'value'    => str_replace($this->special_char, '',
                    htmlspecialchars($value)),
                'folder'   => $folder,
                'comment'  => $comment,
                'marked'   => strpos(htmlspecialchars($value),
                        $this->special_char) !== false,
            ];
        }

        $translations = collect($translations)->sortByDesc('marked');

        return Datatables::collection($translations)
            ->addColumn('name', function ($trans) {
                return str_replace('.', '&nbsp; >  &nbsp;', $trans['name']);

            })->addColumn('location', function ($trans) {
                return $trans['folder'].'\\'.$trans['location'];

            })->addColumn('value', function ($trans) {
                $input = "<div style='display:none' class='original_value'>"
                    .$trans['value']."</div><input type='text' value=\""
                    .$trans['value']."\" data-file='".$trans['location']
                    ."' data-name='".$trans['name']
                    ."' class='form-control save-translation' style='width:90%'><i class='"
                    .str_replace('.', '-', $trans['name'])
                    ." translation-notification'></i><div class='translation-description'>"
                    .$trans['comment']."</div>";

                return $input;
            })
            ->rawColumns(['value'])
            ->make(true);
    }


    public function DTUpdateFiled(Request $request)
    {
        $folder = $request->folder;
        $file = $request->file;
        $name = $request->name;
        $value = $request->value;

        $filename = base_path().'/resources/lang/'.$folder.'/'.$file;
        $filename = str_replace('\\', '/', $filename);

        $existing_vals = \File::getRequire($filename);
        $name_array = [];
        $name_array = explode('.', $name);

        $count_name = count($name_array);

        $db_key = str_replace('.php', '.', $file).$name;
        $db_translation = Translation::where([
            'locale' => $folder,
            'key'    => $db_key,
        ])->first();

        if ($db_translation) {
            $db_translation->delete();
        }

        if ($count_name == 1) {
            $existing_vals[$name_array[0]] = $value;
        } elseif ($count_name == 2) {
            $existing_vals[$name_array[0]][$name_array[1]] = $value;
        } elseif ($count_name == 3) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]]
                = $value;
        } elseif ($count_name == 4) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]]
                = $value;
        } elseif ($count_name == 5) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]]
                = $value;
        } elseif ($count_name == 6) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]][$name_array[5]]
                = $value;
        } elseif ($count_name == 7) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]][$name_array[5]][$name_array[6]]
                = $value;
        } elseif ($count_name == 8) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]][$name_array[5]][$name_array[6]][$name_array[7]]
                = $value;
        } elseif ($count_name == 9) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]][$name_array[5]][$name_array[6]][$name_array[7]][$name_array[8]]
                = $value;
        } elseif ($count_name == 10) {
            $existing_vals[$name_array[0]][$name_array[1]][$name_array[2]][$name_array[3]][$name_array[4]][$name_array[5]][$name_array[6]][$name_array[7]][$name_array[8]][$name_array[9]]
                = $value;
        }

        $this->saveInFile($filename, $existing_vals);

        return response()->json([
            'status'  => 'success',
            'message' => trans('admin/languages.translation_updated_successfully'),
        ]);
    }

    public function update(Request $request)
    {
        $template = base_path();
        $lang = $request->input('lang');
        $file = $request->input('file');
        $filename = $template.'/resources/lang/'.$lang.'/'.$file;
        $filename = str_replace('\\', '/', $filename);
        $trans = $request->trans;
        $this->saveInFile($filename, $trans);

        return redirect(route('admin.translations.files').'?folder='.$lang
            .'&file='.$file)->with([
            'status'  => 'success',
            'message' => trans('admin/languages.translations_updated_successfully'),
        ]);
    }

    public function syncTranslations()
    {
        $location = config('base.language_location');
        $folder_source = defaultLocale();
        $files_source = \File::allFiles($location.$folder_source);

        $trans_source = [];
        foreach ($files_source as $key_source => $value_source) {
            $trans_source[$value_source->getRelativePathName().'->']
                = \File::getRequire($value_source->getPathname());
        }
        $trans_source = array_dot($trans_source);

        $available_languages = getAvLangs();
        unset($available_languages[$folder_source]);
        foreach ($available_languages as $folder_destination) {
            $translations = false;
            $files_destination = \File::allFiles($location.$folder_destination);

            $trans_destination = [];
            foreach (
                $files_destination as $key_destination => $value_destination
            ) {
                $trans_destination[$value_destination->getRelativePathName()
                .'->']
                    = \File::getRequire($value_destination->getPathname());
            }
            $trans_destination = array_dot($trans_destination);
            $differences = array_diff_key($trans_source, $trans_destination);
            foreach ($differences as $difference_key => $difference_value) {
                $file = explode('->', $difference_key);
                $difference_key = str_replace($file[0].'->.', '',
                    $difference_key);
                $translations[$file[0]][$difference_key] = $difference_value;
            }

            if ($translations) {
                foreach (
                    $translations as $translation_key => $translation_value
                ) {
                    $file = $translation_key;
                    $filename = base_path().'/resources/lang/'
                        .$folder_destination.'/'.$file;
                    $file_explode = explode('\\', $file);
                    $folders = substr($file, 0, strrpos($file, '\\'));
                    $folder_location = $location.$folder_destination.'/'
                        .$folders;

                    if (count($file_explode) > 1
                        && ! \File::exists($folder_location)
                    ) {
                        \File::makeDirectory($folder_location);
                    }

                    if (\File::exists($filename)) {
                        $existing_vals
                            = array_dot(\File::getRequire($filename));
                    } else {
                        $existing_vals = [];
                    }

                    foreach ($translation_value as $key => $value) {
                        $db_location = str_replace('.php', '.',
                            $translation_key);
                        $translation_db = new Translation();
                        $translation_db->locale = $folder_destination;
                        $translation_db->key = $db_location.$key;
                        $translation_db->value
                            = trans('admin/languages.translation_was_synchronized');
                        $translation_db->save();
                    }

                    $allTranslations
                        = array_combine(array_merge(array_keys($existing_vals),
                        array_keys($translation_value)
                    ),
                        array_merge(array_values($existing_vals),
                            array_values($translation_value)
                        )
                    );

                    $existing_vals = array();
                    foreach ($allTranslations as $key => $value) {
                        array_set($existing_vals, $key, $value);
                    }

                    $this->saveInFile($filename, $existing_vals);
                }
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => trans('admin/languages.sync_translations_successfully'),
        ]);
    }


    public function saveInFile($filename, $existing_vals)
    {
        $form = "<?php \n\n";
        $form .= "return [ \n";
        $level = 1;
        foreach ($existing_vals as $key => $val) {
            if ($key != '_token' && $key != 'lang' && $key != 'file') {
                $form = $this->recusiveSaveInFile($val, $key, $form, $level);
            }
        }
        $form .= '];';
        \File::put($filename, $form);

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }

    public function recusiveSaveInFile($val, $key, $form, $level)
    {
        $_space = "\t";
        $space = null;
        for ($i = 1; $i <= $level; $i++) {
            $space .= $_space;
        }

        $form .= $space;
        if ( ! is_array($val)) {
            $form .= '"'.$key.'" => "'.addcslashes(strip_tags($val, '<p><a>'),
                    '"').'", '." \n ";

        } else {
            $form .= '"'.$key.'" => [ '." \n ";
            $level += 1;
            foreach ($val as $k => $v) {
                $form = $this->recusiveSaveInFile($v, $k, $form, $level);
            }
            $form .= $space."], \n";
        }

        return $form;
    }
}
