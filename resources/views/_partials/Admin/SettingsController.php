<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingTrans;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    private $langs = [];
    private $all_langs = [];

    public function __construct()
    {
        $this->all_langs = config('languages');
        $this->langs = config('languages');
        $this->mainModel = Setting::class;
        $this->middleware('permission:admin_view_settings');
    }

    public function index(Request $request)
    {
        if(!$request->slug) {
            return redirect(route('admin.settings.index', ['slug' => 'main']));
        }
        $settings = Setting::where('category', $request->slug)->get()->sortBy('multi_language')->groupBy('multi_language');

        if(!$settings) {
            return abort(404);
        }

        return view('admin.settings.index', ['all_settings' => $settings, 'languages' => $this->langs, 'slug' => $request->slug]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'slug' => 'required',
        ]);

        $translatedReq = $request->all()['translation'];
        $tr_array = false;
        $settings_slugs = [];
        foreach($translatedReq as $tr_key => $tr) {
            foreach($tr as $t_key => $t) {
                $tr_array['translation.' . $tr_key . '.' . $t_key] = 'required';
                $settings_slugs[] = $t_key;
            }
        }

        foreach($request->files as $tra_key => $tra) {
            foreach($tra as $tr_key => $tr) {
                foreach($tr as $t_key => $t) {
                    $tr_array1[$tra_key . '.' . $tr_key . '.' . $t_key] = 'required';
                    unset($tr_array[$tra_key . '.' . $tr_key . '.' . $t_key]);
                    $settings_slugs[] = $t_key;
                }
            }
        }

        // remove non-required fileds
        $allSL = SettingTrans::groupBy('locale')->get()->pluck('locale');
        $allSL = array_unique(array_merge($allSL->toArray(), $this->langs));

        $all_settings = Setting::where('category', $request->slug)->where('required', '!=', 1)->get()->pluck('key');

        foreach($allSL as $aLS) {

            foreach($all_settings as $asKey => $allS) {
                $tr_array1['translation.' . $aLS . '.' . $allS] = 'required';
                unset($tr_array['translation.' . $aLS . '.' . $allS]);
            }
        }
        $this->validate($request, $tr_array);
        $input = $request->all();

        $check_settings = Setting::whereIn('key', $settings_slugs)->get();
        foreach($check_settings as $key => $check) {
            foreach($this->all_langs as $locale) {
                if($check->multi_language) {
                    $settings = $input['translation'][$locale];
                } else {
                    $settings = $input['translation'][defaultLocale()];
                }

                if($check) {
                    if($check->type == 'file') {
                        $uploadPhoto = $this->uploadPhoto($request, ['photo_id' => $check->value, 'files' => ["translation.$locale.$check->key"]]);
                        if(!empty($uploadPhoto)) {
                            $check->translateOrNew($locale)->value = $uploadPhoto['logo'];
                        }
                        $check->save();
                    } else {
                        $check->translateOrNew($locale)->value = $settings[$check->key];
                        $check->save();
                    }
                }
            }
        }


        Cache::flush();

        //remove settings cache
//		clearRedisCache('clear:settings');

//		try {
//			clearRedisCache('clear:settings');
//		}catch(Exception  $e) {
//			Cache::flush();
//		}
//


        return redirect()->back();
    }
}
