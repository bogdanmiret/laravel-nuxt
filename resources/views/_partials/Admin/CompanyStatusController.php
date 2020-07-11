<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyStatusController extends Controller
{
    public function mark_approved(Company $restaurant, $status)
    {
        $restaurant -> active = $status;
        $restaurant -> save();

        return redirect()->route('admin.restaurants.index')->with(['status' => 'success', 'message' => 'Restaurant '.$restaurant->name .' updated.']);
    }
}
