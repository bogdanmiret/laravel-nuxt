<?php

namespace App\Http\Controllers\Admin;

use App\Models\Newdish;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Media;

use Datatables;

class DishesController extends Controller
{

    public function __construct() {
        $this->middleware('permission:admin_view_dishes');
    }

    public function index()
    {
        return view('admin.dishes.index');
    }

    public function show($id)
    {
        $dish = Newdish::where('id', $id)->with('media', 'type', 'ingredients')->withCount('ingredients')->first();

        $images = Media::where('model_id', $id)->whereIn(
            'collection_name',
            ['dish_contributions', 'contributions']
        )->where('custom_properties', 'like', '%"approved":1%')->where('file_name', '!=', 'media')->get();


        if (!$dish) {
            abort(404, "No dish found with id {$id}");
        }

        return view('admin.dishes.show', compact('dish', 'images'));
    }

    public function datatable()
    {
        return Datatables::of(Newdish::query()->select(
            'id',
            'name',
            'title',
            'comment_number',
            'companies_count_cron'
        )->where('companies_count_cron', '>', 50)->withCount('ingredients'))
            ->addColumn('image', function ($dish) {
                $action = '<div class="img img-responsive">';
                if (isset($dish->getMedia("dish_main")[0])) {
                    $action .= '<img src="' . $dish->getMedia("dish_main")[0]->getUrl("restaurant_thumbnail") . '">  ';
                }
                $action .= '</div>';

                return $action;
            })
            ->addColumn('action', function ($dish) {
                $action = '<div class="btn-group btn-group-justified">
                <a class="btn btn-success" href="' . route(
                    'admin.dishes.show',
                    $dish->id
                ) . '">' . trans('global.btn.more_info') . '</a>';
                $action .= '<a href="' . route(
                    'admin.dishes.delete.picture',
                    $dish->id
                ) . '" class="btn btn-info"> Delete picture</a>';
                $action .= '<a href="' . route(
                    'admin.dishes.delete.recipe',
                    $dish->id
                ) . '" class="btn btn-warning"> Delete recipe</a>';
                $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route(
                    'admin.dishes.destroy',
                    $dish->id
                ) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }


    public function delete_dish($id) {
       
        Newdish::where('id', $id)->first()->delete();

        return response()->json('ok', 200);

        // return view('admin.dishes.show')->with(['status' => 'success', 'message' => 'Dish deleted successfully.']);
    }

    public function delete_recipe($id)
    {
        $dish = Newdish::where('id', $id)->with('ingredients')->first();

        $dish->ingredients()->detach();

        if (request()->ajax) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with(['status' => 'success', 'message' => 'All the ingredients have been detached.']);
    }

    public function delete_picture($id)
    {
        $dish = Newdish::find($id);
        $dish->getMedia("dish_main")[0]->forceDelete();

        return back()->with(['status' => 'success', 'message' => 'Picture detached successfully']);
    }

    public function replace_image($id, Request $request)
    {
        $this->validate($request, [
            'picture' => config('base.files.images')
        ]);

        $dish = Newdish::find($id);
        if (isset($dish->getMedia("dish_main")[0])) {
            $dish->getMedia("dish_main")[0]->forceDelete();
        }
        $dish->addMediaFromRequest("picture")->toMediaCollection('dish_main', 'dish_main');
        $dish->fresh()->save();

        clearRedisCache(['laravel:']);

        return back();
    }

    public function detach_ingredient($dish_id, $ingredient_id)
    {
        $dish = Newdish::find($dish_id);
        $dish->ingredients()->detach($ingredient_id);
        $dish->ingredients()->attach($ingredient_id);

        return back()->with(['status' => 'success', 'message' => 'Ingredient detached successfully']);
    }
}
