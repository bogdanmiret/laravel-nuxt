<?php

namespace App\Http\Controllers\Admin;

use App\Models\JsonList;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MiscController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:admin_update_env', ['only' => 'regenerateEnv']);
	}


	public function regenerateEnv()
	{
		$env_countries = collect(json_decode(JsonList::where('slug', 'envs')->first()->translate('de')->json_value, true));

		if (!count($env_countries)) {
			abort(404, "No countries found in env JSON list");
		}
		
		//delete all the old env files
		$files = glob(base_path("/envs/{,.}*"), GLOB_BRACE);
		foreach ($files as $file) { // iterate files
			if (is_file($file))
				unlink($file); // delete file
		}


		foreach ($env_countries as $env_country) {
			$formatted_array = [];
			foreach ($env_country as $key => $detail) {
				if (strpos($detail, " ")) {
					array_push($formatted_array, strtoupper($key) . "=" . '"' . $detail . '"');
				} else {
					array_push($formatted_array, strtoupper($key) . "=" . $detail);
				}

			}

			$base_env = file(base_path(".base_env"), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			$merged_array = array_merge($base_env, $formatted_array);




			$holder = "";
			foreach ($merged_array as $single_var) {
				$holder .= $single_var . "\n";
			}

			\File::put(base_path("envs/.env-{$env_country['country_iso']}"), $holder);
		}

		return back()->with(['status' => 'success', 'message' => 'ENVs have regenerated ']);
	}


	public function deleteMediaFromModel($media_id, $class_name, $model_id)
	{
		$class_name::find($model_id)->deleteMedia($media_id);
		
		if(request()->ajax()) {
		    return response()->json('Success', 200);
        }
		
		return back()->with(['status' => 'success', 'message' => "Image {$media_id} deleted successfully"]);
	}

	public function setMainMediaForModel($media_id, $class_name, $model_id)
	{
		$old_main_image = null;
		if($class_name == 'App\\Models\\Newdish') {

			$old_main_image = $class_name::find($model_id)->media()->where('collection_name', 'dish_main')->first();

			$model = $class_name::find($model_id);
			
			$media = Media::findOrFail($media_id);
	
			$model->addMediaFromUrl($media->getPath())->toMediaCollection('dish_main', 'dish_main');

		}

		if($old_main_image) {
			$class_name::find($model_id)->deleteMedia($old_main_image->id);
		}
		$class_name::find($model_id)->deleteMedia($media_id);

		$model->fresh()->save();

		clearRedisCache(['laravel:']);

		return back()->with(['status' => 'success', 'message' => "Image {$media_id} is now main image."]);
	}
}
