<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\JsonList;
use App\Models\Visit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
   public function index()
   {
       return view('admin.restaurants.index');
   }

    public function show(Company $restaurant)
    {
        $restaurant->city_formatted = $restaurant->extended_address .', '.$restaurant->city->name .', '.$restaurant->country->name;
	    $amenities = collect(json_decode(JsonList::where('slug' , 'amenities')->first()->json_value))->where('profile' , 1);
	    $restaurant_amenities = $restaurant->amenities()->wherePivot('status' , 1)->get();
	    $visits = Visit::where('type', 'restaurants')->where('key', $restaurant->id)->get();

	    $restaurant_amenities = $restaurant_amenities->filter(function ($item) {
            return $item['slug'] !== null;
        });
        $restaurant_amenities = $restaurant_amenities->pluck('slug')->toArray();


        $restaurant->open_hours = json_decode($restaurant->open_hours, true);
        if ($restaurant->open_hours !== null && (count($restaurant->open_hours) !== 7 || $restaurant->open_hours_updated === 0)) {

            $restaurant->open_hours = [
                    'monday' => ['-'],
                    'tuesday' => ['-'],
                    'wednesday' => ['-'],
                    'thursday' => ['-'],
                    'friday' => ['-'],
                    'saturday' => ['-'],
                    'sunday' => ['-']
                ];

        }

        return view('admin.restaurants.show', compact('restaurant', 'amenities', 'restaurant_amenities', 'visits'));
    }

    public function create()
    {
        return view('admin.restaurants.show');
    }
}
