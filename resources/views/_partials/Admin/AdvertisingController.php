<?php

namespace App\Http\Controllers\Admin;

use App\Models\Advertising;
use App\Models\AdvertisingBanner;
use App\Models\JsonList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Datatables;

class AdvertisingController extends Controller
{
    public function __construct()
    {
        $this->mainModel = Advertising::class;

        $this->middleware('permission:admin_view_ads', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_ads', ['only' => 'destroy']);
        $countries_json = JsonList::where('slug', 'country_specific')->first()->translateOrDefault('en')->json_value;
        $this->countries = collect(json_decode($countries_json))->pluck('country', 'isocode');
    }


    public function index()
    {
        return view('admin.advertising.index');
    }

    public function edit($id)
    {
        $maximum_ad_banners = 3;
        $ad = Advertising::with('banners')->findOrFail($id);
        $item = [];
        foreach ($this->countries as $isocode => $country) {
            $ad_details = $ad->banners->where('isocode', $isocode);
            foreach ($ad_details as $ad_detail) {
                if ($ad_detail->type == 'ad') {
                    $item['ad'][$isocode] = isset($ad_detail->ad) ? $ad_detail->ad : null;
                } else {
                    $item['banner'][$isocode][$ad_detail->slug]['url'] = isset($ad_detail->url) ? $ad_detail->url : null;
                    $item['banner'][$isocode][$ad_detail->slug]['slug'] = isset($ad_detail->slug) ? $ad_detail->slug : null;
                    $item['banner'][$isocode][$ad_detail->slug]['banner'] = $ad_detail->getMedia('ad_banners')->count() > 0 ? storage('media/ewoiplnx/' . $ad_detail->getMedia('ad_banners')[0]->id . '/' . $ad_detail->getMedia('ad_banners')[0]->file_name) : null;
                }
            }
        }

        $ad->ads = $item;

        return view('admin.advertising.edit', [
            'ad'                 => $ad,
            'countries'          => $this->countries,
            'maximum_ad_banners' => $maximum_ad_banners,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ad = Advertising::findOrFail($id);

        $ad->status = isset($request->status);
        $ad->slug = $request->slug;
        $ad->description = $request->description;
        $ad->name = $request->name;
//        dd($request);
        $ad->copy = $request->copy;

        foreach ($request->ads as $type => $ads_data) {
            foreach ($ads_data as $isocode => $ad_data) {
                if ($type == 'ad') {

                    $advertising_ad = $ad->banners->where('isocode', $isocode)->where('type', 'ad')->first();
                    if (!$advertising_ad) {
                        $advertising_ad = new AdvertisingBanner();
                        $advertising_ad->advertising_id = $ad->id;
                        $advertising_ad->type = 'ad';
                        $advertising_ad->isocode = $isocode;
                    }

                    $advertising_ad->ad = $ad_data;
                    $advertising_ad->slug = str_slug($advertising_ad->ad);
                    if (!empty($ad_data)) {
                        $advertising_ad->save();
                    }
                } else {

                    foreach ($ad_data as $ad_data_banner_key => $ad_data_banner) {
                        if (empty($ad_data_banner['url'])) {
                            continue;
                        }

                        if (strpos($ad_data_banner_key, 'not_found_') === 0) {

                            $advertising_banner = new AdvertisingBanner();
                            $advertising_banner->advertising_id = $ad->id;
                            $advertising_banner->type = 'banner';
                            $advertising_banner->isocode = $isocode;

                        } else {
                            $advertising_banner = $ad->banners->where('isocode', $isocode)->where('type',
                                'banner')->where('slug', $ad_data_banner_key)->first();
                            if (!$advertising_banner) {

                                $advertising_banner = new AdvertisingBanner();
                                $advertising_banner->advertising_id = $ad->id;
                                $advertising_banner->type = 'banner';
                                $advertising_banner->isocode = $isocode;
                            }
                        }

                        $banner_image = $advertising_banner->getMedia('ad_banners');
                        $banner_image_count = $banner_image->count();
                        $banner_validation_rules = $banner_image_count ? 'image|max:1024' : 'required|image|max:1024';

                        $this->validate($request, [
                            'ads.' . $type . '.' . $isocode . '.' . $ad_data_banner_key . '.url'    => 'url',
                            'ads.' . $type . '.' . $isocode . '.' . $ad_data_banner_key . '.banner' => $banner_validation_rules,
                        ]);

                        if ($request->hasFile('ads.banner.' . $isocode . '.' . $ad_data_banner_key . '.banner')) {
                            foreach ($advertising_banner->getMedia('ad_banners') as $media) {
                                $media->delete();
                            }

                            $advertising_banner->addMediaFromRequest('ads.banner.' . $isocode . '.' . $ad_data_banner_key . '.banner')->toMediaCollection('ad_banners',
                                'ad_banners');
                        }

                        $advertising_banner->status = isset($ad_data_banner['status']) ? 1 : 0;
                        $advertising_banner->url = $ad_data_banner['url'];
                        $advertising_banner->slug = generateSlug([
                            'class'   => AdvertisingBanner::class,
                            'element' => str_shuffle(str_slug($advertising_banner->url)),
                        ]);
                        $advertising_banner->save();

                    }
                }
            }
        }

        clearRedisCache(['laravel:global_advertisings']);

        if ($ad->save()) {
            $alert_message = trans('admin/package.alert.u_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.advertising.edit', $id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
    }

    public function getAdvertisings(Request $request)
    {
        if (!$request->ajax()) {
            abort('404');
        }

        $ads = Advertising::query()->orderBy('created_at', 'desc');

        return Datatables::eloquent($ads)
            ->editColumn('status', function ($ad) {
                return '<div class="status' . $ad->id . '">' . $ad->getStatus() . '</div>';
            })
            ->editColumn('description', function ($ad) {
                return str_limit($ad->description, 80);
            })
            ->addColumn('action', function ($ad) {
                if ($ad->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $deleteName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $deleteName = trans("global.btn.activate");
                }

                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $ad->id . '" data-href="' . route('admin.advertising.change-status',
                        $ad->id) . '" onclick="changeElementStatus(this)">' . $deleteName . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.advertising.edit',
                        $ad->id) . '">' . trans('global.btn.edit') . '</a>';

                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function changeStatus($id)
    {
        $class = $this->mainModel;
        $element = $class::findOrFail($id);

        if ($element->status == 1) {
            $element->status = 0;
            $rclass = config('base.btn.deactivate');
            $aclass = config('base.btn.activate');
            $newstatus = trans('global.sts.deactivated');
            $newStatusBtn = trans('global.btn.activate');
        } else {
            $element->status = 1;
            $rclass = config('base.btn.activate');
            $aclass = config('base.btn.deactivate');
            $newstatus = trans('global.sts.activated');
            $newStatusBtn = trans('global.btn.deactivate');
        }
        $element->save();

        clearRedisCache(['laravel:global_advertisings']);

        return response()->json([
            'status'       => 'ok',
            'newstatus'    => $newstatus,
            'newstatusbtn' => $newStatusBtn,
            'aclass'       => $aclass,
            'rclass'       => $rclass,
        ]);
    }
}
