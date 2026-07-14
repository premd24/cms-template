<?php

namespace App\Http\Controllers;

use App\Models\SampleItem;

class DashboardController extends Controller
{
    /**
     * Display the boilerplate metrics dashboard.
     */
    public function index()
    {
        $sampleItemsCount = SampleItem::count();

        return view('dashboard', compact('sampleItemsCount'));
    }
}
