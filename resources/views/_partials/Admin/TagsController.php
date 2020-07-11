<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{
    public function getSelTags(Request $request)
    {
        if(!$request->ajax()){
            abort('404');
        }

        $name = $request->name;

        if ($name) {
            return Tag::where("name", "LIKE", "$name%")->get();
        }
        return Tag::all();
    }
}
