<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Media;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Datatables;

    class ContributionTableController extends Controller
    {
        public function __construct()
        {

        }


        public function __invoke(Request $request)
        {

            if (!$request->ajax()) {
                abort('404');
            }


//            $contributions = Media::where('collection_name', 'contributions')->where('custom_properties', 'like', '%"approved":0%')->get();
            $contributions = Media::where('collection_name', 'contributions')->where('model_type', 'App\\Models\\Company');
	            //   if($request->pending == 1){
		             $contributions->where('custom_properties' , 'like' , '%"approved":0%');
	            //   }
	        
	        $contributions = $contributions->orderBy('created_at', 'desc') ->get();
            
	      
            

            return Datatables::of($contributions)
                ->addColumn('approved', function ($contribution) {
                    return $contribution->approved_label;
                })
                ->addColumn('company_name', function ($contribution) {
                    return $contribution->company_label;
                })
                ->addColumn('user_name', function ($contribution) {
                    return $contribution->name_label;
                })
                ->addColumn('actions', function ($contribution) {
                    return $contribution->action_buttons;
                })
                ->rawColumns(['approved', 'actions'])
                ->make(TRUE);
        }
    }
