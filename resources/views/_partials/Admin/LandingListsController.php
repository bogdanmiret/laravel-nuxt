<?php

namespace App\Http\Controllers\Admin;

use App\Models\LandingListTrans;
use App\Models\LandingsListParameter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\LandingsListValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Datatables;
use Illuminate\Support\Facades\Schema;

class LandingListsController extends Controller
{

    public function __construct()
    {
        $this->langs = config('languages');
        $this->mainModel = LandingsListParameter::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $landings = LandingsListParameter::all();

        return view('admin.landing-lists.index')->with('landings', $landings);
    }


    public function GetLandingLists()
    {

        $landings = LandingsListParameter::all();
        return Datatables::of($landings)
            ->addColumn('action', function ($landing) {

                if ($landing->changeStatus == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.landings-lists.edit', $landing->id) . '">' . trans('global.btn.edit') . '</a>';

                if (config('base.landings-lists.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.landings-lists.destroy', $landing->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = config('base.landings-lists.types');
        array_unshift($types , "");

        return view('admin.landing-lists.edit', [
            'types' => $types,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $landing = new LandingsListParameter;
        $input = $request->all();
        $landing->name = $input['name'];
        $landing->col_name = '';
        $landing->type = $input['type'];
        if($landing->type != config('base.landings-lists.types.custom'))
            $landing->col_name = $input['col_name'];
        $landing->save();
        $id = $landing->id;
        $elements = $input['elements'];
        foreach($elements as $element){
            $landinglist = new LandingsListValue();
            $landinglist->param_id = $id;
            $landinglist->ref_id = $element;
            $landinglist->save();
        }



        return redirect(route('admin.landings-lists.edit', $id))->with('success', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $landing = LandingsListParameter::find($id);
        $types = config('base.landings-lists.types');
        array_unshift($types , "");
        if($landing->type == config('base.landings-lists.types.custom')){
            $elements = $landing->getElements->pluck('ref_id','ref_id');
            $elementsIds = $landing->getElements()->pluck('ref_id');
            $columns = array();
        } else {
            $classname = "App\Models\\" . $landing->type;
            $tablename = with(new $classname)->getTable();
            $columns = Schema::getColumnListing($tablename);
            $columns = array_combine($columns, $columns);
            $elementsIds = $landing->getElements()->pluck('ref_id','ref_id');
            $elements = $classname::wherein('id', $elementsIds)->pluck($landing->col_name, 'id');
        }
        return view('admin.landing-lists.edit', [
            'item' => $landing,
            'types' => $types,
            'cols' => $columns,
            'elements' => $elements,
            'elementsIds' => $elementsIds->toarray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $landing = LandingsListParameter::findOrFail($id);
        $input = $request->all();
        $landing->name = $input['name'];
        $landing->col_name = '';
        $landing->type = $input['type'];
        if($landing->type != config('base.landings-lists.types.custom'))
            $landing->col_name = $input['col_name'];

        $landing->getElements()->delete();

        if(isset($input['allelements'])){
            $landing->all_values = true;
        } else {
            $landing->all_values = false;
            $elements = $input['elements'];
            foreach($elements as $element){
                $landinglist = new LandingsListValue();
                $landinglist->param_id = $id;
                $landinglist->ref_id = $element;
                $landinglist->save();
            }
        }


        $landing->save();


        return redirect(route('admin.landings-lists.edit', $id))->with('success', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(config('base.landings-lists.delete') == false){
            die();
        }

        $landings = LandingsListParameter::find($id);
        Log::info($landings);
        if ($landings) {
            if(config('base.landings-lists.forceDelete') == true){
                $landings->forceDelete();
            } else {
                $landings->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/landings.landing_list_not_found')]);
    }
}
