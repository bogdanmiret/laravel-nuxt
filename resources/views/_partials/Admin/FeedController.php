<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogArticle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FeedController extends Controller
{


    public function index()
    {
        return view('admin.feed.index');
    }

    public function getDTFeedArticles(Request $request)
    {

        if (!$request->ajax()) {
            abort('404');
        }

        $feed_articles = BlogArticle::query()->select(DB::raw('blog_articles_trans.name as name'), 'blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.blog_article_id')->whereNotNull('source_url')->groupBy('blog_articles.id');

        return Datatables::eloquent($feed_articles)
            ->editColumn('status', function ($feed_article) {
                return '<div class="status' . $feed_article->id . '">' . $feed_article->getStatus() . '</div>';
            })
            ->editColumn('categories', function ($feed_article) {
                return '<div class="status' . $feed_article->id . '">' . implode(',', $feed_article->categories->pluck('name')->toArray()) . '</div>';
            })
            ->filterColumn('slug', function ($feed_article, $keyword) {
                $sql = "blog_articles.slug like ?";
                $feed_article->select('blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.blog_article_id');
                $feed_article->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('name', function ($feed_article, $keyword) {
                $sql = "blog_articles_trans.name like ?";
                $feed_article->select(DB::raw('blog_articles_trans.name as name'), 'blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.page_id');
                $feed_article->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($feed_article) {

                $action = '<div class="btn-group btn-group-justified">';
                $action .= '<a class="' . config('base.btn.view') . '" href="'.route('trans.feed.show', $feed_article->slug).'">' . trans('global.btn.view') . '</a>';
                if (config('base.FeedArticle.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.feed.destroy', $feed_article->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action', 'categories'])
            ->make(true);
    }

    public function destroy($id)
    {
        if (config('base.FeedArticle.delete') == false) {
            die();
        }

        $blog_article = BlogArticle::find($id);

        if ($blog_article && ((Auth::user()->can(['admin_delete_feed']) && $blog_article->source_url !== null))) {

            if (config('base.FeedArticle.forceDelete') === true) {
                $blog_article->forceDelete();
            } else {
                $blog_article->delete();
            }

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => trans('admin/blog.blog_article_not_found')]);

    }


}
