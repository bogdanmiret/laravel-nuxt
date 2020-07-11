<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
    public function index()
    {
        $user = User::where('email', 'alexandru.dimitriu@vlinde.com')->first();
        $media = $user->getMedia('general_media');

        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'file' => 'required'
        ]);

        $user = User::where('email', 'alexandru.dimitriu@vlinde.com')->first();

        $user->addMedia($request->file('file'))->toMediaCollection('general_media', 'general_media');

        return response()->json([ 'message' => 'success' ]);
    }

    public function delete(Media $media)
    {
        $media->delete();

        return back();
    }
}
