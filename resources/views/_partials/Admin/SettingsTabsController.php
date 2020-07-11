<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingTrans;
use Intervention\Image\Facades\Image;
class SettingsTabsController extends Controller
{
    public function __construct()
    {
        $this->multi_language = false;
        $this->all_langs = config('languages');
        $this->langs = $this->multi_language ? config('languages') : [defaultLocale() => defaultLocale()];
        $this->mainModel = Setting::class;
        $this->middleware('permission:admin_view_settings');
    }

    public function index(Request $request)
    {

        $settings = Setting::first();
        if(!$settings){
            return abort(404);
        }
        return view('admin.settings.index', ['settings' => $settings, 'languages' => $this->langs]);
    }

    public function update(Request $request, $id)
    {
        $translatedReq = $request->all()['translation'];
        $tr_array = false;


        foreach ($translatedReq as $tr_key => $tr) {
            foreach ($tr as $t_key => $t) {
                $tr_array['translation.' . $tr_key . '.' . $t_key] = 'required';
            }
        }

        foreach ($request->files as $tra_key => $tra) {
            foreach ($tra as $tr_key => $tr) {
                foreach ($tr as $t_key => $t) {
                    $tr_array1[$tra_key . '.' . $tr_key . '.' . $t_key] = 'required';
                    unset($tr_array[$tra_key . '.' . $tr_key . '.' . $t_key]);
                }
            }
        }

        // remove non-required fileds
        $allSL = SettingTrans::groupBy('locale')->get()->pluck('locale');
        $allSL = array_unique(array_merge($allSL->toArray(), $this->langs));

        $allsettings = Setting::where('required', '!=', 1)->get()->pluck('key');

        foreach($allSL as $aLS){

            foreach($allsettings as $asKey => $allS){
                $tr_array1['translation.' . $aLS. '.' . $allS] = 'required';
                unset($tr_array['translation.' . $aLS. '.' . $allS] );
            }
        }

        $this->validate($request, $tr_array);
        $input = $request->all();

        foreach ($this->all_langs as $locale) {

            if($this->multi_language){
                $settings = $input['translation'][$locale];
            } else {
                $settings = $input['translation'][defaultLocale()];
            }

            foreach ($settings as $key => $setting) {

                if ($key == "_method" || $key == "_token") {
                    continue;
                }
                $check = Setting::where('key', $key)->first();

                if ($check) {
                    if ($check->type == 'file') {
                        $uploadPhoto = $this->uploadPhoto($request, ['photo_id' => $check->value, 'files' => ["translation.$locale.$key"]]);
                        if (!empty($uploadPhoto)) {
                            $check->translateOrNew($locale)->value = $uploadPhoto['logo'];
                        }
                        $check->save();
                    } else {
                        $check->translateOrNew($locale)->value = $settings[$key];
                        $check->save();
                    }
                }
            }
        }

        //remove settings cache
        clearRedisCache('clear:settings');

        return redirect(route('admin.settings.index'));
    }
}
