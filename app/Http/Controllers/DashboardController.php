<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // You can fetch stats here later (e.g., total items, alerts)
        // $totalItems = \App\Models\Item::count();

        return view("dashboard/dashboard");
    }
}
