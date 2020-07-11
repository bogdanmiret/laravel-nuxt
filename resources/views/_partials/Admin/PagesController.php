<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTrans;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Datatables;

class PagesController extends Controller
{
    
    public $languages;
    public function __construct()
    {
        $this->mainModel = Page::class;
        
        $this->middleware('permission:admin_view_pages', ['except' => 'destroy']);
        $this->middleware('permission:admin_delete_pages', ['only' => 'destroy']);
        $this->languages = config('languages');
    }
    
    public function index()
    {
        return view('admin.pages.index');
    }
    
    public function getPages(Request $request)
    {
        
        if (!$request->ajax()) {
            abort('404');
        }
        
        $pages = Page::all();
        
        return Datatables::collection($pages)
            ->editColumn('status', function ($page) {
                return '<div class="status' . $page->id . '">' . $page->getStatus() . '</div>';
            })
            ->addColumn('action', function ($page) {
                if ($page->status == 1) {
                    $statusClass = config('base.btn.deactivate');
                    $deleteName = trans("global.btn.deactivate");
                } else {
                    $statusClass = config('base.btn.activate');
                    $deleteName = trans("global.btn.activate");
                }
                
                $action = '<div class="btn-group btn-group-justified">
                        <a class="' . $statusClass . '" href="javascript:void(0);" data-id="' . $page->id . '" data-href="' . route('admin.pages.change-status',
                        $page->id) . '" onclick="changeElementStatus(this)">' . $deleteName . '</a>
                        <a class="' . config('base.btn.edit') . '" href="' . route('admin.pages.edit',
                        $page->id) . '">' . trans('global.btn.edit') . '</a>';
                
                if (config('base.Page.delete') == true) {
                    
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.pages.destroy',
                            $page->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }
                $action .= '</div>';
                
                return $action;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function create()
    {
        return view('admin.pages.edit', ['languages' => $this->languages]);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'translation.' . defaultLocale() . '.name'    => 'required',
            'translation.' . defaultLocale() . '.content' => 'required',
        ]);
        
        $input = $request->all();
        $page = new Page();
        
        $page->display_in_footer = isset($input['display_in_footer']) ? 1 : 0;
        $page->display_title = isset($input['display_title']) ? 1 : 0;
        $page->display_in_copyright = isset($input['display_in_copyright']) ? 1 : 0;
        $page->status = isset($input['status']) ? 1 : 0;
        $page->full_width = isset($input['full_width']) ? 1 : 0;
        $page->embedded = isset($input['embedded']) ? 1 : 0;
        
        $page->admin_id = Auth::user()->id;
        
        //check if pageslug is unique!
        
        foreach ($this->languages as $locale) {
            if (!empty($input['translation'][$locale]['name']) || !empty($input['translation'][$locale]['content'])) {
                $this->validate($request, [
                    'translation.' . $locale . '.name'    => 'required',
                    'translation.' . $locale . '.content' => 'required',
                ]);
            } else {
                continue;
            }
            
            $page->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
            $page->translateOrNew($locale)->content = $input['translation'][$locale]['content'];
            $page->translateOrNew($locale)->slug = generateSlug([
                'class'   => PageTrans::class,
                'element' => $input['translation'][$locale]['name'],
            ]);
        }
        if ($page->save()) {
            $alert_message = trans('admin/pages.alert.c_success');
            $alert_status = 'success';
        }
    
        self::clear_pages_redis();
        
        return redirect(route('admin.pages.edit', $page->id))->with([
            'status'  => $alert_status,
            'message' => $alert_message,
        ]);
        
    }
    
    public function edit($id)
    {
        $page = Page::findOrFail($id);
       
        $item = $page->toArray();
        foreach ($this->languages as $language) {
            $item['translation'][$language]['name'] = empty($page->translate($language)) ? '' : $page->translate($language)->name;
            $item['translation'][$language]['content'] = empty($page->translate($language)) ? '' : $page->translate($language)->content;
            $item['translation'][$language]['slug'] = empty($page->translate($language)) ? '' : $page->translate($language)->slug;
        }
        
        return view('admin.pages.edit', ['page' => $item, 'languages' => $this->languages, 'id' => $id]);
    }
    
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        
        $this->validate($request, [
            'translation.' . defaultLocale() . '.name'    => 'required',
            'translation.' . defaultLocale() . '.content' => 'required',
            'translation.' . defaultLocale() . '.slug'    => 'required',
        ]);
        
        
        
        $input = $request->all();
        $page->display_in_footer = isset($input['display_in_footer']) ? 1 : 0;
        $page->display_title = isset($input['display_title']) ? 1 : 0;
        $page->display_in_copyright = isset($input['display_in_copyright']) ? 1 : 0;
        $page->status = isset($input['status']) ? 1 : 0;
        $page->full_width = isset($input['full_width']) ? 1 : 0;
        $page->embedded = isset($input['embedded']) ? 1 : 0;
        
        foreach ($this->languages as $locale) {
            
            if (!empty($input['translation'][$locale]['name']) || !empty($input['translation'][$locale]['content'])) {
                $this->validate($request, [
                    'translation.' . $locale . '.name'    => 'required',
                    'translation.' . $locale . '.content' => 'required',
                    'translation.' . $locale . '.slug'    => 'required',
                ]);
            } else {
                continue;
            }
            
            $page->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
            $page->translateOrNew($locale)->content = $input['translation'][$locale]['content'];
            $page->translateOrNew($locale)->slug = generateSlug([
                'class'   => PageTrans::class,
                'element' => $input['translation'][$locale]['slug'],
                'id'      => $page->translate($locale)->id,
            ]);
        }
        if ($page->save()) {
            $alert_message = trans('admin/pages.alert.u_success');
            $alert_status = 'success';
        }
    
        self::clear_pages_redis();
        
        return redirect(route('admin.pages.edit', $id))->with(['status' => $alert_status, 'message' => $alert_message]);
    }
    
    public function destroy($id)
    {
        if (config('base.Page.delete') == false) {
            die();
        }
        
        $page = Page::find($id);
        
        if ($page) {
            if (config('base.Page.forceDelete') == true) {
                $page->forceDelete();
            } else {
                $pageTrans = PageTrans::where('page_id', $id)->get();
                foreach ($pageTrans as $pT) {
                    $pT->delete();
                }
                $page->delete();
            }
            
            return response()->json(['status' => 'ok']);
        }
        
        self::clear_pages_redis();
        return response()->json(['status' => 'error', 'message' => trans('admin/admin.error.page-not-found')]);
    }
    
    
    protected function clear_pages_redis()
    {
        foreach ($this->languages as $language) {
            clearRedisCache(["laravel:footer_pages:{$language}:list"]);
        }
    }
    
}
