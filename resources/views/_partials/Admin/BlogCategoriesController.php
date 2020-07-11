<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class BlogCategoriesController extends Controller
{
    public function __construct()
    {
        $this->mainModel = BlogCategory::class;
        $this->langs = config('languages');
        $this->middleware('permission:admin_view_blog', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_blog', ['only' => 'destroy']);
    }

    public function validation(Request $request)
    {
        $this->validate($request, [
            'translation.' . defaultLocale() . '.name' => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blog_cat = BlogCategory::all();

        return view('admin.blog-categories.index')->with('blog', $blog_cat);
    }

    public function getDTBlogCategories(Request $request)
    {

        if(!$request->ajax()){
            abort('404');
        }

        $blog_cats = BlogCategory::all();
        return Datatables::of($blog_cats)
            ->editColumn('status', function ($blog_cat) {
                return '<div class="status' . $blog_cat->id . '">' . $blog_cat->getStatus() . '</div>';
            })
            ->addColumn('action', function ($blog_cat) {

                if ($blog_cat->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $statusName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $statusName = trans("global.btn.activate");
                }
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $blog_cat->id . '" data-href="' . route('admin.blog-categories.change-status', $blog_cat->id) . '" onclick="changeElementStatus(this)">' . $statusName . '</a>
                <a class="' . config('base.btn.edit') . '" href="' . route('admin.blog-categories.edit', $blog_cat->id) . '">' . trans('global.btn.edit') . '</a>';

                if (config('base.BlogCategory.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.blog-categories.destroy', $blog_cat->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function getSelBlogCategories(Request $request)
    {
        if(!$request->ajax()){
            abort('404');
        }

        $name = $request->name;

        if ($name) {
            return BlogCategory::whereTranslation("name", "LIKE", "$name%")->where("status", 1)->get();
        }
        return BlogCategory::where("status", 1)->get();
    }

    public function create()
    {
        return view('admin.blog-categories.edit', ['languages' => $this->langs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);

        $input = $request->all();

        $blog_cat = new BlogCategory();
	    $blog_cat->slug = str_slug($input['translation'][defaultLocale()]['name']);
        foreach ($this->langs as $locale) {
            $blog_cat->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
        }

        if($blog_cat->save()){
            $alert_message = trans('admin/blog.alert_category.c_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.blog-categories.edit', $blog_cat->id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog_cat = BlogCategory::find($id);


        $item = $blog_cat->toArray();
        foreach ($this->langs as $language) {
            $item['translation'][$language]['name'] = empty($blog_cat->translate($language)) ? '' : $blog_cat->translate($language)->name;
        }

        return view('admin.blog-categories.edit', [
            'item' => $item,
            'languages' => $this->langs,
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
        $this->validation($request);

        $input = $request->all();


        // update the langs
        $blog_cat = BlogCategory::find($id);

        $blog_cat->slug = str_slug($input['translation'][defaultLocale()]['name']);
        foreach ($this->langs as $locale) {
            $blog_cat->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
        }
        if($blog_cat->save()){
            $alert_message = trans('admin/blog.alert_category.u_success');
            $alert_status = 'success';
        }

        return redirect(route('admin.blog-categories.edit', $id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (config('base.BlogCategory.delete') == false) {
            die();
        }

        $blog_cat = BlogCategory::find($id);
        if($blog_cat->blogArticles()->count() > 0){
            return response()->json(['status' => 'error', 'message' => trans('admin/blog.error_delete_have_blog_articles')]);
        }

        if ($blog_cat) {
            if (config('base.BlogCategory.forceDelete') == true) {
                $blog_cat->forceDelete();
            } else {
                $blog_cat->delete();
            }
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'error', 'message' => trans('admin/blog.blog_category_not_found')]);

    }
}
