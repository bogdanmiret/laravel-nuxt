<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantClaimRequest;
use App\Models\Claim;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{

    public function index()
    {
        return view('admin.claims.index');
    }

    public function show(Claim $claim)
    {
        return view('admin.claims.show', compact('claim'));
    }

}
