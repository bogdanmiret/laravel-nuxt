<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use App\Models\EmailType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class EmailTemplatesController extends Controller
{
	public $langs;
	
	public function __construct()
	{
		$this->mainModel = EmailTemplate::class;
		$this->langs = config('languages');
		$this->middleware('permission:admin_view_email_templates', ['except' => 'destroy']);
		$this->middleware('permission:admin_delete_email_templates', ['only' => 'destroy']);
		
		$this->available_vars = new \stdClass();
		$this->available_vars->subject = '{USERNAME}, {SITE_NAME}';
		$this->available_vars->greeting = '{USERNAME}';
		$this->available_vars->intro_line = '{USERNAME}, {SITE_NAME}';
		$this->available_vars->outro_line = '{USERNAME}, {SITE_NAME}';
		$this->available_vars->action_url = '{SITE_URL}, {SITE_URL_PROFILE}';
		
	}
	
	public function index()
	{
		return view('admin.email-templates.index');
	}
	
	public function getDT(Request $request)
	{
		if(!$request->ajax()) {
			abort('404');
		}
		
		$email_templates = EmailTemplate::all();
		
		return Datatables::collection($email_templates)
			->addColumn('action', function ($email_template) {
				
				$action = '<div class="btn-group btn-group-justified">';
				$action .= '<a class="' . config('base.btn.edit') . '" href="' . route('admin.email-templates.edit', $email_template->id) . '">' . trans('global.btn.edit') . '</a>';
				if($email_template->admin_notification) {
					$action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.email-templates.destroy', $email_template->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
				}
				
				$action .= '</div>';
				
				return $action;
			})
			->make(true);
	}
	
	public function create()
	{
        $categories = EmailType::join('email_types_trans', 'email_types.id', '=', 'email_types_trans.email_type_id')
            ->select('email_types.id', 'email_types_trans.name')
            ->get()->pluck('name', 'id');

		return view('admin.email-templates.edit', ['languages' => $this->langs, 'available_vars' => $this->available_vars, 'categories' => $categories]);
	}
	
	
	public function edit($id)
	{
		$email_template = EmailTemplate::find($id);
		$item = [];
		
		
		foreach($this->langs as $language) {
			$item['translation'][$language]['name'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->name;
			$item['translation'][$language]['subject'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->subject;
			$item['translation'][$language]['greeting'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->greeting;
			$item['translation'][$language]['intro_line'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->intro_line;
			$item['translation'][$language]['outro_line'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->outro_line;
			$item['translation'][$language]['action_text'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->action_text;
			$item['translation'][$language]['action_url'] = empty($email_template->translate($language)) ? '' : $email_template->translate($language)->action_url;
		}
		$email_template->translation = $item['translation'];

        $categories = EmailType::all()->pluck('name', 'id');

		return view('admin.email-templates.edit', ['languages' => $this->langs, 'email_template' => $email_template, 'categories' => $categories]);
	}
	
	public function store(Request $request)
	{
		$input = $request->all();
		$this->validate($request, [
			'slug'                                           => 'required',
			'translation.' . defaultLocale() . '.name'       => 'required',
			'translation.' . defaultLocale() . '.greeting'   => 'required',
			'translation.' . defaultLocale() . '.intro_line' => 'required',
		]);
		$email_template = new EmailTemplate();
		$email_template->admin_notification = 1;
		$email_template->slug = str_slug($input['slug']);
        $email_template->type_id = $input['category'];

		
		foreach($this->langs as $locale) {
			if(!empty($input['translation'][$locale]['name']) || !empty($input['translation'][$locale]['intro_line'])) {
				$this->validate($request, [
					'translation.' . $locale . '.name'       => 'required',
					'translation.' . $locale . '.intro_line' => 'required',
				]);
			} else continue;
			
			$email_template->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			$email_template->translateOrNew($locale)->subject = $input['translation'][$locale]['subject'];
			$email_template->translateOrNew($locale)->subject_info = trans('admin/email_templates.available_vars', ['vars' => $this->available_vars->subject], $locale);
			$email_template->translateOrNew($locale)->greeting = $input['translation'][$locale]['greeting'];
			$email_template->translateOrNew($locale)->greeting_info = trans('admin/email_templates.available_vars', ['vars' => $this->available_vars->greeting], $locale);
			$email_template->translateOrNew($locale)->intro_line = $input['translation'][$locale]['intro_line'];
			$email_template->translateOrNew($locale)->intro_line_info = trans('admin/email_templates.available_vars', ['vars' => $this->available_vars->intro_line], $locale);
			$email_template->translateOrNew($locale)->outro_line = $input['translation'][$locale]['outro_line'];
			$email_template->translateOrNew($locale)->outro_line_info = trans('admin/email_templates.available_vars', ['vars' => $this->available_vars->outro_line], $locale);
			$email_template->translateOrNew($locale)->action_text = $input['translation'][$locale]['action_text'];
			$email_template->translateOrNew($locale)->action_url = $input['translation'][$locale]['action_url'];
			$email_template->translateOrNew($locale)->action_url_info = trans('admin/email_templates.available_vars', ['vars' => $this->available_vars->action_url], $locale);
		}
		
		if($email_template->save()) {
			$alert_message = trans('admin/email_templates.alert.u_success');
			$alert_status = 'success';
		}
		
		return redirect(route('admin.email-templates.edit', $email_template->id))->with(['status' => $alert_status, 'message' => $alert_message]);
	}
	
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'translation.' . defaultLocale() . '.name'       => 'required',
			'translation.' . defaultLocale() . '.greeting'   => 'required',
			'translation.' . defaultLocale() . '.intro_line' => 'required',
		]);
		$email_template = EmailTemplate::find($id);
		
		$input = $request->all();
        $email_template->type_id = $input['category'];

		foreach($this->langs as $locale) {
			if(!empty($input['translation'][$locale]['name']) || !empty($input['translation'][$locale]['intro_line'])) {
				$this->validate($request, [
					'translation.' . $locale . '.name'       => 'required',
					'translation.' . $locale . '.intro_line' => 'required',
				]);
			} else continue;
			
			$email_template->translateOrNew($locale)->name = $input['translation'][$locale]['name'];
			$email_template->translateOrNew($locale)->subject = $input['translation'][$locale]['subject'];
			$email_template->translateOrNew($locale)->greeting = $input['translation'][$locale]['greeting'];
			$email_template->translateOrNew($locale)->intro_line = $input['translation'][$locale]['intro_line'];
			$email_template->translateOrNew($locale)->outro_line = $input['translation'][$locale]['outro_line'];
			$email_template->translateOrNew($locale)->action_text = $input['translation'][$locale]['action_text'];
			$email_template->translateOrNew($locale)->action_url = $input['translation'][$locale]['action_url'];
		}
		
		if($email_template->save()) {
			$alert_message = trans('admin/email_templates.alert.u_success');
			$alert_status = 'success';
		}
		
		return redirect(route('admin.email-templates.edit', $email_template->id))->with(['status' => $alert_status, 'message' => $alert_message]);
	}
	
	public function destroy($id)
	{
		
		$email_template = EmailTemplate::find($id);
		
		if($email_template) {
			if(config('base.EmailTemplate.forceDelete') == true) {
				$email_template->forceDelete();
			} else {
				$email_template->delete();
			}
			
			return response()->json(['status' => 'ok']);
		}
		
		return response()->json(['status' => 'error', 'message' => trans('admin/email_templates.error.not_found')]);
	}


}
