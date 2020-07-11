<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class CompanyTableController extends Controller
{
    public function __construct()
    {

    }


    public function __invoke(Request $request)
    {

        if (!$request->ajax()) {
            abort('404');
        }

        $query = DB::table('companies')
            ->leftJoin('cities', 'companies.city_id', '=', 'cities.id');

//        if ($request->get('only_from_google') == 'true') {
//            $query->join('media', function ($join) {
//                $join->on('companies.id', '=', 'media.model_id');
//                $join->on('media.model_type', '=', DB::raw("'App\\\Models\\\Company'"));
//                $join->on('media.collection_name', '=', DB::raw("'company_image'"));
//
//                $join->on('media.custom_properties', 'LIKE', DB::raw("'{\"source\":\"Google Scrap\"%'"));
//            });
//        }
//        else {
            $query->leftjoin('media', function ($join) {
                $join->on('companies.id', '=', 'media.model_id');
                $join->on('media.model_type', '=', DB::raw("'App\\\Models\\\Company'"));
                $join->on('media.collection_name', '=', DB::raw("'company_image'"));
//                $join->on('media.custom_properties', 'LIKE', DB::raw("'{\"source\":\"Google Scrap\"%'"));
            });
//        }

        $query->select(
            'companies.id as company_id',
            'companies.new_slug as company_slug',
            'companies.name as company_name',
            'companies.created_at',
            'companies.city_id',
            'companies.dishes_count',
            'companies.active',
            'cities.id',
            'cities.name as city_name',
            'cities.slug as city_slug',
            'cities.slug',
            'media.id as image_id'
        );

        return Datatables::queryBuilder($query
        )
            ->addColumn('image', function ($company) {

                if (!$company->image_id) {
                    return '';
                }

                return "<div class='img img-responsive' >
                    <img style='max-width: 150px' src='" . asset("/storage/media/company_images/$company->image_id/conversions/thumbnail.jpg") . "'>
                    </div>";
            })
            ->editColumn('active', function ($restaurant) {
                if ($restaurant->active == 1) {
                    return "<label class='label label-success'>" . trans('admin/labels.general.yes') . "</label>";
                }

                return "<label class='label label-danger'>" . trans('admin/labels.general.no') . "</label>";
            })
            ->addColumn('actions', function ($restaurant) {
                return self::getControls($restaurant)
                    . self::getSwitch($restaurant);
            })
            ->filterColumn('companies.name', function ($query, $keyword) {
                $query->whereRaw("companies.name like ?", ["$keyword%"]);
            })
//            ->filterColumn('companies.name', 'whereRaw', "LOWER(companies.name) like ? ", ["$1%"])
            ->setTotalRecords(250)
            ->rawColumns(['active', 'actions', 'image'])
            ->make(true);
    }

    public function getSwitch($restaurant)
    {

        switch ($restaurant->active) {
            case 0:
                return ' <a href="' . route('admin.restaurant.mark', [
                        $restaurant->company_id,
                        1,
                    ]) . '" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="' . trans('admin/labels.restaurant.activate') . '"></i></a> ';

            case 1:
                return ' <a href="' . route('admin.restaurant.mark', [
                        $restaurant->company_id,
                        0,
                    ]) . '" class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('admin/labels.restaurant.disable') . '"></i></a> ';

            default:
                return '';
        }
    }

    public function getControls($restaurant)
    {

        $image_control = '';

        if ($restaurant->image_id) {
            $image_control = '<a href="javascript:void(0)" onclick="deleteCompanyImage(' . $restaurant->company_id . ',' . $restaurant->image_id . ')" class="btn btn-xs btn-danger">Delete IMG</a>';
        }

        $details = ' <a href="' . route('admin.restaurants.show',
                $restaurant->company_id) . '" class="btn btn-xs btn-info"><i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="' . trans('admin/labels.general.details') . '"></i></a> ';

        $frontend_url = ' <a href="' . route('trans.show.restaurant',
                [$restaurant->city_slug, $restaurant->company_slug,]) . '" class="btn btn-xs btn-success">
            <i class="fa fa-external-link" data-toggle="tooltip" data-placement="top" title="Frontend URL"></i>
                        </a>';

        return $image_control . $details . $frontend_url;
    }
}
