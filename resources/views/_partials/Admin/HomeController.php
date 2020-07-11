<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogArticle;
use App\Models\Claim;
use App\Models\Company;
use App\Models\ContactForm;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Newdish;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Arcanedev\LogViewer\Tables\StatsTable;
use Arcanedev\LogViewer\Contracts\LogViewer as LogViewerContract;

class HomeController extends Controller
{
    protected $logViewer;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param LogViewerContract $logViewer
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//		$logs = isset($logViewer->statsTable()->footer()['error']) ? $logViewer->statsTable()->footer()['error'] : [];

        $statistics = new \stdClass();
        $statistics->users = Cache::remember('dashboard:user_count', 60, function () {
            return User::count();
        });

        $statistics->contactForms = ContactForm::where('status', 0)->count();
        $statistics->blogArticles = BlogArticle::count();
        // $statistics->logs = Redis::get('cron.statistics:logs');
        $statistics->logs = null;
        // $statistics->total_company_contributions = Redis::get('cron.statistics:total_company_contributions');
        $statistics->total_company_contributions = null;
        // $statistics->unaproved_company_contributions = Redis::get('cron.statistics:unaproved_company_contributions');
        $statistics->unaproved_company_contributions = null;
        // $statistics->total_dish_contributions = Redis::get('cron.statistics:total_dish_contributions');
        $statistics->total_dish_contributions = null;
        // $statistics->unaproved_dish_contributions = Redis::get('cron.statistics:unaproved_dish_contributions');
        $statistics->unaproved_dish_contributions = null;
//		$statistics->aproved_company_contributions = Media::where('collection_name' , 'dish_contributions')->where('custom_properties' , 'like' , '%"approved":1%')->count();
		$statistics->aproved_company_contributions = 0;

        // $statistics->total_feedback = Redis::get('cron.statistics:total_feedback');
        $statistics->total_feedback = null;
        // $statistics->pending_feedback = Redis::get('cron.statistics:pending_feedback');
        $statistics->pending_feedback = null;

        $statistics->total_claims = Claim::count();
        $statistics->pending_claims = Claim::where('status', 1)->count();

        // $statistics->companies_count = Redis::get('cron.statistics:restaurants');
        $statistics->companies_count = 0;
        // $statistics->dishes_count = Redis::get('cron.statistics:dishes');
        $statistics->dishes_count = 0;


        if ($statistics->companies_count == null
            || $statistics->dishes_count == null
            || $statistics->total_feedback == null
            || $statistics->pending_feedback == null
            || $statistics->total_company_contributions == null
            || $statistics->unaproved_company_contributions == null
            || $statistics->total_dish_contributions == null
            || $statistics->unaproved_dish_contributions == null
        ) {
            if (config('env.QUEUE_DRIVER') === 'redis') {
                $redis_queue_has_key = redisQueueHasKey("CalculateStatistics");
                if ($redis_queue_has_key === false) {
                    Artisan::queue('CalculateStatistics');
                }
            } else {
                // Artisan::queue('CalculateStatistics');
            }
        }

        return view('admin.dashboard.index', compact('statistics'));
    }

    public function getLandingListColumns(Request $request)
    {
        if (!$request->input('classdef'))
            return (json_encode(['success' => 'false']));
        $result = [];
        $classname = "App\\Models\\" . $request->input('classdef');
        $tablename = with(new $classname)->getTable();
        $columns = Schema::getColumnListing($tablename);
        foreach ($columns as $column) {
            $result[] = [
                'id'     => $column,
                'column' => $column,
            ];
        }

        return (json_encode($result));
    }


    public function getLandingList(Request $request)
    {
        if (!$request->input('classdef') || !$request->input('columndef') || !$request->input('term'))
            return (json_encode(['success' => 'false']));
        $column = $request->input('columndef');
        $class = $request->input('classdef');
        $term = $request->input('term');
        $results = [];

        $classname = "App\\Models\\" . $class;
        $q = $classname::where($column, 'like', '%' . $term . '%')->groupby($column)->pluck($column, 'id');

        foreach ($q as $id => $result) {
            $results[] = [
                'id'  => $id,
                'val' => $result,
            ];
        }

        return (json_encode($results));
    }

    public function purgeRedisCache()
    {
        clearRedisCache(['laravel']);
        clearRedisCache(['cron']);
        return redirect()->back();
    }
}
