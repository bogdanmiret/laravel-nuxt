<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Http\Request;

use Facebook\Facebook;
use Codebird\Codebird;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SocialController extends Controller
{

    public function getFbPermissions()
    {
        return redirect("https://www.facebook.com/dialog/oauth?client_id=" . setting('facebook-app-id') . "&redirect_uri=" . route('admin.social.facebook.token') . "&scope=publish_actions,manage_pages,publish_pages");
    }

    public function getFbToken(Request $request)
    {
        $code = $request->input('code');
        $access_token = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=" . setting('facebook-app-id') . "&redirect_uri=" . route('admin.social.facebook.token') . "&client_secret=" . setting('facebook-app-secret') . "&code=$code");
        $access_token = str_replace('access_token=', '', $access_token);
        $access_token = explode('&', $access_token);
        $access_token = $access_token[0];
        $config = array();
        $config['appId'] = setting('facebook-app-id');
        $config['secret'] = setting('facebook-app-secret');
        $config['fileUpload'] = false; // optional
        $fb = new Facebook($config);
        try {
            $ret = $fb->get('/' . setting('facebook-page-id') . '?fields=access_token', $access_token);
            $response = json_decode($ret->getBody());
            settingchange('facebook-app-token', $response->access_token);
            return redirect(route('admin.social.create'))->with(['success' => 'success']);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
    }

    public function postToFacebook(Request $request)
    {
        $input = $request->all();
        if($input['message'] != '')
            $params["message"] = $input['message'];
        if($input['link'] != '')
            $params["link"] = $input['link'];
        if($input['picture'] != '')
            $params["picture"] = $input['picture'];
        if($input['name'] != '')
            $params["name"] = $input['name'];
        if($input['caption'] != '')
            $params["caption"] = $input['caption'];
        if($input['description'] != '')
            $params["description"] = $input['description'];

        $config = array();
        $config['appId'] = setting('facebook-app-id');
        $config['secret'] = setting('facebook-app-secret');
        $config['fileUpload'] = false; // optional
        $fb = new Facebook($config);

        try {
            $ret = $fb->post('/' . setting('facebook-page-id') . '/feed', $params, setting('facebook-app-token'));
        } catch (FacebookResponseException $e) {
            return redirect(route('admin.social.create'))->withErrors($e->getMessage())->withInput();
            exit;
        }
//        } catch (Exception $e) {
//            \Log::info($e->getMessage());
//            return redirect(route('admin.social.create'))->withErrors('There was a problem posting to Facebook')->withInput();
//        }
        return redirect(route('admin.social.create'))->with(['success' => 'success']);
    }

    public function postToTwitter(Request $request)
    {
        $input = $request->all();
        $params['status'] = $input['status'];
        Codebird::setConsumerKey(setting('twitter-app-key'), setting('twitter-app-secret'));
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken(setting('twitter-access-token'), setting('twitter-access-token-secret'));
        try {
            $reply = $cb->statuses_update($params);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return redirect(route('admin.social.create'))->withErrors('There was a problem posting to Twitter')->withInput();
        }
        return redirect(route('admin.social.create'))->with(['success' => 'success']);
    }

    public function create(){
        $token = Setting::where('key','facebook-app-token')->first();
        $now = Carbon::now();
        $updated_at = $token->translate('en')->updated_at;
        $tokendays = $now->diffindays($updated_at->addDays(60),false);
        return view('admin.social.create')->with(['tokendays'=>$tokendays]);
    }

}
