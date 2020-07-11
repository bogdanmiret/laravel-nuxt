<?php

namespace App\Http\Controllers\Admin;


use App\Models\Company;
use App\Models\Media;
use App\Models\User;
use App\Notifications\ContributionDenied;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Image;


class ContributionController extends Controller
{
    public function index()
    {
        return view('admin.contributions.index');
    }

    public function show(Media $contribution)
    {
        $user = User::find($contribution->custom_properties['user_id']);
        $restaurant = Company::find($contribution->model_id);
        return view('admin.contributions.show', compact('contribution', 'user', 'restaurant'));
    }

    public function destroy(Media $contribution)
    {


        $user = User::find($contribution->custom_properties['user_id']);

        $user->notify(new ContributionDenied($contribution));

        $contribution->delete();
        return response()->json(['status' => 'success', 'redirect' => route('admin.contribution.index')]);
    }

    public function rotate_image(Request $request)
    {

        $contribution = Media::find($request->contribution) ;

      
        $angle = -($request->angle);

        $contribution_path = config("filesystems.disks.{$contribution->disk}.root") ."/{$contribution->id}";
	    
        $old_path = $contribution_path.'/'.$contribution->file_name;
        
        $img = Image::make($old_path);
        
       
        
        $rotated_name = time().'_'.$contribution->file_name;

        $contribution->file_name = $rotated_name;
       
        
        $contribution->save();
        $img ->rotate($angle);
	    
        
        Image::make($img)->save($contribution_path.'/'.$rotated_name);

       
        $gallery_conversion = Image::make($contribution_path.'/conversions/contribution_gallery.jpg');
        $contribution_thumbnail = Image::make($contribution_path.'/conversions/gallery_thumbnail.jpg');

        $gallery_conversion->rotate($angle);
        $contribution_thumbnail->rotate($angle);

        Image::make($gallery_conversion)->save($gallery_conversion->basePath());
        Image::make($contribution_thumbnail)->save($contribution_thumbnail->basePath());
	
	    File::delete($old_path);

    }


}
