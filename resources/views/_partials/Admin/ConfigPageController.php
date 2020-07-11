<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ConfigPageController extends Controller
{


    /**
     * @var string
     */
    private $envPath;


    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->middleware('permission:admin_update_env', ['only' => 'saveEnv']);
    }


    public function index(){
        if(config('base.config_page.edit_env') && \Auth::user()->can('admin_update_env')) {
            $env = $this->getEnvContent();
            return view("admin.config.index")->with(['envConfig' => $env]);
        }

        return view("admin.config.index");
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (!file_exists($this->envPath)) {
            return false;
        }
        $lines = file($this->envPath);
        foreach ($lines as $line) {
            $line = trim(str_replace('"', '', $line));
            $components = explode('=', $line);
            if ($components[0] != '') {
                $key = $components[0];
                $val = isset($components[1]) ? $components[1] : '';
                $result[$key] = $val;
            }
        }
        return $result;
    }


    /**
     * Processes the newly saved environment configuration and redirects back.
     *
     * @param Request $input
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveEnv(Request $input)
    {

        $message = $this->saveFile($input);
        return Redirect(route('admin.config-page'))
            ->with(['message' => $message]);
    }

    /**
     * Save the edited content to the file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFile(Request $input)
    {
        $inputs = $input->all();
        $output ='';

        foreach ($inputs as $key => $input) {
            if($key == "_token")
                continue;
            if (strpos($input, ' ') !== false)
                $output .= "$key=\"$input\"\r\n";
            else
                $output .= "$key=$input\r\n";
        }
        $message = trans('messages.environment.success');

        try {
            file_put_contents($this->envPath, $output);
        } catch (Exception $e) {
            $message = trans('messages.environment.errors');
        }

        if(config('app.env') == 'local') {
            Artisan::call('config:clear');
        } else {
            Artisan::call('config:cache');
        }
    }

}
