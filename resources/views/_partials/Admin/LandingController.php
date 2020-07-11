<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingsListParameter;
use App\Models\LandingTrans;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Landing;
use App\Http\Controllers\Controller;
use stdClass;
use Datatables;

class LandingController extends Controller
{
    private $langs = [];
    
    public function __construct()
    {
        $this->langs = config('languages');
        
        $this->mainModel = Landing::class;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $landings = Landing::all();
        
        
        return view('admin.landings.index')->with('landings', $landings);
    }
    
    
    public function GetLandings()
    {
        
        $landings = Landing::where('is_child', 0)->get();
        
        return Datatables::of($landings)
            ->editColumn('status', function ($user) {
                return '<div class="status' . $user->id . '">' . $user->getStatus() . '</div>';
            })
            ->addColumn('action', function ($landing) {
                
                if ($landing->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $landing->id . '" data-href="' . route('admin.landings.change-status',
                        $landing->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.landings.edit',
                        $landing->id) . '">' . trans('global.btn.edit') . '</a>';
                
                if (config('base.landings.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.landings.destroy',
                            $landing->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '<a class="btn btn-info" href="javascript:void(0);" data-href="' . route('admin.clone.landing') . '"  data-id="' . $landing->id . '" onclick="cloneElement(this)">' . trans('global.btn.clone') . '</a>';
                
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.landings.edit', ['languages' => $this->langs]);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $landing = new Landing();
        
        $translation = $request->input('translation');
        
        foreach ($this->langs as $locale) {
            $translation[$locale]['slug'] = $translation[$locale]['slug'] != "" ? generateLandingSlug($translation[$locale]['slug']) : generateLandingSlug($translation[$locale]['title']);
        }
        $request->merge(['translation' => $translation]);
        
        
        $this->validate($request, [
            'translation.' . config('env.DEFAULT_LOCALE') . '.title'      => 'required',
            'translation.' . config('env.DEFAULT_LOCALE') . '.slug'       => 'required',
            'translation.' . config('env.DEFAULT_LOCALE') . '.search_url' => 'required',
        ]);
//			dd('here');
        $input = $request->all();
        $intervals = null;
        if (strlen($input['list_1'])) {
            $intervals[$input['list_1']] = $input['interval_1'];
        }
        if (strlen($input['list_2'])) {
            $intervals[$input['list_2']] = $input['interval_2'];
        }
        if (strlen($input['list_3'])) {
            $intervals[$input['list_3']] = $input['interval_3'];
        }
        
        $landing->intervals = json_encode($intervals);
        
        $landing->image1 = $input['image1'];
        $landing->image2 = $input['image2'];
        $landing->type = $input['type'];
        $landing->isocode = strtolower($input['isocode']);
//		$landing->exclude_suggestions = $input['exclude_suggestions'];
        
        foreach ($this->langs as $locale) {
            if (strlen($input['translation'][$locale]['title'])) {
                $landing->translateOrNew($locale)->title = $input['translation'][$locale]['title'];
            } else {
                $landing->translateOrNew($locale)->title = $input['translation'][config('env.DEFAULT_LOCALE')]['title'];
            }
            
            if (strlen($input['translation'][$locale]['subtitle'])) {
                $landing->translateOrNew($locale)->subtitle = $input['translation'][$locale]['subtitle'];
            } else {
                $landing->translateOrNew($locale)->subtitle = $input['translation'][config('env.DEFAULT_LOCALE')]['subtitle'];
            }
            
            if (strlen($input['translation'][$locale]['calltoaction'])) {
                $landing->translateOrNew($locale)->calltoaction = $input['translation'][$locale]['calltoaction'];
            } else {
                $landing->translateOrNew($locale)->calltoaction = $input['translation'][config('env.DEFAULT_LOCALE')]['calltoaction'];
            }
            
            if (strlen($input['translation'][$locale]['metadesc'])) {
                $landing->translateOrNew($locale)->metadesc = $input['translation'][$locale]['metadesc'];
            } else {
                $landing->translateOrNew($locale)->metadesc = $input['translation'][config('env.DEFAULT_LOCALE')]['metadesc'];
            }
            
            if (strlen($input['translation'][$locale]['metatitle'])) {
                $landing->translateOrNew($locale)->metatitle = $input['translation'][$locale]['metatitle'];
            } else {
                $landing->translateOrNew($locale)->metatitle = $input['translation'][config('env.DEFAULT_LOCALE')]['metatitle'];
            }
            
            if (strlen($input['translation'][$locale]['slug'])) {
                $landing->translateOrNew($locale)->slug = $input['translation'][$locale]['slug'];
            } else {
                $landing->translateOrNew($locale)->slug = $input['translation'][config('env.DEFAULT_LOCALE')]['slug'];
            }
            
            if (strlen($input['translation'][$locale]['above_content'])) {
                $landing->translateOrNew($locale)->above_content = $input['translation'][$locale]['above_content'];
            } else {
                $landing->translateOrNew($locale)->above_content = $input['translation'][config('env.DEFAULT_LOCALE')]['above_content'];
            }
            
            if (strlen($input['translation'][$locale]['below_content'])) {
                $landing->translateOrNew($locale)->below_content = $input['translation'][$locale]['below_content'];
            } else {
                $landing->translateOrNew($locale)->below_content = $input['translation'][config('env.DEFAULT_LOCALE')]['below_content'];
            }
            
            if (strlen($input['translation'][$locale]['search_url'])) {
                $landing->translateOrNew($locale)->search_url = $input['translation'][$locale]['search_url'];
            } else {
                $landing->translateOrNew($locale)->search_url = $locale . '/' . $input['translation'][config('env.DEFAULT_LOCALE')]['search_url'];
            }
            
            if (strlen($input['translation'][$locale]['influencer'])) {
                $landing->translateOrNew($locale)->influencer = $input['translation'][$locale]['influencer'];
            } else {
                $landing->translateOrNew($locale)->influencer = '';
            }
        }
        preg_match_all('/{{/', $landing->search_url, $matches);
        $landing->status = 1;
        
        
        if (count($matches[0]) > 1) {
            $landing->exclude_suggestions = 1;
        } else {
            $landing->exclude_suggestions = 0;
        }
        
        if (isset($input['exclude_suggestions'])) {
            $landing->exclude_suggestions = 1;
        }
        
        if (isset($input['include_random_footer'])) {
            $landing->include_random_footer = 1;
        }
        
        $landing->save();
        
        return redirect(route('admin.landings.edit', $landing->id))->with('success', 'success');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $landing = Landing::find($id);
        
        $item = $landing->toArray();
        foreach ($this->langs as $language) {
            $item['translation'][$language]['title'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->title;
            $item['translation'][$language]['subtitle'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->subtitle;
            $item['translation'][$language]['calltoaction'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->calltoaction;
            $item['translation'][$language]['above_content'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->above_content;
            $item['translation'][$language]['below_content'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->below_content;
            $item['translation'][$language]['metadesc'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->metadesc;
            $item['translation'][$language]['metatitle'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->metatitle;
            $item['translation'][$language]['slug'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->slug;
            $item['translation'][$language]['search_url'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->search_url;
            $item['translation'][$language]['influencer'] = empty($landing->translate($language)) ? '' : $landing->translate($language)->influencer;
            
        }
        
        if (strlen($item['intervals'])) {
            $decoded_intervals = json_decode($item['intervals'], true);
            $i = 1;
            foreach ($decoded_intervals as $key => $interval) {
                $item['list_' . $i] = $key;
                $item['interval_' . $i] = $interval;
                $i++;
            }
        }
        
        $landing_ids = Landing::get()->pluck('id')->toArray();
        
        $landing_ids = array_map('strval', $landing_ids);
        
        $isocodes = app('continents')->pluck('isocode');
        
        $landing_lists_ids = LandingsListParameter::get([
            'id',
            'name',
        ])->toArray();
        
        
        $landing_lists_ids = array_map(function ($landing_lists_id) {
            return [
                'value' => (string)$landing_lists_id['id'],
                'label' => $landing_lists_id['name'] . " " . (string)$landing_lists_id['id'],
            ];
        }, $landing_lists_ids);
        
        
        $landing_childen = $landing->children;
        $landing_childen = explode(',', $landing_childen);
        $landing_childen = array_filter($landing_childen);
        
        
        $children_struct = [];
        
        foreach ($landing_childen as $landing_child) {
            
            $landing_child = Landing::find($landing_child);
            $struct['isocode'] = $landing_child->isocode;
            $struct['landing_id'] = (string)$landing_child->id;
            $struct['intervals'] = [];
            
            $replace_map = [];
            $parent_intervals = array_keys(json_decode($landing->intervals, true));
            $child_intervals = array_keys(json_decode($landing_child->intervals, true));
            
            
            for ($i = 0; $i < count($parent_intervals); $i++) {
                $replace_map [$parent_intervals[$i]] = isset($child_intervals[$i]) ? $child_intervals[$i] : "";
            }
            
            
            foreach ($replace_map as $from => $to) {
                array_push($struct['intervals'], [
                    'from' => (string)$from,
                    'to'   => (string)$to,
                ]);
            }
            
            array_push($children_struct, $struct);
        }
        
        
        $empty_struct['isocode'] = "";
        $empty_struct['landing_id'] = "";
        $empty_struct['intervals'] = [];
        
        
        foreach (json_decode($item['intervals'], true) as $from => $interval) {
            array_push($empty_struct['intervals'], [
                'from' => (string)$from,
                'to'   => "",
            ]);
        }


//        dd($item, $this->langs, $landing_ids, $isocodes, $landing_lists_ids, $empty_struct, $children_struct);

        return view('admin.landings.edit', [
            'item'              => $item,
            'languages'         => $this->langs,
            'landing_ids'       => $landing_ids,
            'isocodes'          => $isocodes,
            'landing_lists_ids' => $landing_lists_ids,
            'empty_struct'      => $empty_struct,
            'children_struct'   => $children_struct,
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $landing = Landing::findOrFail($id);
        
        
        $slugChanged = true;
        
        foreach ($this->langs as $locale) {
            if ($request->input('translation')[$locale]['slug'] != $landing->translateOrNew($locale)->slug) {
                $slugChanged = true;
            }
        }
        
        if ($slugChanged) {
            $translation = $request->input('translation');
            
            foreach ($this->langs as $locale) {
                $translation[$locale]['slug'] = $translation[$locale]['slug'] != "" ? $translation[$locale]['slug'] : generateLandingSlug($translation[$locale]['title']);
            }
            $request->merge(['translation' => $translation]);
            
            $this->validate($request, [
                'translation.' . config('env.DEFAULT_LOCALE') . '.title'      => 'required',
                'translation.' . config('env.DEFAULT_LOCALE') . '.slug'       => 'required',
                'translation.' . config('env.DEFAULT_LOCALE') . '.search_url' => 'required',
            ]);
        }
        
        $input = $request->all();


//		dd($input);
        $intervals = null;
        if (strlen($input['list_1'])) {
            $intervals[$input['list_1']] = $input['interval_1'];
        }
        if (strlen($input['list_2'])) {
            $intervals[$input['list_2']] = $input['interval_2'];
        }
        if (strlen($input['list_3'])) {
            $intervals[$input['list_3']] = $input['interval_3'];
        }
        
        $landing->intervals = json_encode($intervals);
        $landing->type = $input['type'];
        $landing->image1 = $input['image1'];
        $landing->image2 = $input['image2'];
        $landing->isocode = $input['isocode'];
        
        
        foreach ($this->langs as $locale) {
            if (strlen($input['translation'][$locale]['title'])) {
                $landing->translateOrNew($locale)->title = $input['translation'][$locale]['title'];
            } else {
                $landing->translateOrNew($locale)->title = $input['translation'][config('env.DEFAULT_LOCALE')]['title'];
            }
            
            if (strlen($input['translation'][$locale]['subtitle'])) {
                $landing->translateOrNew($locale)->subtitle = $input['translation'][$locale]['subtitle'];
            } else {
                $landing->translateOrNew($locale)->subtitle = $input['translation'][config('env.DEFAULT_LOCALE')]['subtitle'];
            }
            
            if (strlen($input['translation'][$locale]['calltoaction'])) {
                $landing->translateOrNew($locale)->calltoaction = $input['translation'][$locale]['calltoaction'];
            } else {
                $landing->translateOrNew($locale)->calltoaction = $input['translation'][config('env.DEFAULT_LOCALE')]['calltoaction'];
            }
            
            if (strlen($input['translation'][$locale]['metadesc'])) {
                $landing->translateOrNew($locale)->metadesc = $input['translation'][$locale]['metadesc'];
            } else {
                $landing->translateOrNew($locale)->metadesc = $input['translation'][config('env.DEFAULT_LOCALE')]['metadesc'];
            }
            
            if (strlen($input['translation'][$locale]['metatitle'])) {
                $landing->translateOrNew($locale)->metatitle = $input['translation'][$locale]['metatitle'];
            } else {
                $landing->translateOrNew($locale)->metatitle = $input['translation'][config('env.DEFAULT_LOCALE')]['metatitle'];
            }
            
            if (strlen($input['translation'][$locale]['slug'])) {
                $landing->translateOrNew($locale)->slug = str_replace([
                    "-ISO",
                    "ISO-",
                ], [
                    "-" . strtolower($input['isocode']),
                    ucfirst($input['isocode']) . "-",
                ], $input['translation'][$locale]['slug']);
            } else {
                $landing->translateOrNew($locale)->slug = ucfirst($locale) . '-' . $input['translation'][config('env.DEFAULT_LOCALE')]['slug'];
            }
            
            if (strlen($input['translation'][$locale]['above_content'])) {
                $landing->translateOrNew($locale)->above_content = $input['translation'][$locale]['above_content'];
            } else {
                $landing->translateOrNew($locale)->above_content = $input['translation'][config('env.DEFAULT_LOCALE')]['above_content'];
            }
            if (strlen($input['translation'][$locale]['below_content'])) {
                $landing->translateOrNew($locale)->below_content = $input['translation'][$locale]['below_content'];
            } else {
                $landing->translateOrNew($locale)->below_content = $input['translation'][config('env.DEFAULT_LOCALE')]['below_content'];
            }
            
            if (strlen($input['translation'][$locale]['search_url'])) {
                $landing->translateOrNew($locale)->search_url = $input['translation'][$locale]['search_url'];
            } else {
                $landing->translateOrNew($locale)->search_url = $locale . '/' . $input['translation'][config('env.DEFAULT_LOCALE')]['search_url'];
            }
            
            if (strlen($input['translation'][$locale]['influencer'])) {
                $landing->translateOrNew($locale)->influencer = $input['translation'][$locale]['influencer'];
            } else {
                $landing->translateOrNew($locale)->influencer = '';
            }
        }
        
        
        if (isset($request->exclude_suggestions)) {
            $landing->exclude_suggestions = 1;
        } else {
            $landing->exclude_suggestions = 0;
        }
        
        $landing->include_random_footer = isset($request->include_random_footer) ? 1 : 0;
        $landing->save();
    
    
        $iso_codes = app('continents')->where('status', 1)->pluck('isocode')->toArray();
        $languages = array_unique (app('continents')->where('status', 1)->pluck('language')->toArray());
        
        foreach($iso_codes as $iso_code) {
            foreach($languages as $language) {
                clearRedisCache(["laravel:{$iso_code}:{$language}:footer_trendings"]);
                clearRedisCache(["laravel:{$iso_code}:{$language}:footer_often_searched"]);
            }
        }
        
        return redirect(route('admin.landings.edit', $id))->with('success', 'success');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (config('base.landings.delete') == false) {
            die();
        }
        
        $landing = Landing::find($id);
        
        
        if ($landing) {
            
            $children = $landing->children;
            $children = array_filter(explode(",", $children));
            foreach ($children as $child) {
                Landing::find($child)->delete();
            }
            
            if (config('base.landings.forceDelete') == true) {
                $landing->forceDelete();
            } else {
                $landing->delete();
            }
            
            return response()->json(['status' => 'ok']);
        }
        
        return response()->json([
            'status'  => 'error',
            'message' => trans('admin/landings.landing_not_found'),
        ]);
    }
    
    public function clone_landing(Request $request)
    {
        
        $landing = Landing::find($request->id);
        $clone = new Landing();
        $clone->status = $landing->status;
        $clone->image1 = $landing->image1;
        $clone->image2 = $landing->image2;
        $clone->exclude_suggestions = $landing->exclude_suggestions;
        $clone->type = $landing->type;
        $clone->isocode = $landing->isocode;
        $clone->intervals = $landing->intervals;
        foreach ($this->langs as $locale) {
            $clone->translateOrNew($locale)->slug = $landing->translate($locale)->slug . '_MODIFY_ME';
            $clone->translateOrNew($locale)->search_url = $landing->translate($locale)->search_url . '_MODIFY_ME';
            $clone->translateOrNew($locale)->metadesc = $landing->translate($locale)->metadesc;
            $clone->translateOrNew($locale)->metatitle = $landing->translate($locale)->metatitle;
            $clone->translateOrNew($locale)->title = $landing->translate($locale)->title . '_MODIFY_ME';
            $clone->translateOrNew($locale)->subtitle = $landing->translate($locale)->subtitle;
            $clone->translateOrNew($locale)->above_content = $landing->translate($locale)->above_content;
            $clone->translateOrNew($locale)->below_content = $landing->translate($locale)->below_content;
            $clone->translateOrNew($locale)->calltoaction = $landing->translate($locale)->calltoaction;
            $clone->translateOrNew($locale)->influencer = $landing->translate($locale)->influencer;
        }
        
        $clone->save();
        
        return response()->json(['status' => 'ok']);
    }
    
    
    public function syncChildren(Request $request)
    {
        
        $parent = Landing::find($request->parent_id);
        $parent->children = null;
        $parent->save();
        
        
        $item = $parent->toArray();
        foreach ($this->langs as $language) {
            $item['translation'][$language]['title'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->title;
            $item['translation'][$language]['subtitle'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->subtitle;
            $item['translation'][$language]['calltoaction'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->calltoaction;
            $item['translation'][$language]['above_content'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->above_content;
            $item['translation'][$language]['below_content'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->below_content;
            $item['translation'][$language]['metadesc'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->metadesc;
            $item['translation'][$language]['metatitle'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->metatitle;
            $item['translation'][$language]['slug'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->slug;
            $item['translation'][$language]['search_url'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->search_url;
            $item['translation'][$language]['influencer'] = empty($parent->translate($language)) ? '' : $parent->translate($language)->influencer;
        }
        
        
        if (strlen($item['intervals'])) {
            $decoded_intervals = json_decode($item['intervals'], true);
            $i = 1;
            foreach ($decoded_intervals as $key => $interval) {
                $item['list_' . $i] = $key;
                $item['interval_' . $i] = $interval;
                $i++;
            }
        }
        
        foreach ($request->structs as $struct) {
            
            $replace_map = [];
            foreach ($struct['intervals'] as $interval) {
                $replace_map[$interval['from']] = is_array($interval['to']) ? $interval['to']['value'] : $interval['to'];
            }
            
            
            $landing = Landing::find($struct['landing_id']);
            
            
            if (!$landing) {
                $landing = new Landing();
            }
            
            $landing->id = $struct['landing_id'];
            $landing->status = $item['status'];
            $landing->type = $item['type'];
            $landing->image1 = replace_curly_landing_ids($replace_map, $item['image1']);
            $landing->image2 = replace_curly_landing_ids($replace_map, $item['image2']);
            $landing->exclude_suggestions = $item['exclude_suggestions'];
            $landing->isocode = strtolower($struct['isocode']);
            
            
            $intervals = json_decode($item['intervals'], true);
            
            $new_interval = [];
            
            foreach ($intervals as $key => $interval) {
                $new_interval[$replace_map[$key]] = $interval;
            }
            
            $landing->intervals = json_encode($new_interval);
            
            foreach ($item['translation'] as $language_code => $translates) {
                foreach ($translates as $field => $translate) {
                    if ($field == "slug" || $field == "above_content" || $field == "below_content") {
                        $landing->translateOrNew($language_code)->slug =
                            str_replace(
                                [ucfirst($item['isocode']) . "-"],
                                [ucfirst($struct['isocode']) . "-"],
                                replace_curly_landing_ids($replace_map, $translate));
                    } else {
                        $landing->translateOrNew($language_code)->$field = replace_curly_landing_ids($replace_map,
                            $translate);
                    }
                }
            }
            $landing->include_random_footer = $parent->include_random_footer;
            $landing->is_child = 1;
            $landing->save();
            $parent->children .= $landing->id . ",";
            $parent->save();
        }
        
        return response()->json(['status' => 'ok']);
    }
    
    
    public
    function delete_sql_landing(
        Request $request
    ) {
        
        
        $parent_landing = Landing::find($request->parent_id);
        $parent_landing->children = str_replace($request->id . ",", "", $parent_landing->children);
        $parent_landing->save();
        
        Landing::find($request->id)->delete();
        
        return response()->json(['status' => 'ok']);
    }
}
