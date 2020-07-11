<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Visit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:admin_view_statistics');

    }

    public function index()
    {
        return view('admin.statistics.index');
    }

    public function dt($type)
    {
        switch ($type) {
            case 'restaurants':
                return $this->getRestaurantStatistics();
                break;
            case 'dishes':
                return $this->getDishesStatistics();
                break;
            case 'terms':
                return $this->getTermsStatistics();
                break;
            case 'cities':
                return $this->getCitiesStatistics();
                break;
            case 'blog':
                return $this->getBlogStatistics();
                break;
            case 'sign-up':
                return $this->getSignUpStatistics();
                break;
            default:
                return [];
        }
    }

    private function getRestaurantStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'restaurants')
                ->join('companies', 'companies.id', '=', 'visits.key')
                ->join('cities', 'cities.id', '=', 'companies.city_id')
                ->select(
                    'key',
                    'month',
                    'year',
                    'two_month',
                    'two_year',
                    'companies.name',
                    'companies.new_slug',
                    'cities.slug'
                )
        )
            ->addColumn('companies', function ($visit) {
                return '<a href="' . route('trans.show.restaurant',
                        ['city' => $visit->slug, 'slug' => $visit->new_slug])
                    . '" target="_blank">' . $visit->name . '</a>';
            })
            ->rawColumns(['companies'])
            ->make(true);
    }

    private function getDishesStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'dishes')
                ->join('newdishes', 'newdishes.id', '=', 'visits.key')
                ->select(
                    'key',
                    'month',
                    'year',
                    'two_month',
                    'two_year',
                    'newdishes.title',
                    'newdishes.name'
                )
        )
            ->addColumn('newdishes', function ($visit) {
                return '<a href="' . route('trans.dishes.show', $visit->title)
                    . '" target="_blank">' . $visit->name . '</a>';
            })
            ->rawColumns(['newdishes'])
            ->make(true);
    }

    private function getTermsStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'terms')
                ->select(
                    'key as name',
                    'month',
                    'year',
                    'two_month',
                    'two_year'
                )
        )
            ->addColumn('name', function ($visit) {
                return '<a href="' . route("trans.execute.restaurants.search", [
                        'key1' => 'terms',
                        'var1' => $visit->name,
                    ]) . '" target="_blank">' . ucfirst($visit->name) . '</a>';
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    private function getCitiesStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'cities')
                ->join('cities', 'cities.slug', '=', 'visits.key')
                ->join('countries', 'countries.id', '=', 'cities.country_id')
                ->select(
                    'key as name',
                    'month',
                    'year',
                    'two_month',
                    'two_year',
                    'countries.iso_3166_2 as iso'
                )
        )
            ->addColumn('name', function ($visit) {
                return '<a href="' . route("trans.execute.restaurants.search", [
                        'key1' => 'city',
                        'var1' => $visit->iso . '_' . $visit->name,
                    ]) . '" target="_blank">' . ucfirst($visit->name) . '</a>';
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    private function getBlogStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'blog')
                ->where('blog_articles_trans.locale', app()->getLocale())
                ->join('blog_articles', 'blog_articles.id', '=', 'visits.key')
                ->join('blog_articles_trans',
                    'blog_articles_trans.blog_article_id', '=',
                    'blog_articles.id')
                ->select(
                    'key',
                    'month',
                    'year',
                    'two_month',
                    'two_year',
                    'blog_articles.slug',
                    'blog_articles_trans.name'
                )
        )
            ->addColumn('blog_articles', function ($visit) {
                return '<a href="' . route('trans.blog.show',
                        ['blog' => $visit->slug]) . '" target="_blank">'
                    . $visit->name . '</a>';
            })
            ->rawColumns(['blog_articles'])
            ->make(true);
    }

    private function getSignUpStatistics()
    {
        return Datatables::of(
            Visit::query()
                ->where('type', 'sign-up')
                ->select(
                    'key as name',
                    'month',
                    'year',
                    'two_month',
                    'two_year'
                )
        )
            ->addColumn('name', function ($visit) {
                return ucfirst($visit->name);
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}
