<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogArticleTrans;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\FileUpload;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Datatables;
use App\Models\Tag;

class BlogArticlesController extends Controller
{
    public function __construct()
    {
        $this->mainModel = BlogArticle::class;
        $this->langs = config('languages');
        $this->middleware('permission:admin_view_blog', ['except' => 'destroy']);
    }

    public function validation(Request $request)
    {
        $this->validate($request, [
            'pic.*' => config('base.files.images'),
            'translation.' . defaultLocale() . '.name' => 'required',
            'translation.' . defaultLocale() . '.categories.*' => 'required',
        ]);
    }

    public function index()
    {
        return view('admin.blog.index');
    }

    public function getDTBlogArticles(Request $request)
    {

        if (!$request->ajax()) {
            abort('404');
        }

        $blog_articles = BlogArticle::query()->select(DB::raw('blog_articles_trans.name as name'), 'blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.blog_article_id')->whereNull('source_url')->groupBy('blog_articles.id');

        return Datatables::eloquent($blog_articles)
            ->editColumn('status', function ($blog_article) {
                return '<div class="status' . $blog_article->id . '">' . $blog_article->getStatus() . '</div>';
            })
            ->editColumn('categories', function ($blog_article) {
                return '<div class="status' . $blog_article->id . '">' . implode(',', $blog_article->categories->pluck('name')->toArray()) . '</div>';
            })
            ->filterColumn('slug', function ($blog_article, $keyword) {
                $sql = "blog_articles.slug like ?";
                $blog_article->select('blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.blog_article_id');
                $blog_article->whereRaw($sql, ["%{$keyword}%"]);
//		        dd($blog_article->toSql());
            })
            ->filterColumn('name', function ($blog_article, $keyword) {
                $sql = "blog_articles_trans.name like ?";
                $blog_article->select(DB::raw('blog_articles_trans.name as name'), 'blog_articles.*')->leftJoin('blog_articles_trans', 'blog_articles.id', 'blog_articles_trans.page_id');
                $blog_article->whereRaw($sql, ["%{$keyword}%"]);
//		        dd($blog_article->toSql());
            })
            ->addColumn('action', function ($blog_article) {

                if ($blog_article->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $blog_article->id . '" data-href="' . route('admin.blog.change-status', $blog_article->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.blog.edit', $blog_article->id) . '">' . trans('global.btn.edit') . '</a>' .
                    '<a class="' . config('base.btn.edit') . '" href="' . route('admin.blog.republish', $blog_article->id) . '">' . trans('global.btn.republish') . '</a>';

                if (config('base.BlogArticle.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.blog.destroy', $blog_article->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action', 'categories'])
            ->make(true);
    }

    public function create()
    {
        $categories = BlogCategory::all()->pluck('name', 'id');
        return view('admin.blog.edit', ['languages' => $this->langs, 'categories' => $categories]);
    }

    public function store(Request $request)
    {

        $this->validation($request);
        $input = $request->all();
        $timeNow = Carbon::now();
        $timeCreated = Carbon::create(2019, 3, 1, 0, 0, 0);
        $diff = $timeNow->diffInDays($timeCreated);
        $fakeVotes = ($timeNow->timestamp - $timeCreated->timestamp) * 0.00003 - $diff + rand(1, 20);

        $blog = new BlogArticle();
        $blog->score = (int)$fakeVotes;
        $blog->author_id = Auth::id();
        $blog->slug = generateSlugWithTrashed(['class' => BlogArticle::class, 'element' => $input['translation'][defaultLocale()]['name']]);
        $blog->is_paid = isset($input['paid']) && $input['paid'] == 'on';

        foreach ($this->langs as $key => $locale) {
            $language = isset($input['translation'][$locale]['language']) ? $input['translation'][$locale]['language'] : false;

            if ($language) {
                $languageOnly[] = $language;
            }

            if (isset($languageOnly) && !in_array($locale, $languageOnly)) {
                continue;
            }

            $this->syncTags($input, $locale, $blog);

            $blog->translateOrNew($locale)->name = !empty($input['translation'][$locale]['name']) ? $input['translation'][$locale]['name'] : $input['translation'][defaultLocale()]['name'];
            $blog->translateOrNew($locale)->short_description = !empty($input['translation'][$locale]['short_description']) ? $input['translation'][$locale]['short_description'] : $input['translation'][defaultLocale()]['short_description'];
            $blog->translateOrNew($locale)->description = !empty($input['translation'][$locale]['description']) ? $input['translation'][$locale]['description'] : $input['translation'][defaultLocale()]['description'];
            $tags = isset($input['translation'][$locale]['tags']) ? implode(',', $input['translation'][$locale]['tags']) : isset($input['translation'][defaultLocale()]['tags']) ? implode(',', $input['translation'][defaultLocale()]['tags']) : '';
            $blog->translateOrNew($locale)->tags = $tags;
            $blog->save();
        }

        $uploadPhoto = $this->uploadPhoto($request, ['photo_id' => $blog->pic, 'files' => ['pic']]);
        if (!empty($uploadPhoto)) {
            $blog->pic = $uploadPhoto['medium'];
        }

        $blog->save();
        //Save  blog categories
        if (isset($input['categories'])) $blog->categories()->sync($input['categories']);

        if ($blog->save()) {
            $alert_message = trans('admin/blog.alert.c_success');
            $alert_status = 'success';
        }
        return redirect(route('admin.blog.edit', $blog->id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }


    public function edit($id)
    {
        $blog = BlogArticle::find($id);
        $item = $blog->toArray();
        $categories = $blog->categories()->get()->pluck('name', 'id');
        $item['categories'] = $blog->categories()->get()->pluck('id')->toArray();

        if ($blog->photo) {
            $item['photo'] = $blog->photo->location;
        }
        foreach ($this->langs as $language) {

            $tags = empty($blog->translate($language)) ? '' : $blog->translate($language)->tags;
            $tags = ltrim($tags, ',');
            $tags = explode(',', $tags);
            $tags = array_combine($tags, $tags);
            $item['translation'][$language]['used_tags'] = $tags;
            $item['translation'][$language]['name'] = empty($blog->translate($language)) ? '' : $blog->translate($language)->name;
            $item['translation'][$language]['short_description'] = empty($blog->translate($language)) ? '' : $blog->translate($language)->short_description;
            $item['translation'][$language]['description'] = empty($blog->translate($language)) ? '' : $blog->translate($language)->description;

            $item['translation'][$language]['tags'] = empty($blog->translate($language)) ? '' : $blog->translate($language)->tags;
            $item['translation'][$language]['tags'] = explode(', ', $item['translation'][$language]['tags']);
            $item['translation'][$language]['tags'] = array_combine($item['translation'][$language]['tags'], $item['translation'][$language]['tags']);
        }

        return view('admin.blog.edit', ['item' => $item, 'languages' => $this->langs, 'categories' => $categories, 'tags' => $tags]);
    }


    public function update(Request $request, $id)
    {
        dd($request->all());
        $this->validation($request);
        $input = $request->all();

        $blog = BlogArticle::find($id);

        $blog->is_paid = isset($input['paid']) && $input['paid'] == 'on';

        if ($blog->slug != $input['slug']) {
            $blog->slug = generateSlugWithTrashed(['class' => BlogArticle::class, 'element' => $input['slug']]);
        }

        if (isset($input['categories'])) $blog->categories()->sync($input['categories']);

        foreach ($this->langs as $locale) {
            if (isset($input['translation'][$locale]['delete'])) {
                BlogArticleTrans::where(['locale' => $locale, 'blog_article_id' => $blog->id])->delete();
                continue;
            }

            if (!empty($input['translation'][$locale]['name']) || !empty($input['translation'][$locale]['description'])) {
                $this->validate($request, [
                    'translation.' . $locale . '.name' => 'required',
                    'translation.' . $locale . '.description' => 'required',
                ]);
            } else continue;

            $this->syncTags($input, $locale, $blog);

            $blog->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
            $blog->translateOrNew($locale)->short_description = $input['translation'][$locale]['short_description'];
            $blog->translateOrNew($locale)->description = $input['translation'][$locale]['description'];
            $blog->translateOrNew($locale)->tags = isset($input['translation'][$locale]['tags']) ? implode(',', $input['translation'][$locale]['tags']) : '';
            $blog->save();
        }

        $uploadPhoto = $this->uploadPhoto($request, ['photo_id' => $blog->pic, 'files' => ['pic']]);
        if (!empty($uploadPhoto)) {
            $blog->pic = $uploadPhoto['medium'];
        }
        if ($blog->save()) {
            $alert_message = trans('admin/blog.alert.u_success');
            $alert_status = 'success';
        }
        return redirect(route('admin.blog.edit', $id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }


    public function destroy($id)
    {
        if (config('base.BlogArticle.delete') == false) {
            die();
        }

        $blog_article = BlogArticle::find($id);

        if ($blog_article && (Auth::user()->can(['admin_delete_blog']) && $blog_article->source_url === null)) {

            if (config('base.BlogArticle.forceDelete') === true) {
                $blog_article->forceDelete();
            } else {
                $blog_article->delete();
            }

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => trans('admin/blog.blog_article_not_found')]);

    }


    public function deletePicture($id)
    {

        $modelClass = explode('\\', $this->mainModel);
        $modelClass = $modelClass[count($modelClass) - 1];
        $storage_disk = config('base.storage.public');
        $storage_path = Storage::disk($storage_disk)->getDriver()->getAdapter()->getPathPrefix();
        $upload_location = config('base.' . $modelClass . '.fl');


        $blog_article = BlogArticle::find($id);
        if (!$blog_article) {
            return response()->json(['status' => 'error', 'message' => trans('admin/blog.blog_article_not_found')]);
        }

        $pic_id = $blog_article->pic;

        $photo_original = FileUpload::where(['id' => $pic_id, 'owner_type' => $modelClass])->orWhere('parent_id', $pic_id)->first();
        if ($photo_original) {
            if ($photo_original->parent_id == 0) {
                $all_photos = $photo_original->id;
            } else {
                $all_photos = $photo_original->parent_id;
            }

            $photos = FileUpload::where(['id' => $all_photos, 'owner_type' => $modelClass])->orwhere('parent_id', $all_photos)->get();

            if ($photos) {
                foreach ($photos as $photo) {
                    $file_location = $storage_path . $upload_location . $photo->location;
                    if (\File::exists($file_location)) {
                        unlink($file_location);
                    }
                    $photo->delete();
                }
            }

            if ($photo_original) {
                $file_location = $storage_path . $upload_location . $photo_original->location;
                if (\File::exists($file_location)) {
                    unlink($file_location);
                }
                $photo_original->delete();
            }
        }
        $blog_article->pic = null;
        $blog_article->save();
        return response()->json(['status' => 'ok']);
    }

    public function syncTags($input, $locale, $blog)
    {

        if (isset($input['translation'][$locale]['tags'])) {
            $newPostTags = $input['translation'][$locale]['tags'];
        } else {
            $newPostTags = [];
        }
        $oldPostTags = explode(',', $blog->translateOrNew($locale)->tags);
        $diffnewplus = array_diff($newPostTags, $oldPostTags);
        $diffnewminus = array_diff($oldPostTags, $newPostTags);
        foreach ($diffnewminus as $newtags) {
            $tag = Tag::where(['name' => $newtags, 'locale' => $locale])->first();
            if ($tag) {
                $tag->count = $tag->count - 1;
                $tag->save();
            } else {
                $tag = new Tag();
                $tag->name = $newtags;
                $tag->slug = generateSlug(['class' => Tag::class, 'element' => $newtags]);
                $tag->locale = $locale;
                $tag->count = 1;
                if (!empty($newtags)) {
                    $tag->save();
                }
            }
        }

        foreach ($diffnewplus as $newtags) {
            $tag = Tag::where(['name' => $newtags, 'locale' => $locale])->first();
            if ($tag) {
                $currentCount = $tag->count;
                $tag->count = $currentCount + 1;
                $tag->save();
            } else {
                $tag = new Tag();
                $tag->name = $newtags;
                $tag->slug = generateSlug(['class' => Tag::class, 'element' => $newtags]);
                $tag->locale = $locale;
                $tag->count = 1;
                if (!empty($newtags)) {
                    $tag->save();
                }
            }
        }


        /*
         * End tags system
         */
    }

    public function contentbuilder(Request $request)
    {
        $description = null;

        $blog = BlogArticle::find($request->blog);

        if ($blog !== null) {
            $translate = $blog->translate($request->lang);

            if ($translate !== null) {
                $description = $translate->description;
            }
        }

        return view('admin.blog.contentbuilder', ['description' => $description]);
    }

    public function republishBlog($id)
    {

        BlogArticle::find($id)->update([
            'created_at' => Carbon::now()
        ]);

        return back()->with([
            'status' => 'success',
            'message' => 'The post was republished.'
        ]);

    }

}

