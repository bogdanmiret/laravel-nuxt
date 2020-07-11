<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * Show reservations lists
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.reservations.index');
    }

    /**
     * Show reservations in datatable
     *
     * @param Request $request
     *
     * @return array
     */
    public function dt(Request $request)
    {
        $reservations = Booking::with([
            'company' => function ($query) {
                $query->with('country', 'package');
            },
        ]);

        return datatables($reservations)->toArray();
    }
}
